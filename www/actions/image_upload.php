<?php

include_once('/var/www/html/actions/_common/action_variables.php');

if ($form_id !== null or $form_id != "") {

  $token_check = $tokens->check_token($csrf_token);

  if ($token_check == 1 && $session_token_check == 1) {

    $response = 1;
    $message = $csrf_token;
    $error_data = "NONE";

    $session_id_res = $db->sql("SELECT * FROM ppat_sessions WHERE session_id=?", 's', $session);
    while ($session_id_row = $session_id_res->fetch_assoc()) {
      $session_id = $session_id_row['id'];
    }


    $newfile = $_FILES['file']['name'];
    $ext = strtolower(pathinfo($newfile, PATHINFO_EXTENSION));
    $file_name = "ppat-image-" . random_str(10);

    if (in_array($ext, $allowed_file_types)) {

      if (!empty($_FILES)) {
        $tempFile = $_FILES['file']['tmp_name'];
        $targetFile =  $tempFolder . $file_name . "." . $ext;
        move_uploaded_file($tempFile, $targetFile);
      }

      $db->sql("INSERT INTO ppat_temp_files SET session_id=?, the_file=?, extension=?, original_filename=?, upload_time=?", 'ssssi', $session, $file_name, $ext, $newfile, $current_time);
    } else {
      $response = 0;
      $error_data = "INVALID FILE TYPE";
    }
  }
}


$return_arr = array("response" => $response, "message" => $message, "session" => $session_crypted, "error_data" => $error_data);
echo json_encode($return_arr);