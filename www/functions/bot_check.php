<?php

function bot_check()
{

  //check known user agents
  global $db;
  global $user_ip;
  global $known_bot;

  if ($known_bot == 1) {
    $bot = 1;
  } else {
    $bot = 0;
  }


  // check malicious_useragents
  $attempts_from_this_ip = 0;
  $res = $db->sql("SELECT * FROM ppat_malicious_useragents WHERE agent_ip=?", 's', $user_ip);
  while ($row = $res->fetch_assoc()) {
    $attempts_from_this_ip = $attempts_from_this_ip + $row['attempts'];
  }
  if ($attempts_from_this_ip > 25) {
    $bot = 1;
  }


  return $bot;
}
