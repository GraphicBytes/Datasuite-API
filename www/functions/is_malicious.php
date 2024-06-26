<?php

function is_malicious(){

  global $is_user_a_bot;
  global $db;
  global $user_ip;

  $malicious = FALSE;

  // is this a recognised bot?
  if ($is_user_a_bot == 1) {
    $malicious = TRUE;
  } else {
    // check recent nasty IPs
    $attempts_from_this_ip=0;
    $res=$db->sql( "SELECT * FROM ppat_malicious_ips WHERE ip_address=? ORDER BY id DESC LIMIT 1", 's' , $user_ip );
    while($row=$res->fetch_assoc()) {
      $attempts_from_this_ip = $row['attempts'];
    }
    if ($attempts_from_this_ip > 25) {
      $malicious = TRUE;
    }
  }


return $malicious;

}
