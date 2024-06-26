<?php
if (is_malicious()) {

    log_malicious();
    $result['status'] = 0;
    $result['feedback'] = "INVALID REQUEST!";
} else {

    $status = 0;
    $alreadyCheckedIn = 0;

    $res = $db->sql("SELECT * FROM ppat_submissions WHERE session_id = ?", "s", $typea);
    while ($row = $res->fetch_assoc()) {

        $status = 1;

        $rowId = $row['id'];
        $formId = $row['form_id'];

        
        

        $res2 = $db->sql("SELECT * FROM ppat_entry_actions WHERE form_id = ?", "s", $formId);
        while ($row2 = $res2->fetch_assoc()) {

            $action_number = $row2['action_number'];
            $qr_triggered =  $row2['qr_triggered'];

            if ($qr_triggered == 1) {

                $savedActionKey = 'action_' . $action_number;
                $alreadyCheckedIn = $row[$savedActionKey];

                


                $updateSql = "UPDATE ppat_submissions SET " . $savedActionKey . " = 1 WHERE id = ?";
                $db->sql($updateSql, "s", $rowId);
            }
        }

        $result['goto'] = $rowId;
        $result['already_checked_in'] = $alreadyCheckedIn;
    }

    $result['status'] = $status;
}






