<?php
if (is_malicious()) {

  log_malicious();
  $result['status'] = 0;
  $result['feedback'] = "INVALID REQUEST!";
} else {

  $status = 0;
  $allowed = 0;
  $result['newtoken'] = $tokens->get_token();
  // $result['userGroupsReadWrite'] = $userGroupsReadWrite;
  // $result['userGroupsReadOnly'] = $userGroupsReadOnly;

  $data_headers = array();
  $export_fields = array();
  $data_entries = array();
  $data_entry_keys = array();
  $col_count = 1;
  $form_name = "";

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

  if (!empty($userGroupsAll)) {
    $subGroup = [];
    $sqlSubGroup = "SELECT sub_group_ids FROM ppat_sub_usergroup_links WHERE user_id='$tokenData->user_id'";
    $resSubGroup = $db->sql($sqlSubGroup);
   
    while ($row = $resSubGroup->fetch_assoc()){ 
      foreach(unserialize( $row["sub_group_ids"] ) as $single){
        $sqlSubGroupName = "SELECT sub_group_name FROM ppat_sub_usergroups WHERE id='$single'";
        $resSubGroupName = $db->sql($sqlSubGroupName);  
        $subGroup[] = $resSubGroupName->fetch_assoc()["sub_group_name"]; 
      }
    }
    

    $sql = "SELECT * FROM ppat_forms WHERE user_group IN (" . implode(",", array_map('intval', $userGroupsAll)) . ") AND id='$typea'";
    $res = $db->sql($sql);
    while ($row = $res->fetch_assoc()) {

      $form_id = $row['id'];
      $form_status = $row['status'];
      $form_name = $row['form_name'];
      $form_group = $row['form_group'];

      $qr_gen = $row['qr_gen'];

      $action_ids = array();
      if ($row['act1_id'] != 0) {
        $action_ids[] = $row['act1_id'];
        $use_action_1 = 1;
      }
      if ($row['act2_id'] != 0) {
        $action_ids[] = $row['act2_id'];
        $use_action_2 = 1;
      }
      if ($row['act3_id'] != 0) {
        $action_ids[] = $row['act3_id'];
        $use_action_3 = 1;
      }
      if ($row['act4_id'] != 0) {
        $action_ids[] = $row['act4_id'];
        $use_action_4 = 1;
      }
      if ($row['act5_id'] != 0) {
        $action_ids[] = $row['act5_id'];
        $use_action_5 = 1;
      }
      if ($row['act6_id'] != 0) {
        $action_ids[] = $row['act6_id'];
        $use_action_6 = 1;
      }
      if ($row['act7_id'] != 0) {
        $action_ids[] = $row['act7_id'];
        $use_action_7 = 1;
      }
      if ($row['act8_id'] != 0) {
        $action_ids[] = $row['act8_id'];
        $use_action_8 = 1;
      }
      if ($row['act9_id'] != 0) {
        $action_ids[] = $row['act9_id'];
        $use_action_9 = 1;
      }
      if ($row['act10_id'] != 0) {
        $action_ids[] = $row['act10_id'];
        $use_action_10 = 1;
      }
      if ($row['act11_id'] != 0) {
        $action_ids[] = $row['act11_id'];
        $use_action_11 = 1;
      }
      if ($row['act12_id'] != 0) {
        $action_ids[] = $row['act12_id'];
        $use_action_12 = 1;
      }
      if ($row['act13_id'] != 0) {
        $action_ids[] = $row['act13_id'];
        $use_action_13 = 1;
      }
      if ($row['act14_id'] != 0) {
        $action_ids[] = $row['act14_id'];
        $use_action_14 = 1;
      }
      if ($row['act15_id'] != 0) {
        $action_ids[] = $row['act15_id'];
        $use_action_15 = 1;
      }
      $allowed = 1;



      //add id to headers
      $accessor = "col" . $col_count;
      $data_header['Header'] = "ID";
      $data_header['accessor'] = $accessor;
      $data_headers[] = $data_header;
      $col_count = $col_count + 1;

      $sql2 = "SELECT * FROM ppat_form_fields WHERE form_id='$form_id' AND key_data = 1 ORDER BY field_order ASC";
      $res2 = $db->sql($sql2);
      while ($row2 = $res2->fetch_assoc()) {

        $field_type = $row2['field_type'];
        $short_label = $row2['short_label'];

        if (
          $field_type == "text" or $field_type == "TEXT"
          || $field_type == "textarea" or $field_type == "TEXTAREA"
          || $field_type == "email" or $field_type == "EMAIL"
          || $field_type == "select" or $field_type == "SELECT"
          || $field_type == "hidden" or $field_type == "HIDDEN"
        ) {

          $data_entry_keys[] = $row2['field_name'];

          $accessor = "col" . $col_count;

          if ($short_label !== null && $short_label !== "") {
            $data_header['Header'] = $short_label;
          } else {
            $data_header['Header'] = $row2['label'];
          }

          $data_header['accessor'] = $accessor;

          $data_headers[] = $data_header;

          $col_count = $col_count + 1;
        }
      }



      $sql2 = "SELECT * FROM ppat_form_fields WHERE form_id='$form_id' ORDER BY field_order ASC";
      $res2 = $db->sql($sql2);
      while ($row2 = $res2->fetch_assoc()) {

        if (
          $row2['field_type'] != "dropzone"
          && $row2['field_type'] != "submit_success_message"
          && $row2['field_type'] != "html"
        ) {
          $export_field = array();
          $export_field['label'] = $row2['short_label'];
          $export_field['field_name'] = $row2['field_name'];
          $export_fields[] = $export_field;
        }
      }


      $action_count = 1;
      $action_names = array();
      $action_filter_by_default = array();
      $sql3 = "SELECT * FROM ppat_entry_actions WHERE id IN (" . implode(",", array_map('intval', $action_ids)) . ") ORDER BY action_number ASC";
      $res3 = $db->sql($sql3);
      while ($row3 = $res3->fetch_assoc()) {

        $action_array_key = "action-" . $action_count;
        $action_names[$action_array_key] = $row3['action_label'];
        $action_filter_by_default[$action_array_key] = $row3['filter_default'];

        $action_count = $action_count + 1;

        $export_field = array();
        $export_field['label'] = $row3['action_label'];
        $export_field['field_name'] = "dsdata___" . $row3['action_number'];
        $export_fields[] = $export_field;
      }


      if ($qr_gen == 1) {
        $export_field = array();
        $export_field['label'] = "QR Code File URL";
        $export_field['field_name'] = "dsdata___qr_code_file_url";
        $export_fields[] = $export_field;
      }


      $export_field = array();
      $export_field['label'] = "Submission Time";
      $export_field['field_name'] = "dsdata___system_submission_time";
      $export_fields[] = $export_field;




      if ($action_count > 0) {
        $accessor = "col" . $col_count;
        $data_header['Header'] = "Status";
        $data_header['accessor'] = $accessor;
        $data_headers[] = $data_header;
        $col_count = $col_count + 1;
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












      $entry_count = 1;

      $action_0_arg = $action_1_arg = $action_2_arg = $action_3_arg = $action_4_arg = $action_5_arg = $action_6_arg = $action_7_arg = $action_8_arg = $action_9_arg = $action_10_arg = $action_11_arg = $action_12_arg = $action_13_arg = $action_14_arg = $action_15_arg =  "";
      $using_filter = 0;
      $total_filters = 0;

      if ($typeb == 1) {
        $action_0_arg = " status = 0 ";
        $using_filter = 1;
        $total_filters = $total_filters + 1;
      } else {
        $action_0_arg = " (status = 0 OR status = 1)";
      }

      if ($typec == 1) {

        if ($using_filter == 1) {
          $AndOr = "OR";
        } else {
          $AndOr = "AND";
        }

        $action_1_arg = " $AndOr action_1 = 1";
        $using_filter = 1;
        $total_filters = $total_filters + 1;
      } else {
        //$action_1_arg = " AND (action_1 = 0 OR action_1 = 1)";
      }
      if ($typed == 1) {

        if ($using_filter == 1) {
          $AndOr = "OR";
        } else {
          $AndOr = "AND";
        }

        $action_2_arg = " $AndOr action_2 = 1";
        $using_filter = 1;
        $total_filters = $total_filters + 1;
      } else {
        //$action_2_arg = " AND (action_2 = 0 OR action_2 = 1)";
      }
      if ($typee == 1) {

        if ($using_filter == 1) {
          $AndOr = "OR";
        } else {
          $AndOr = "AND";
        }

        $action_3_arg = " $AndOr action_3 = 1";
        $using_filter = 1;
        $total_filters = $total_filters + 1;
      } else {
        //$action_3_arg = " AND (action_3 = 0 OR action_3 = 1)";
      }
      if ($typef == 1) {

        if ($using_filter == 1) {
          $AndOr = "OR";
        } else {
          $AndOr = "AND";
        }

        $action_4_arg = " $AndOr action_4 = 1";
        $using_filter = 1;
        $total_filters = $total_filters + 1;
      } else {
        //$action_4_arg = " OR action_4 = 0 OR action_4 = 1";
      }
      if ($typeg == 1) {

        if ($using_filter == 1) {
          $AndOr = "OR";
        } else {
          $AndOr = "AND";
        }

        $action_5_arg = " $AndOr action_5 = 1";
        $using_filter = 1;
        $total_filters = $total_filters + 1;
      } else {
        //$action_5_arg = " OR action_5 = 0 OR action_5 = 1";
      }
      if ($typeh == 1) {

        if ($using_filter == 1) {
          $AndOr = "OR";
        } else {
          $AndOr = "AND";
        }

        $action_6_arg = " $AndOr action_6 = 1";
        $using_filter = 1;
        $total_filters = $total_filters + 1;
      } else {
        //$action_6_arg = " OR action_6 = 0 OR action_6 = 1";
      }
      if ($typei == 1) {

        if ($using_filter == 1) {
          $AndOr = "OR";
        } else {
          $AndOr = "AND";
        }

        $action_7_arg = " $AndOr action_7 = 1";
        $using_filter = 1;
        $total_filters = $total_filters + 1;
      } else {
        //$action_7_arg = " OR action_7 = 0 OR action_7 = 1";
      }
      if ($typej == 1) {

        if ($using_filter == 1) {
          $AndOr = "OR";
        } else {
          $AndOr = "AND";
        }

        $action_8_arg = " $AndOr action_8 = 1";
        $using_filter = 1;
        $total_filters = $total_filters + 1;
      } else {
        //$action_8_arg = " OR action_8 = 0 OR action_8 = 1";
      }
      if ($typek == 1) {

        if ($using_filter == 1) {
          $AndOr = "OR";
        } else {
          $AndOr = "AND";
        }

        $action_9_arg = " $AndOr action_9 = 1";
        $using_filter = 1;
        $total_filters = $total_filters + 1;
      } else {
        //$action_9_arg = " OR action_9 = 0 OR action_9 = 1";
      }
      if ($typel == 1) {

        if ($using_filter == 1) {
          $AndOr = "OR";
        } else {
          $AndOr = "AND";
        }

        $action_10_arg = " $AndOr action_10 = 1";
        $using_filter = 1;
        $total_filters = $total_filters + 1;
      } else {
        //$action_10_arg = " OR action_10 = 0 OR action_10 = 1";
      }
      if ($typem == 1) {

        if ($using_filter == 1) {
          $AndOr = "OR";
        } else {
          $AndOr = "AND";
        }

        $action_11_arg = " $AndOr action_11 = 1";
        $using_filter = 1;
        $total_filters = $total_filters + 1;
      } else {
        //$action_11_arg = " OR action_11 = 0 OR action_11 = 1";
      }
      if ($typen == 1) {

        if ($using_filter == 1) {
          $AndOr = "OR";
        } else {
          $AndOr = "AND";
        }

        $action_12_arg = " $AndOr action_12 = 1";
        $using_filter = 1;
        $total_filters = $total_filters + 1;
      } else {
        //$action_12_arg = " OR action_12 = 0 OR action_12 = 1";
      }
      if ($typeo == 1) {

        if ($using_filter == 1) {
          $AndOr = "OR";
        } else {
          $AndOr = "AND";
        }

        $action_13_arg = " $AndOr action_13 = 1";
        $using_filter = 1;
        $total_filters = $total_filters + 1;
      } else {
        //$action_13_arg = " OR action_13 = 0 OR action_13 = 1";
      }
      if ($typep == 1) {

        if ($using_filter == 1) {
          $AndOr = "OR";
        } else {
          $AndOr = "AND";
        }

        $action_14_arg = " $AndOr action_14 = 1";
        $using_filter = 1;
        $total_filters = $total_filters + 1;
      } else {
        //$action_14_arg = " OR action_14 = 0 OR action_14 = 1";
      }
      if ($typeq == 1) {

        if ($using_filter == 1) {
          $AndOr = "OR";
        } else {
          $AndOr = "AND";
        }

        $action_15_arg = " $AndOr action_15 = 1";
        $using_filter = 1;
        $total_filters = $total_filters + 1;
      } else {
        //$action_15_arg = " OR action_15 = 0 OR action_15 = 1";
      }




      if ($using_filter == 1) {
        $filter_args = $action_0_arg . $action_1_arg . $action_2_arg . $action_3_arg . $action_4_arg . $action_5_arg . $action_6_arg . $action_7_arg . $action_8_arg . $action_9_arg . $action_10_arg . $action_11_arg . $action_12_arg . $action_13_arg . $action_14_arg . $action_15_arg;

        // if ($total_filters > 1) {
        //$filter_args = preg_replace('/AND /', '', $filter_args, 1);
        // }

        $sql3 = "SELECT * FROM ppat_submissions WHERE form_id='$form_id' AND ( $filter_args ) ORDER BY submission_time DESC";
      } else {
        $sql3 = "SELECT * FROM ppat_submissions WHERE form_id='$form_id' ORDER BY submission_time DESC";
      }

      $res3 = $db->sql($sql3);
      while ($row3 = $res3->fetch_assoc()) {
 

        $entry_id = $row3['id'];
        $entry_data = $row3['form_data'];

        // $entry_data = preg_replace_callback(
        //   '!s:(\d+):"(.*?)";!',
        //   function ($match) {
        //     return ($match[1] == strlen($match[2])) ? $match[0] : 's:' . strlen($match[2]) . ':"' . $match[2] . '";';
        //   },
        //   $entry_data
        // );

        if (isSerialized($entry_data) !== false) { 
          $entry_data = unserialize($entry_data);
        } else {
          $entry_data = array();
        }




        $form_override_data = $row3['override_data'];
        if ($form_override_data !== NULL) {
          $form_override_data = unserialize($form_override_data);
        }

        $action_value_1 = $row3['action_1'];
        $action_value_2 = $row3['action_2'];
        $action_value_3 = $row3['action_3'];
        $action_value_4 = $row3['action_4'];
        $action_value_5 = $row3['action_5'];
        $action_value_6 = $row3['action_6'];
        $action_value_7 = $row3['action_7'];
        $action_value_8 = $row3['action_8'];
        $action_value_9 = $row3['action_9'];
        $action_value_10 = $row3['action_10'];
        $action_value_11 = $row3['action_11'];
        $action_value_12 = $row3['action_12'];
        $action_value_13 = $row3['action_13'];
        $action_value_14 = $row3['action_14'];
        $action_value_15 = $row3['action_15'];

        if ($action_value_1 == 1) {
          if (isset($action_names["action-1"])) {
            $action_value_1 = $action_names["action-1"];
          } else {
            $action_value_1 = 0;
          }
        } else {
          $action_value_1 = 0;
        }

        if ($action_value_2 == 1) {
          if (isset($action_names["action-2"])) {
            $action_value_2 = $action_names["action-2"];
          } else {
            $action_value_2 = 0;
          }
        } else {
          $action_value_2 = 0;
        }

        if ($action_value_3 == 1) {
          if (isset($action_names["action-3"])) {
            $action_value_3 = $action_names["action-3"];
          } else {
            $action_value_3 = 0;
          }
        } else {
          $action_value_3 = 0;
        }

        if ($action_value_4 == 1) {
          if (isset($action_names["action-4"])) {
            $action_value_4 = $action_names["action-4"];
          } else {
            $action_value_4 = 0;
          }
        } else {
          $action_value_4 = 0;
        }

        if ($action_value_5 == 1) {
          if (isset($action_names["action-5"])) {
            $action_value_5 = $action_names["action-5"];
          } else {
            $action_value_5 = 0;
          }
        } else {
          $action_value_5 = 0;
        }

        if ($action_value_6 == 1) {
          if (isset($action_names["action-6"])) {
            $action_value_6 = $action_names["action-6"];
          } else {
            $action_value_6 = 0;
          }
        } else {
          $action_value_6 = 0;
        }

        if ($action_value_7 == 1) {
          if (isset($action_names["action-7"])) {
            $action_value_7 = $action_names["action-7"];
          } else {
            $action_value_7 = 0;
          }
        } else {
          $action_value_7 = 0;
        }

        if ($action_value_8 == 1) {
          if (isset($action_names["action-8"])) {
            $action_value_8 = $action_names["action-8"];
          } else {
            $action_value_8 = 0;
          }
        } else {
          $action_value_8 = 0;
        }

        if ($action_value_9 == 1) {
          if (isset($action_names["action-9"])) {
            $action_value_9 = $action_names["action-9"];
          } else {
            $action_value_9 = 0;
          }
        } else {
          $action_value_9 = 0;
        }

        if ($action_value_10 == 1) {
          if (isset($action_names["action-10"])) {
            $action_value_10 = $action_names["action-10"];
          } else {
            $action_value_10 = 0;
          }
        } else {
          $action_value_10 = 0;
        }

        if ($action_value_11 == 1) {
          if (isset($action_names["action-11"])) {
            $action_value_11 = $action_names["action-11"];
          } else {
            $action_value_11 = 0;
          }
        } else {
          $action_value_11 = 0;
        }

        if ($action_value_12 == 1) {
          if (isset($action_names["action-12"])) {
            $action_value_12 = $action_names["action-12"];
          } else {
            $action_value_12 = 0;
          }
        } else {
          $action_value_12 = 0;
        }

        if ($action_value_13 == 1) {
          if (isset($action_names["action-13"])) {
            $action_value_13 = $action_names["action-13"];
          } else {
            $action_value_13 = 0;
          }
        } else {
          $action_value_13 = 0;
        }

        if ($action_value_14 == 1) {
          if (isset($action_names["action-14"])) {
            $action_value_14 = $action_names["action-14"];
          } else {
            $action_value_14 = 0;
          }
        } else {
          $action_value_14 = 0;
        }

        if ($action_value_15 == 1) {
          if (isset($action_names["action-15"])) {
            $action_value_15 = $action_names["action-15"];
          } else {
            $action_value_15 = 0;
          }
        } else {
          $action_value_15 = 0;
        }

        // add entry ID to data
        $col_count = 1;
        $accessor = "col" . $col_count;
        $data_entry[$accessor] = $entry_id;
        $col_count = $col_count + 1;
 

        foreach ($data_entry_keys as $value) {
          $accessor = "col" . $col_count;

          if (isset($entry_data[$value])) { 
            if (isset($form_override_data[$value])) {
              $data_entry[$accessor] = $form_override_data[$value];
            } else {
              $data_entry[$accessor] = $entry_data[$value];
            }

          }

          $col_count = $col_count + 1;
        }



        

        $accessor = "col" . $col_count;
        $data_entry[$accessor] = "";




        if ($use_action_1 == 1 && $action_value_1 != 0) {
          $data_entry[$accessor] = $data_entry[$accessor] . $action_value_1 . "\n";
        }
        if ($use_action_2 == 1 && $action_value_2 != 0) {
          $data_entry[$accessor] = $data_entry[$accessor] . $action_value_2 . "\n";
        }
        if ($use_action_3 == 1 && $action_value_3 != 0) {
          $data_entry[$accessor] = $data_entry[$accessor] . $action_value_3 . "\n";
        }
        if ($use_action_4 == 1 && $action_value_4 != 0) {
          $data_entry[$accessor] = $data_entry[$accessor] . $action_value_4 . "\n";
        }
        if ($use_action_5 == 1 && $action_value_5 != 0) {
          $data_entry[$accessor] = $data_entry[$accessor] . $action_value_5 . "\n";
        }
        if ($use_action_6 == 1 && $action_value_6 != 0) {
          $data_entry[$accessor] = $data_entry[$accessor] . $action_value_6 . "\n";
        }
        if ($use_action_7 == 1 && $action_value_7 != 0) {
          $data_entry[$accessor] = $data_entry[$accessor] . $action_value_7 . "\n";
        }
        if ($use_action_8 == 1 && $action_value_8 != 0) {
          $data_entry[$accessor] = $data_entry[$accessor] . $action_value_8 . "\n";
        }
        if ($use_action_9 == 1 && $action_value_9 != 0) {
          $data_entry[$accessor] = $data_entry[$accessor] . $action_value_9 . "\n";
        }
        if ($use_action_10 == 1 && $action_value_10 != 0) {
          $data_entry[$accessor] = $data_entry[$accessor] . $action_value_10 . "\n";
        }
        if ($use_action_11 == 1 && $action_value_11 != 0) {
          $data_entry[$accessor] = $data_entry[$accessor] . $action_value_11 . "\n";
        }
        if ($use_action_12 == 1 && $action_value_12 != 0) {
          $data_entry[$accessor] = $data_entry[$accessor] . $action_value_12 . "\n";
        }
        if ($use_action_13 == 1 && $action_value_13 != 0) {
          $data_entry[$accessor] = $data_entry[$accessor] . $action_value_13 . "\n";
        }
        if ($use_action_14 == 1 && $action_value_14 != 0) {
          $data_entry[$accessor] = $data_entry[$accessor] . $action_value_14 . "\n";
        }
        if ($use_action_15 == 1 && $action_value_15 != 0) {
          $data_entry[$accessor] = $data_entry[$accessor] . $action_value_15 . "\n";
        }



        $data_entries[] = $data_entry;

        $entry_count = $entry_count + 1;

        $entry_data = null;
        $form_override_data = null;

      }


      $status = 1;
    }
  }

 
  //Filter data by sub goup
 
if( !empty($subGroup) ){
  $new_data_entrie = [];
    foreach($data_entries as $key => $value){
      $subId = $value['col1'];
      $sqlToFilter = "SELECT form_data FROM ppat_submissions WHERE id='$subId'";
      $resToFilter = $db->sql($sqlToFilter);
      $subList = $resToFilter->fetch_assoc(); 
      $data = unserialize( $subList['form_data']);
      
      if( array_key_exists("categories",$data)){
        $categories = explode(',', $data['categories'] ); 
        $resultCompare = array_intersect($categories, $subGroup); 
        if(!empty($resultCompare) ){ 
          $new_data_entrie[] = $data_entries[$key];
        } 
      }
   
    }
    $data_entries = $new_data_entrie; 
}
 

  $result['form_id'] = $form_id;
  $result['form_name'] = $form_name;
  $result['entry_data'] = $data_entries;
  $result['data_headers'] = $data_headers;
  $result['status'] = $status;
  $result['valid'] = $allowed;
  $result['total_entries'] = $entry_count;
  $result['export_fields'] = $export_fields;
}
