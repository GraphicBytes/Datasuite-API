<?php
if (is_malicious()) {

    log_malicious();
    $result['status'] = 0;
    $result['feedback'] = "INVALID REQUEST!";
} else {

    $status_check = 0;
    $result['newtoken'] = $tokens->get_token();

    $files = null;

    $details = array();
    $otherdetails = array();
    $postimages = array();
    $postvideos = array();
    $postfiles = array();
    $actionIDs = array();
    $actions = array();
    $otherComments = array();

    $readonly = 1;

    $use_action_1 = 0;
    $use_action_2 = 0;
    $use_action_3 = 0;
    $use_action_4 = 0;
    $use_action_5 = 0;
    $use_action_6 = 0;
    $use_action_7 = 0;
    $use_action_8 = 0;
    $use_action_9 = 0;
    $use_action_10 = 0;
    $use_action_11 = 0;
    $use_action_12 = 0;
    $use_action_13 = 0;
    $use_action_14 = 0;
    $use_action_15 = 0;

    $sql = "SELECT * FROM ppat_submissions WHERE id=?";
    $res = $db->sql($sql, "i", $typea);
    while ($row = $res->fetch_assoc()) {

        $submission_id = $row['id'];
        $form_id = $row['form_id'];
        $form_data = unserialize($row['form_data']);

        $form_override_data = $row['override_data'];
        if ($form_override_data !== null) {
            $form_override_data = unserialize($form_override_data);
        }

        $submission_time = $row['submission_time'];
        $user_ip = $row['user_ip'];
        $session_id = $row['session_id'];

        $status = $row['status'];
        $action_1_value = $row['action_1'];
        $action_2_value = $row['action_2'];
        $action_3_value = $row['action_3'];
        $action_4_value = $row['action_4'];
        $action_5_value = $row['action_5'];
        $action_6_value = $row['action_6'];
        $action_7_value = $row['action_7'];
        $action_8_value = $row['action_8'];
        $action_9_value = $row['action_9'];
        $action_10_value = $row['action_10'];
        $action_11_value = $row['action_11'];
        $action_12_value = $row['action_12'];
        $action_13_value = $row['action_13'];
        $action_14_value = $row['action_14'];
        $action_15_value = $row['action_15'];

        $status_check = 1;
    }

    if (!empty($userGroupsReadWrite)) {

        $sql = "SELECT * FROM ppat_forms WHERE user_group IN (" . implode(",", array_map('intval', $userGroupsReadWrite)) . ") AND id='$form_id'";
        $res = $db->sql($sql);
        while ($row = $res->fetch_assoc()) {
            $readonly = 0;
        }
    }

    if ($status_check == 1) {

        $action_0_arg = $action_1_arg = $action_2_arg = $action_3_arg = $action_4_arg = $action_5_arg = $action_6_arg = $action_7_arg = $action_8_arg = $action_9_arg = $action_10_arg = "";
        $using_filter = 0;
        $total_filters = 0;

        if ($typeb == 1) {
            $action_0_arg = " AND status = 0";
            $using_filter = 1;
            $total_filters = $total_filters + 1;
        } else {
            //$action_1_arg = " OR action_1 = 0 OR action_1 = 1";
        }

        if ($typec == 1) {
            $action_1_arg = " AND action_1 = 1";
            $using_filter = 1;
            $total_filters = $total_filters + 1;
        } else {
            //$action_1_arg = " OR action_1 = 0 OR action_1 = 1";
        }
        if ($typed == 1) {
            $action_2_arg = " AND action_2 = 1";
            $using_filter = 1;
            $total_filters = $total_filters + 1;
        } else {
            //$action_2_arg = " OR action_2 = 0 OR action_2 = 1";
        }
        if ($typee == 1) {
            $action_3_arg = " AND action_3 = 1";
            $using_filter = 1;
            $total_filters = $total_filters + 1;
        } else {
            //$action_3_arg = " OR action_3 = 0 OR action_3 = 1";
        }
        if ($typef == 1) {
            $action_4_arg = " AND action_4 = 1";
            $using_filter = 1;
            $total_filters = $total_filters + 1;
        } else {
            //$action_4_arg = " OR action_4 = 0 OR action_4 = 1";
        }
        if ($typeg == 1) {
            $action_5_arg = " AND action_5 = 1";
            $using_filter = 1;
            $total_filters = $total_filters + 1;
        } else {
            //$action_5_arg = " OR action_5 = 0 OR action_5 = 1";
        }
        if ($typeh == 1) {
            $action_6_arg = " AND action_6 = 1";
            $using_filter = 1;
            $total_filters = $total_filters + 1;
        } else {
            //$action_6_arg = " OR action_6 = 0 OR action_6 = 1";
        }
        if ($typei == 1) {
            $action_7_arg = " AND action_7 = 1";
            $using_filter = 1;
            $total_filters = $total_filters + 1;
        } else {
            //$action_7_arg = " OR action_7 = 0 OR action_7 = 1";
        }
        if ($typej == 1) {
            $action_8_arg = " AND action_8 = 1";
            $using_filter = 1;
            $total_filters = $total_filters + 1;
        } else {
            //$action_8_arg = " OR action_8 = 0 OR action_8 = 1";
        }
        if ($typek == 1) {
            $action_9_arg = " AND action_9 = 1";
            $using_filter = 1;
            $total_filters = $total_filters + 1;
        } else {
            //$action_9_arg = " OR action_9 = 0 OR action_9 = 1";
        }
        if ($typel == 1) {
            $action_10_arg = " AND action_10 = 1";
            $using_filter = 1;
            $total_filters = $total_filters + 1;
        } else {
            //$action_10_arg = " OR action_10 = 0 OR action_10 = 1";
        }
        if ($typem == 1) {
            $action_11_arg = " AND action_11 = 1";
            $using_filter = 1;
            $total_filters = $total_filters + 1;
        } else {
            //$action_10_arg = " OR action_10 = 0 OR action_10 = 1";
        }
        if ($typen == 1) {
            $action_12_arg = " AND action_12 = 1";
            $using_filter = 1;
            $total_filters = $total_filters + 1;
        } else {
            //$action_10_arg = " OR action_10 = 0 OR action_10 = 1";
        }
        if ($typeo == 1) {
            $action_13_arg = " AND action_13 = 1";
            $using_filter = 1;
            $total_filters = $total_filters + 1;
        } else {
            //$action_10_arg = " OR action_10 = 0 OR action_10 = 1";
        }
        if ($typep == 1) {
            $action_14_arg = " AND action_14 = 1";
            $using_filter = 1;
            $total_filters = $total_filters + 1;
        } else {
            //$action_10_arg = " OR action_10 = 0 OR action_10 = 1";
        }
        if ($typeq == 1) {
            $action_15_arg = " AND action_15 = 1";
            $using_filter = 1;
            $total_filters = $total_filters + 1;
        } else {
            //$action_10_arg = " OR action_10 = 0 OR action_10 = 1";
        }

        if ($using_filter == 1) {
            $filter_args = $action_0_arg . $action_1_arg . $action_2_arg . $action_3_arg . $action_4_arg . $action_5_arg . $action_6_arg . $action_7_arg . $action_8_arg . $action_9_arg . $action_10_arg . $action_11_arg . $action_12_arg . $action_13_arg . $action_14_arg . $action_15_arg;

            $sql = "SELECT * FROM ppat_submissions WHERE (form_id=? $filter_args) OR id=? ORDER BY submission_time DESC";
            $res = $db->sql($sql, "ii", $form_id, $typea);
        } else {
            $sql = "SELECT * FROM ppat_submissions WHERE form_id=? ORDER BY submission_time DESC";
            $res = $db->sql($sql, "i", $form_id);
        }

        $fullIDArray = array();
        while ($row = $res->fetch_assoc()) {
            $fullIDArray[] = $row['id'];
        }

        $previousEntry = 0;
        $previous_set = 0;

        $next_set = 0;

        $result["go_right_entry"] = $result["go_left_entry"] = 0;

        foreach ($fullIDArray as $value) {

            if ($submission_id == $value && $previous_set == 0) {
                $result["go_right_entry"] = $previousEntry;
                $previous_set = 1;
            }

            if ($submission_id != $value && $previous_set == 1 && $next_set == 0) {
                $result["go_left_entry"] = $value;
                $next_set = 1;
            }

            $previousEntry = $value;
        }

        $sql = "SELECT * FROM ppat_forms WHERE user_group IN (" . implode(",", array_map('intval', $userGroupsAll)) . ") AND id='$form_id' ORDER BY id DESC";
        $res = $db->sql($sql);
        while ($row = $res->fetch_assoc()) {

            $user_group = $row['user_group'];
            $form_name = $row['form_name'];

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
                $use_action_1 = 1;
            }
            if ($act2_id != 0) {
                $actionIDs[] = $act2_id;
                $use_action_2 = 1;
            }
            if ($act3_id != 0) {
                $actionIDs[] = $act3_id;
                $use_action_3 = 1;
            }
            if ($act4_id != 0) {
                $actionIDs[] = $act4_id;
                $use_action_4 = 1;
            }
            if ($act5_id != 0) {
                $actionIDs[] = $act5_id;
                $use_action_5 = 1;
            }
            if ($act6_id != 0) {
                $actionIDs[] = $act6_id;
                $use_action_6 = 1;
            }
            if ($act7_id != 0) {
                $actionIDs[] = $act7_id;
                $use_action_7 = 1;
            }
            if ($act8_id != 0) {
                $actionIDs[] = $act8_id;
                $use_action_8 = 1;
            }
            if ($act9_id != 0) {
                $actionIDs[] = $act9_id;
                $use_action_9 = 1;
            }
            if ($act10_id != 0) {
                $actionIDs[] = $act10_id;
                $use_action_10 = 1;
            }
            if ($act11_id != 0) {
                $actionIDs[] = $act11_id;
                $use_action_11 = 1;
            }
            if ($act12_id != 0) {
                $actionIDs[] = $act12_id;
                $use_action_12 = 1;
            }
            if ($act13_id != 0) {
                $actionIDs[] = $act13_id;
                $use_action_13 = 1;
            }
            if ($act14_id != 0) {
                $actionIDs[] = $act14_id;
                $use_action_14 = 1;
            }
            if ($act15_id != 0) {
                $actionIDs[] = $act15_id;
                $use_action_15 = 1;
            }

            $status_check = 2;
        }
    }

    if ($status_check == 2) {

        $sql2 = "SELECT * FROM ppat_form_fields WHERE form_id='$form_id' ORDER BY field_order ASC";
        $res2 = $db->sql($sql2);
        while ($row2 = $res2->fetch_assoc()) {

            $detail = array();

            $field_id = $row2['id'];
            $key_data = $row2['key_data'];
            $field_type = $row2['field_type'];
            $field_name = $row2['field_name'];
            $field_editable = $row2['editable'];

            $short_label = $row2['short_label'];
            $admin_label = $row2['admin_label'];

            if ($admin_label != null && $admin_label != "") {
                $this_label = $admin_label;
            } else {
                if ($short_label != null && $short_label != "") {
                    $this_label = $short_label;
                } else {
                    $this_label = $row2['label'];
                }
            }

            if (
                $field_type == "text" or $field_type == "TEXT"
                || $field_type == "textarea" or $field_type == "TEXTAREA"
                || $field_type == "email" or $field_type == "EMAIL"
                || $field_type == "select" or $field_type == "SELECT"
                || $field_type == "hidden" or $field_type == "HIDDEN"
            ) {

                $detail['question'] = $this_label;

                if (isset($form_data[$field_name])) {

                    $detail['answer'] = $form_data[$field_name];
                } else {
                    $detail['answer'] = "";
                }

                $detail['test'] = $row2;

                if (isset($form_data[$field_name])) {
                    $answer_detail = $form_data[$field_name];
                } else {
                    $answer_detail = "";
                }

                $detail['type'] = $field_type;
                $detail['field_name'] = $field_name;
                $detail['editable'] = 0;
                $detail['answer'] = $answer_detail;

                if ($field_editable == 1) {
                    $detail['editable'] = 1;
                    if (isset($form_override_data[$field_name])) {
                        $detail['answer'] = $form_override_data[$field_name];
                    }
                }

                if ($key_data == 1) {
                    $details[] = $detail;
                } else {
                    $otherdetails[] = $detail;
                }
            }

            if (
                $field_type == "checkbox" or $field_type == "CHECKBOX"
            ) {

                $detail['question'] = $this_label;

                if (isset($form_data[$field_name])) {
                    $detail['answer'] = $form_data[$field_name];
                } else {
                    $detail['answer'] = "No";
                }

                if ($key_data == 1) {
                    $details[] = $detail;
                } else {
                    $otherdetails[] = $detail;
                }
            }

            if ($field_type == "dropzone") {
                $files = $form_data["dropbox"];
            }

            $status_check = 3;
        }
    }

    if ($status_check == 3) {

        if ($files !== null && !empty($files)) {

            $sql2 = "SELECT * FROM ppat_uploads WHERE id IN (" . implode(",", array_map('intval', $files)) . ") ORDER BY id ASC";
            $res2 = $db->sql($sql2);
            while ($row2 = $res2->fetch_assoc()) {

                $image_id = $row2['id'];

                $access_token = getFileAccessToken($image_id);

                $image_full_file = $row2['full_file'];
                $image_medium_file = $row2['medium_file'];
                $image_thumbnail_file = $row2['thumbnail_file'];
                $image_extension = $row2['extension'];
                $image_original_filename = $row2['original_filename'];
                $image_upload_time = $row2['upload_time'];

                $image_status = $row2['status'];

                $postimage['id'] = $image_id;
                $postimage['url'] = $image_id . "_full." . $image_extension . "?token=" . $access_token;
                $postimage['fileName'] = $image_original_filename;
                $videoType = ['mp4', 'mov', 'wmv', 'avi', 'avchd', 'flv', 'f4v', 'swf', 'mkv', 'webm', 'html5', 'mpeg-2'];

                if (
                    $image_extension == "jpg"
                    || $image_extension == "jpeg"
                    || $image_extension == "png"
                ) {
                    $postimage['thumburl'] = $image_id . "_thumb." . $image_extension . "?token=" . $access_token;
                    $postimage['medium'] = $image_id . "_medium." . $image_extension . "?token=" . $access_token;
                    $postimage['image_status'] = $image_status;
                    $postimages[] = $postimage;
                } elseif (in_array($image_extension, $videoType)) { //add videos in a dedicate variable
                    $postimage['video_status'] = $image_status;
                    $postimage['thumburl'] = $image_id . "_thumb." . $image_extension . "?token=" . $access_token;
                    $postvideos[] = $postimage;
                } else {
                    $postfiles[] = $postimage;
                }
            }
        }

        if (count($actionIDs) > 0 && $readonly == 0) {

            $action_count = 1;
            $action_names = array();
            $action_filter_by_default = array();
            $sql2 = "SELECT * FROM ppat_entry_actions WHERE id IN (" . implode(",", array_map('intval', $actionIDs)) . ") ORDER BY action_number ASC";
            $res2 = $db->sql($sql2);
            while ($row2 = $res2->fetch_assoc()) {

                $action_number = $row2['action_number'];

                $action['action_name'] = $row2['action_name'];
                $action['action_label'] = $row2['action_label'];
                $action['action_number'] = $row2['action_number'];

                if ($action_number == 1) {
                    $action['action_value'] = $action_1_value;
                }
                if ($action_number == 2) {
                    $action['action_value'] = $action_2_value;
                }
                if ($action_number == 3) {
                    $action['action_value'] = $action_3_value;
                }
                if ($action_number == 4) {
                    $action['action_value'] = $action_4_value;
                }
                if ($action_number == 5) {
                    $action['action_value'] = $action_5_value;
                }
                if ($action_number == 6) {
                    $action['action_value'] = $action_6_value;
                }
                if ($action_number == 7) {
                    $action['action_value'] = $action_7_value;
                }
                if ($action_number == 8) {
                    $action['action_value'] = $action_8_value;
                }
                if ($action_number == 9) {
                    $action['action_value'] = $action_9_value;
                }
                if ($action_number == 10) {
                    $action['action_value'] = $action_10_value;
                }
                if ($action_number == 11) {
                    $action['action_value'] = $action_11_value;
                }
                if ($action_number == 12) {
                    $action['action_value'] = $action_12_value;
                }
                if ($action_number == 13) {
                    $action['action_value'] = $action_13_value;
                }
                if ($action_number == 14) {
                    $action['action_value'] = $action_14_value;
                }
                if ($action_number == 15) {
                    $action['action_value'] = $action_15_value;
                }

                $actions[] = $action;

                $action_array_key = "action-" . $action_count;
                $action_names[$action_array_key] = $row2['action_label'];
                $action_filter_by_default[$action_array_key] = $row2['filter_default'];

                $action_count = $action_count + 1;
            }

            $action_filters_triggers = array();
            if ($use_action_1 == 1) {
                $thisAction["number"] = 1;
                $thisAction["name"] = $action_names["action-1"];
                $thisAction['form_id'] = $form_id;
                $thisAction["filter_by_default"] = $action_filter_by_default["action-1"];
                $action_filters_triggers[] = $thisAction;
            }
            if ($use_action_2 == 1) {
                $thisAction["number"] = 2;
                $thisAction["name"] = $action_names["action-2"];
                $thisAction['form_id'] = $form_id;
                $thisAction["filter_by_default"] = $action_filter_by_default["action-2"];
                $action_filters_triggers[] = $thisAction;
            }
            if ($use_action_3 == 1) {
                $thisAction["number"] = 3;
                $thisAction["name"] = $action_names["action-3"];
                $thisAction['form_id'] = $form_id;
                $thisAction["filter_by_default"] = $action_filter_by_default["action-3"];
                $action_filters_triggers[] = $thisAction;
            }
            if ($use_action_4 == 1) {
                $thisAction["number"] = 4;
                $thisAction["name"] = $action_names["action-4"];
                $thisAction['form_id'] = $form_id;
                $thisAction["filter_by_default"] = $action_filter_by_default["action-4"];
                $action_filters_triggers[] = $thisAction;
            }
            if ($use_action_5 == 1) {
                $thisAction["number"] = 5;
                $thisAction["name"] = $action_names["action-5"];
                $thisAction['form_id'] = $form_id;
                $thisAction["filter_by_default"] = $action_filter_by_default["action-5"];
                $action_filters_triggers[] = $thisAction;
            }
            if ($use_action_6 == 1) {
                $thisAction["number"] = 6;
                $thisAction["name"] = $action_names["action-6"];
                $thisAction['form_id'] = $form_id;
                $thisAction["filter_by_default"] = $action_filter_by_default["action-6"];
                $action_filters_triggers[] = $thisAction;
            }
            if ($use_action_7 == 1) {
                $thisAction["number"] = 7;
                $thisAction["name"] = $action_names["action-7"];
                $thisAction['form_id'] = $form_id;
                $thisAction["filter_by_default"] = $action_filter_by_default["action-7"];
                $action_filters_triggers[] = $thisAction;
            }
            if ($use_action_8 == 1) {
                $thisAction["number"] = 8;
                $thisAction["name"] = $action_names["action-8"];
                $thisAction['form_id'] = $form_id;
                $thisAction["filter_by_default"] = $action_filter_by_default["action-8"];
                $action_filters_triggers[] = $thisAction;
            }
            if ($use_action_9 == 1) {
                $thisAction["number"] = 9;
                $thisAction["name"] = $action_names["action-9"];
                $thisAction['form_id'] = $form_id;
                $thisAction["filter_by_default"] = $action_filter_by_default["action-9"];
                $action_filters_triggers[] = $thisAction;
            }
            if ($use_action_10 == 1) {
                $thisAction["number"] = 10;
                $thisAction["name"] = $action_names["action-10"];
                $thisAction['form_id'] = $form_id;
                $thisAction["filter_by_default"] = $action_filter_by_default["action-10"];
                $action_filters_triggers[] = $thisAction;
            }
            if ($use_action_11 == 1) {
                $thisAction["number"] = 11;
                $thisAction["name"] = $action_names["action-11"];
                $thisAction['form_id'] = $form_id;
                $thisAction["filter_by_default"] = $action_filter_by_default["action-11"];
                $action_filters_triggers[] = $thisAction;
            }
            if ($use_action_12 == 1) {
                $thisAction["number"] = 12;
                $thisAction["name"] = $action_names["action-12"];
                $thisAction['form_id'] = $form_id;
                $thisAction["filter_by_default"] = $action_filter_by_default["action-12"];
                $action_filters_triggers[] = $thisAction;
            }
            if ($use_action_13 == 1) {
                $thisAction["number"] = 13;
                $thisAction["name"] = $action_names["action-13"];
                $thisAction['form_id'] = $form_id;
                $thisAction["filter_by_default"] = $action_filter_by_default["action-13"];
                $action_filters_triggers[] = $thisAction;
            }
            if ($use_action_14 == 1) {
                $thisAction["number"] = 14;
                $thisAction["name"] = $action_names["action-14"];
                $thisAction['form_id'] = $form_id;
                $thisAction["filter_by_default"] = $action_filter_by_default["action-14"];
                $action_filters_triggers[] = $thisAction;
            }
            if ($use_action_15 == 1) {
                $thisAction["number"] = 15;
                $thisAction["name"] = $action_names["action-15"];
                $thisAction['form_id'] = $form_id;
                $thisAction["filter_by_default"] = $action_filter_by_default["action-15"];
                $action_filters_triggers[] = $thisAction;
            }

            $result['action_filters'] = $action_filters_triggers;

        }

        $comments_found = 0;
        $user_comments = "";
        $sql = "SELECT * FROM ppat_entry_comments WHERE user_id='$logged_in_id' AND entry_id=?";
        $res = $db->sql($sql, "i", $typea);
        while ($row = $res->fetch_assoc()) {
            $user_comments = $crypt->decrypt($row['content']);
            $comments_found = 1;
        }

        $sql = "SELECT * FROM ppat_entry_comments WHERE user_id!='$logged_in_id' AND entry_id=?";
        $res = $db->sql($sql, "i", $typea);
        while ($row = $res->fetch_assoc()) {

            $this_user_id = $row['user_id'];
            $display_name = "";
            $last_update = $row['last_update'];
            $dt = new DateTime("@$last_update");
            $last_update = $dt->format('g:ia \o\n l jS F Y');

            $res2 = $db->sql("SELECT id, display_name FROM ppat_users WHERE id=?", "i", $this_user_id);
            while ($row2 = $res2->fetch_assoc()) {
                $display_name = $row2['display_name'];
            }

            $comments_found = 1;
            $thisComment['display_name'] = $display_name;
            $thisComment['last_update'] = $last_update;
            $thisComment['content'] = $crypt->decrypt($row['content']);

            if ($thisComment['content'] !== null && $thisComment['content'] != "") {
                $otherComments[] = $thisComment;
            }

        }
    }

    if ($status_check == 3) {

        $result['status'] = 1;

        $result['submission_id'] = $submission_id;
        $result['form_id'] = $form_id;
        //$result['form_data'] = $form_data;
        $result['submission_time'] = $submission_time;
        $result['user_ip'] = $user_ip;
        $result['form_name'] = $form_name;
        $result['details'] = $details;
        $result['other_details'] = $otherdetails;
        $result['postimages'] = $postimages;
        $result['postvideos'] = $postvideos;
        $result['postfiles'] = $postfiles;
        $result['actions'] = $actions;
        $result['comments'] = $user_comments;
        $result['other_comments'] = $otherComments;
        $result['valid'] = 1;
        $result['readonly'] = $readonly;
    } else {
        $result['valid'] = 0;
        $result['status'] = 0;
    }
}
