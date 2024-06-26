<?php

include_once '_common/action_variables.php';

$session_crypted = "";

if ($form_id !== null or $form_id != "") {

    $formres = $db->sql("SELECT * FROM ppat_forms WHERE id=?", 'i', $form_id);
    while ($formrow = $formres->fetch_assoc()) {
        $formdata = $formrow;
        $form_enabled = $formrow['status'];
        $form_exist = 1;
    }

    if ($form_exist == 1) {

        if ($form_enabled == 1) {

            $csrf_token = $tokens->get_token();
            $response = 2;
            $message = $csrf_token;
            $error_data = "";

            $session = random_str(50);
            $session_crypted = $crypt->encrypt($session);

            $db->sql("INSERT INTO ppat_sessions SET session_id = ?, session_time=?, user_ip=?", 'sis', $session, $current_time, $user_ip);
        } else {
            $response = 0;
            $error_data = $message = "DISABLED";
        }
    } else {
        $response = 0;
        $error_data = $message = "NO MATCHING FORM";
    }
} else {
    $response = 0;
    $error_data = $message = "NO ID SET";
}

$return_arr = array("response" => $response, "message" => $message, "session" => $session_crypted, "error_data" => $error_data);
echo json_encode($return_arr);
