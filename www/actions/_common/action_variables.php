<?php
$debug = "";
$message = "";
$session = "";
$current_time = time();

$response = 0;
$errors = 0;
$error_data = array();
$run_dropzone = 0;

$form_enabled = 0;
$form_exist = 0;
$form_id = $typea;

if (isset($_GET['action'])) {
  $action = $_GET['action'];
} else {
  $action = NULL;
}

if (isset($_POST['csrf_token'])) {
  $csrf_token = $_POST['csrf_token'];
} else {
  $csrf_token = NULL;
}

$session_token_check = 0;

if (isset($_POST['session'])) {

  $session_crypted = $_POST['session'];
  $session = $crypt->decrypt($session_crypted);
  if ($session) {
    $res = $db->sql("SELECT * FROM ppat_sessions WHERE session_id=?", 's', $session);
    while ($row = $res->fetch_assoc()) {
      $session_token_check = 1;
    }
  }
}
