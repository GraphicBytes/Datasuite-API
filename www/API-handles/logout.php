<?php

$token_check = 0;
if (isset($_POST['CSRFtoken'])) {
  $csrf = $_POST['CSRFtoken'];
  $token_check = $tokens->check_token($csrf);
}

$result['tokenData'] = $_POST;

if (is_malicious() or $token_check == 0) {

  log_malicious();
  $result['status'] = 0;
  $result['feedback'] = "INVALID REQUEST!";
} else {

  if ($csrf !== null && $csrf !== "'null'") {

    $csrf_length = strlen($csrf);

    if ($token_length > 10 && $csrf_length > 10) {

      $csrfCheck = $tokens->check_token($csrf);

      if ($csrfCheck === 1) {

        if ($typea != "just-here") {
          $new_cookie_id = random_str(256);
          $db->sql("UPDATE ppat_users SET cookie_id=?, cookie_age=?, login_fail='0', cookie_fail='0' WHERE id=?", 'sii', $new_cookie_id, $request_time, $logged_in_id);
        }        

        $db->sql("DELETE FROM file_access_tokens WHERE user_id='$logged_in_id'");

        $result['status'] = 1;
        $result['feedback'] = "SUCCESSFULLY LOGGED OUT";
      } else {
        log_malicious();
        $result['status'] = 0;
      }
    } else {
      log_malicious();
      $result['status'] = 0;
    }
  } else {
    log_malicious();
    $result['status'] = 0;
  }
}
