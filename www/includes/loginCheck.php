<?php
//############################################
//############### LOG IN CHECK ###############
//############################################

$logged_in_id = 0;
$logged_in_cookie_id = "empty";
$valid_token = 0;


if (is_malicious()) {

  log_malicious();
  $result['status'] = 0;
  $result['feedback'] = "INVALID REQUEST!";
} else {

  if (isset($_POST['Token'])) {
    $UserToken = $_POST['Token'];
    $token_length = strlen($UserToken);

    if ($token_length > 50) {

      $tokenData = json_decode($crypt->decrypt($UserToken));

      if (isset($tokenData->user_id)) {
        $logged_in_id = $tokenData->user_id;
      }
      if (isset($tokenData->cookie_id)) {
        $logged_in_cookie_id = $tokenData->cookie_id;
      }
    }
  }

  $res = $db->sql("SELECT * FROM ppat_users WHERE id=? AND cookie_fail < 10 AND login_fail < 10 ORDER BY id ASC LIMIT 1", 'i', $logged_in_id);
  while ($row = $res->fetch_assoc()) {

    $cookie_id = $row['cookie_id'];
    $user_display_name = $row['display_name'];

    if ($logged_in_cookie_id == $cookie_id) {
      $valid_token = 1;
    }
  }

  $res = $db->sql("SELECT * FROM ppat_users WHERE id=? AND cookie_fail < 10 AND login_fail < 10 ORDER BY id ASC LIMIT 1", 'i', $logged_in_id);
  while ($row = $res->fetch_assoc()) {

    $cookie_id = $row['cookie_id'];
    $user_display_name = $row['display_name'];

    if ($logged_in_cookie_id == $cookie_id) {
      $valid_token = 1;
    }
  }

  if ($valid_token == 1) {

    $userGroupsReadWrite = array();
    $userGroupsReadOnly = array();
    $userGroupsAll = array();

    $res = $db->sql("SELECT * FROM ppat_usergroup_links WHERE user_id=? ORDER BY id ASC", 'i', $logged_in_id);
    while ($row = $res->fetch_assoc()) {

      $group_privileges = $row['group_privileges'];

      if ($group_privileges == 1) {
        $userGroupsReadWrite[] = $row['group_id'];
        $userGroupsAll[] = $row['group_id'];
      } else if ($group_privileges == 0) {
        $userGroupsReadOnly[] = $row['group_id'];
        $userGroupsAll[] = $row['group_id'];
      }
    }
  }


  if ($valid_token == 0) {
    $logged_in_id = 0;
  }
}
