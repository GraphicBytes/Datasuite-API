<?php

include_once('_common/action_variables.php');

if ($form_id !== null or $form_id != "") {

  $token_check = $tokens->check_token($csrf_token);

  if ($token_check == 1 && $session_token_check == 1) {

    $original_filename = $_POST['name'];

    $tempfileres = $db->sql("SELECT * FROM ppat_temp_files WHERE session_id=? AND original_filename=?", 'ss', $session, $original_filename);
    while ($tempfilerow = $tempfileres->fetch_assoc()) {
      $temp_file_id = $tempfilerow['id'];
      $temp_file_name = $tempfilerow['the_file'];
      $temp_file_extension = $tempfilerow['extension'];

      $file_to_delete = $tempFolder . $temp_file_name . "." . $temp_file_extension;
      unlink($file_to_delete);
      $db->sql("DELETE FROM ppat_temp_files WHERE id=?", 'i', $temp_file_id);
    }
  }
}





$return_arr = array("response" => $response, "message" => $message, "session" => $session, "error_data" => $error_data);
echo json_encode($return_arr);
