<?php
if (is_malicious()) {

  log_malicious();
  $result['status'] = 0;
  $result['feedback'] = "INVALID REQUEST!";
} else {



  $status = 0;
  $result['newtoken'] = $tokens->get_token();
  $result['userGroupsReadWrite'] = $userGroupsReadWrite;
  $result['userGroupsReadOnly'] = $userGroupsReadOnly;



  $sql = "SELECT * FROM ppat_forms WHERE user_group IN (" . implode(",", array_map('intval', $userGroupsAll)) . ")";
  $res = $db->sql($sql);
  while ($row = $res->fetch_assoc()) {

    $form_id = $row['id'];
    $form_status = $row['status'];
    $form_name = $row['form_name'];
    $form_group = $row['form_group'];

    $result['forms']["form_" . $form_id]['id'] = $form_id;
    $result['forms']["form_" . $form_id]['status'] = $form_status;
    $result['forms']["form_" . $form_id]['form_name'] = $form_name;
    $result['forms']["form_" . $form_id]['form_group'] = $form_group;
  }



  $result['status'] = $status;
}
