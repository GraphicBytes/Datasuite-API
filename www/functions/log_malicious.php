<?php

function log_malicious()
{

  global $db;
  global $user_ip;
  global $request_time;
  global $known_bot;
  global $user_agent;


  if ($known_bot == 0) {
    $to_md5 = $user_agent . $user_ip;
    $user_agent_MD5 = md5($to_md5);

    $db->sql("INSERT INTO ppat_malicious_useragents SET agent_md5=?, user_agent=?, agent_ip=?, attempts=1, last_attempt=? ON DUPLICATE KEY UPDATE attempts=attempts+1, last_attempt=?", 'sssii', $user_agent_MD5, $user_agent, $user_ip, $request_time, $request_time);

    $db->sql("INSERT INTO ppat_malicious_ips SET ip_address=?, last_attempt=? ON DUPLICATE KEY UPDATE attempts=attempts+1, last_attempt=?", 'sii', $user_ip, $request_time, $request_time);
  }


  return true;
}
