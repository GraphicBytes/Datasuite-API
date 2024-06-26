<?php
$status = 0;
$token_check = 0;
$readonly = 1;


if (isset($_POST['CSRFtoken'])) {
    $token = $_POST['CSRFtoken'];
    $token_check = $tokens->check_token($token);
}


if (is_malicious()) {

    log_malicious();
    $result['status'] = 0;
    $result['feedback'] = "INVALID REQUEST!";
} else {


    $formID = $_POST['formID'];
    $entryID = $_POST['entryID'];


    $status_check = 0;
    $result['newtoken'] = $tokens->get_token();

    $sql = "SELECT * FROM ppat_submissions WHERE id=?";
    $res = $db->sql($sql, "i", $entryID);
    while ($row = $res->fetch_assoc()) {

        $form_id = $row['form_id'];
        $saved_form_data = unserialize($row['form_data']);
        $status_check = 1;
    }


    if (!empty($userGroupsReadWrite)) {

        $sql = "SELECT * FROM ppat_forms WHERE user_group IN (" . implode(",", array_map('intval', $userGroupsReadWrite)) . ") AND id='$form_id'";
        $res = $db->sql($sql);
        while ($row = $res->fetch_assoc()) {
            $readonly = 0;
        }
    }


    if ($readonly == 1) {
        $status_check == 0;
    }



    if ($status_check == 1) {

        $dropbox = $saved_form_data['dropbox'];

        $image_belongs_to_form = 0;

        foreach ($dropbox as $value) {

            if ($value == $typea) {
                $image_belongs_to_form = 1;
            }
        }
    }



    if ($image_belongs_to_form == 1) {


        if ($typeb == 1) {
            $upload_status = 0;
        } else if ($typeb == 0) {            
            $upload_status = 1;
        } else {
            $upload_status = 0;
        }

        $db->sql(" UPDATE ppat_uploads SET status=?, form_id=? WHERE id=? ", "iii", $upload_status, $form_id, $typea);

        $result['new_value'] = $upload_status;

        $result['typea'] = $typea;

        $status = 1;
    }
}






$result['status'] = $status;
