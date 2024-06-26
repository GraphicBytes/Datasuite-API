<?php
//###########################################
//############### ENVIRONMENT ###############
//###########################################
include_once('environment.php');
$use_delay = 0; // Handy to get a good view of loading animations on api requsts.
$current_time = $request_time = time();
//sleep(2);

//###########################################
//############### SERVER VARS ###############
//###########################################
$server_name = $_SERVER['SERVER_NAME'];

if (isset($_SERVER['HTTP_X_REAL_IP'])) {
  $user_ip = $userIP = $_SERVER['HTTP_X_REAL_IP'];
} else {
  $user_ip = $userIP = $_SERVER['REMOTE_ADDR'];
}

$user_agent = "";
if (isset($_SERVER['HTTP_USER_AGENT'])) {
  $user_agent = $_SERVER['HTTP_USER_AGENT'];
}

//############################################
//############### SITE OPTIONS ###############
//############################################
include_once('config/site_options.php');
include_once('config/known_bots.php');
$tempFolder = 'temp/';
$uploadsFolder = 'uploads/';
$QRCache = 'uploads/QR_Cache/';
$ExportCache = 'uploads/export_cache/';

//#################################################
//############### PRIMARY FUNCTIONS ###############
//#################################################
include_once('functions/db.php');
include_once('functions/encryption.php');
include_once('functions/hashing.php');
include_once('functions/csrf.php');
include_once('functions/check_request.php');
include_once('functions/random_str.php');
include_once('functions/resize-class.php');
include_once('functions/tokens.php');
include_once('functions/verifyEmail.php');
include_once('functions/siteOptions.php');
include_once('functions/clean_ob_for_js.php');
include_once('functions/is_malicious.php');
include_once('functions/log_malicious.php');
include_once('functions/bot_check.php');
include_once('functions/sendEmail.php');
include_once('functions/getFileAccessToken.php');
include_once('functions/phpqrcode/qrlib.php');
include_once('functions/get_mime_type.php');
include_once('functions/isSerialized.php');

//###################################################
//############### DATABASE CONNECTION ###############
//###################################################
$db = new db();

//#######################################################
//############### 3RD PARTY API FUNCTIONS ###############
//#######################################################
include_once('functions/adestra/Autoloader.php');
include_once('functions/dotdigital/updateDotDigital.php');

//#####################################################
//############### GET AND CHECK REQUEST ###############
//#####################################################
$this_request = check_request();
$is_this_a_safe_request = $this_request['is_this_a_safe_request'];
$page = $this_request['get1'];
$typea = $this_request['get2'];
$typeb = $this_request['get3'];
$typec = $this_request['get4'];
$typed = $this_request['get5'];
$typee = $this_request['get6'];
$typef = $this_request['get7'];
$typeg = $this_request['get8'];
$typeh = $this_request['get9'];
$typei = $this_request['get10'];
$typej = $this_request['get11'];
$typek = $this_request['get12'];
$typel = $this_request['get13'];
$typem = $this_request['get14'];
$typen = $this_request['get15'];
$typeo = $this_request['get16'];
$typep = $this_request['get17'];
$typeq = $this_request['get18'];
$typer = $this_request['get19'];
$types = $this_request['get20'];

//Api result array
$result = array();
$api_request_type = 2;

//bot check
$is_user_a_bot = bot_check();

//#####################################################
//############# PRIMARY OBJECTS & OPTIONS #############
//#####################################################
$siteOptions = new siteOptions();
$base_url = $siteOptions->get('base_url');
$system_name = $siteOptions->get('system_name');
$csrf_tokens = $siteOptions->get('csrf_tokens');
$anti_spam_tokens = $siteOptions->get('anti_spam_tokens');
$smtp_host = $siteOptions->get('smtp_host');
$smtp_user = $siteOptions->get('smtp_user');
$smtp_password = $siteOptions->get('smtp_password');
$admin_email = $siteOptions->get('admin_email');
$last_email_sent = $siteOptions->get('last_email_sent');
$smtp_from = $siteOptions->get('smtp_user');

//############################################
//############### HTTP HEADERS ###############
//############################################
include_once('config/http_headers.php');

if ($is_this_a_safe_request == 0) {
  $result['status'] = 0;
  header('Content-type: application/json');
  echo json_encode($result);
  die();
}

//#####################################################
//############# OBJECTS & CLASS FUNCTIONS #############
//#####################################################
$crypt = new EncryptData();  // (1/3)
$tokens = new tokens(); // (2/3)
$csrf = new csrf(); // (3/3)
//PHP Mailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include('functions/PHPMailer/src/Exception.php');
include('functions/PHPMailer/src/PHPMailer.php');
include('functions/PHPMailer/src/SMTP.php');
$mail = new PHPMailer(true);


//###########################################
//############### LOGIN CHECK ###############
//###########################################
include_once('includes/loginCheck.php');



//##############################################
//############### REQUEST ROUTER ###############
//##############################################

if ($staticFile == 1) {
  include_once('static_file_router.php');
} else {
  include_once('api_router.php');
}
