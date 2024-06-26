<?php
if (is_malicious()) {

    log_malicious();
    $result['status'] = 0;
    $result['feedback'] = "INVALID REQUEST!";
} else {

    $status_check = 0;
    
    $sql = "SELECT * FROM ppat_submissions WHERE id=?";
    $res = $db->sql($sql, "i", $typea);
    while ($row = $res->fetch_assoc()) {
       
        $form_id = $row['form_id'];
        $status_check = 1;
        
    }



    if ($status_check == 1) {

        $sql = "SELECT * FROM ppat_forms WHERE user_group IN (" . implode(",", array_map('intval', $userGroupsAll)) . ") AND id='$form_id' ORDER BY id DESC";
        $res = $db->sql($sql);
        while ($row = $res->fetch_assoc()) {

            $status_check = 2;
        }
    }



    if ($status_check == 2) {

        $result['status'] = 1;
        $result['form_id'] = $form_id;
    } else {
        $result['valid'] = 0;
        $result['status'] = 0;
    }
}
