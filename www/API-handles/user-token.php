<?php
if (is_malicious()) {

  log_malicious();
  $result['status'] = 0;
} else {

  $result['status'] = 1;
  $result['token'] = $tokens->get_token();
}
