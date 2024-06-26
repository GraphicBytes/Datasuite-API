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


    $status_check = 0;
    $result['newtoken'] = $tokens->get_token();

    $sql = "SELECT * FROM ppat_submissions WHERE id=?";
    $res = $db->sql($sql, "i", $typea);
    while ($row = $res->fetch_assoc()) {

        $form_id = $row['form_id'];
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


        $sql = "SELECT * FROM ppat_submissions WHERE id=?";
        $res = $db->sql($sql, "i", $typea);
        while ($row = $res->fetch_assoc()) {

            $form_data = unserialize($row['form_data']);
            $form_override_data = unserialize($row['override_data']);
        }



        $actionIDs = array();

        $sql = "SELECT * FROM ppat_forms WHERE user_group IN (" . implode(",", array_map('intval', $userGroupsAll)) . ") AND id='$form_id'";
        $res = $db->sql($sql);
        while ($row = $res->fetch_assoc()) {

            $act1_id = $row['act1_id'];
            $act2_id = $row['act2_id'];
            $act3_id = $row['act3_id'];
            $act4_id = $row['act4_id'];
            $act5_id = $row['act5_id'];
            $act6_id = $row['act6_id'];
            $act7_id = $row['act7_id'];
            $act8_id = $row['act8_id'];
            $act9_id = $row['act9_id'];
            $act10_id = $row['act10_id'];
            $act11_id = $row['act11_id'];
            $act12_id = $row['act12_id'];
            $act13_id = $row['act13_id'];
            $act14_id = $row['act14_id'];
            $act15_id = $row['act15_id'];

            if ($act1_id != 0) {
                $actionIDs[] = $act1_id;
            }
            if ($act2_id != 0) {
                $actionIDs[] = $act2_id;
            }
            if ($act3_id != 0) {
                $actionIDs[] = $act3_id;
            }
            if ($act4_id != 0) {
                $actionIDs[] = $act4_id;
            }
            if ($act5_id != 0) {
                $actionIDs[] = $act5_id;
            }
            if ($act6_id != 0) {
                $actionIDs[] = $act6_id;
            }
            if ($act7_id != 0) {
                $actionIDs[] = $act7_id;
            }
            if ($act8_id != 0) {
                $actionIDs[] = $act8_id;
            }
            if ($act9_id != 0) {
                $actionIDs[] = $act9_id;
            }
            if ($act10_id != 0) {
                $actionIDs[] = $act10_id;
            }
            if ($act11_id != 0) {
                $actionIDs[] = $act11_id;
            }
            if ($act12_id != 0) {
                $actionIDs[] = $act12_id;
            }
            if ($act13_id != 0) {
                $actionIDs[] = $act13_id;
            }
            if ($act14_id != 0) {
                $actionIDs[] = $act14_id;
            }
            if ($act15_id != 0) {
                $actionIDs[] = $act15_id;
            }

            $status_check = 2;
        }
    }

    if ($status_check == 2) {

        $comments = $crypt->encrypt(urldecode($_POST['comments']));

        $comments_found = 0;
        $sql = "SELECT * FROM ppat_entry_comments WHERE user_id='$logged_in_id' AND entry_id=?";
        $res = $db->sql($sql, "i", $typea);
        while ($row = $res->fetch_assoc()) {
            $comments_id = $row['id'];
            $comments_found = 1;
        }

        if ($comments_found == 1) {
            $db->sql("UPDATE ppat_entry_comments SET content=?, last_update='$current_time' WHERE id=?", "si", $comments, $comments_id);
        } else {
            $db->sql("INSERT INTO ppat_entry_comments SET user_id='$logged_in_id', entry_id='$typea', content=?, last_update='$current_time'", "s", $comments);
        }



        $sql2 = "SELECT * FROM ppat_entry_actions WHERE id IN (" . implode(",", array_map('intval', $actionIDs)) . ") ORDER BY id ASC";
        $res2 = $db->sql($sql2);
        while ($row2 = $res2->fetch_assoc()) {

            $action_number = $row2['action_number'];
            $action_name = $row2['action_name'];


            if (isset($_POST[$action_name])) {

                $action_value = $_POST[$action_name];

                if (
                    $action_value === 1
                    || $action_value === "1"
                    || $action_value === "on"
                    || $action_value === 0
                    || $action_value === "0"
                    || $action_value === "off"
                ) {

                    if (
                        $action_value === 1
                        || $action_value === "1"
                        || $action_value === "on"
                    ) {
                        $action_value = 1;
                    }

                    if (
                        $action_value === 0
                        || $action_value === "0"
                        || $action_value === "off"
                    ) {
                        $action_value = 0;
                    }

                    if ($action_number == 1) {
                        $db->sql("UPDATE ppat_submissions SET action_1=? WHERE id=?", "ii", $action_value, $typea);
                    }
                    if ($action_number == 2) {
                        $db->sql("UPDATE ppat_submissions SET action_2=? WHERE id=?", "ii", $action_value, $typea);
                    }
                    if ($action_number == 3) {
                        $db->sql("UPDATE ppat_submissions SET action_3=? WHERE id=?", "ii", $action_value, $typea);
                    }
                    if ($action_number == 4) {
                        $db->sql("UPDATE ppat_submissions SET action_4=? WHERE id=?", "ii", $action_value, $typea);
                    }
                    if ($action_number == 5) {
                        $db->sql("UPDATE ppat_submissions SET action_5=? WHERE id=?", "ii", $action_value, $typea);
                    }
                    if ($action_number == 6) {
                        $db->sql("UPDATE ppat_submissions SET action_6=? WHERE id=?", "ii", $action_value, $typea);
                    }
                    if ($action_number == 7) {
                        $db->sql("UPDATE ppat_submissions SET action_7=? WHERE id=?", "ii", $action_value, $typea);
                    }
                    if ($action_number == 8) {
                        $db->sql("UPDATE ppat_submissions SET action_8=? WHERE id=?", "ii", $action_value, $typea);
                    }
                    if ($action_number == 9) {
                        $db->sql("UPDATE ppat_submissions SET action_9=? WHERE id=?", "ii", $action_value, $typea);
                    }
                    if ($action_number == 10) {
                        $db->sql("UPDATE ppat_submissions SET action_10=? WHERE id=?", "ii", $action_value, $typea);
                    }
                    if ($action_number == 11) {
                        $db->sql("UPDATE ppat_submissions SET action_11=? WHERE id=?", "ii", $action_value, $typea);
                    }
                    if ($action_number == 12) {
                        $db->sql("UPDATE ppat_submissions SET action_12=? WHERE id=?", "ii", $action_value, $typea);
                    }
                    if ($action_number == 13) {
                        $db->sql("UPDATE ppat_submissions SET action_13=? WHERE id=?", "ii", $action_value, $typea);
                    }
                    if ($action_number == 14) {
                        $db->sql("UPDATE ppat_submissions SET action_14=? WHERE id=?", "ii", $action_value, $typea);
                    }
                    if ($action_number == 15) {
                        $db->sql("UPDATE ppat_submissions SET action_15=? WHERE id=?", "ii", $action_value, $typea);
                    }
                }
            }
        }



        $overrideData = array();

        $formfields = array();
        $formres = $db->sql("SELECT * FROM ppat_form_fields WHERE form_id=? ORDER BY field_order ASC  ", 'i', $form_id);
        while ($formrow = $formres->fetch_assoc()) {
            $formfields[$formrow['id']] = $formrow;
        }

        $manual_entries_present = 0;

        foreach ($formfields as $key) {

            $field_name = $key['field_name'];
            $field_type = $key['field_type'];
            $field_editable = $key['editable'];


            if ($field_editable == 1) {

                


                $current_value = $form_data[$field_name];
                $submitted_value = htmlspecialchars(urldecode($_POST[$field_name]), ENT_QUOTES);

                if ($current_value != $submitted_value) {
                    $manual_entries_present = 1;
                    $overrideData[$field_name] = $submitted_value;
                }

            }
        }

        if ($manual_entries_present == 1) {
            $overrideData = serialize($overrideData);

            $db->sql("UPDATE ppat_submissions SET override_data=? WHERE id=?", "si", $overrideData, $typea);
        } else {
            $db->sql("UPDATE ppat_submissions SET override_data=NULL WHERE id=?", "i", $typea);
        }


        // $result["manual_entries_present"] = $manual_entries_present;
        // $result["current_value"] = $current_value;
        // $result["submitted_value"] = $submitted_value;


        $status = 1;
    }
}


if ($status == 1) {
    $db->sql("UPDATE ppat_submissions SET status='1', last_mod_time=? WHERE id=?", "ii", $request_time, $typea);
} else {
    $db->sql("UPDATE ppat_submissions SET last_mod_time=? WHERE id=?", "ii", $request_time, $typea);
}



$result['status'] = $status;


$result['test'] = $_POST;