<?php
//###########################################
//############### ENVIRONMENT ###############
//###########################################
$fullURL = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$env = (int)getenv('ENV');

if (str_contains($fullURL, ':5051') || str_contains($fullURL, 'static.')) {
  $staticFile = 1;
} else {
  $staticFile = 0;
}

if ($env == 1) {
   $debug = 0;
} else {
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL); 
  $debug = 1;
}


//#########################################
//############### CONSTANTS ###############
//#########################################
if (str_contains($fullURL, 'staging.')) {
  define("STATIC_URL", "datasuite-admin.bright-staging.uk/static/");
} else if (str_contains($fullURL, ':5050')) {
  define("STATIC_URL", "http://192.168.1.66:5051/");
} else {
  define("STATIC_URL", "https://static.datasuite.com/");
}


//#########################################
//############### KEYS & SALTS ############
//#########################################
$websitesalt = getenv('WEBSITESALT');
$tokenSalt = getenv('TOKENSALT');
$encryption_key = getenv('ENCRYPTIONKEY');
$salt = getenv('SALT');

//##############################################
//############### DB CREDENTIALS ###############
//##############################################
$dbhost = getenv('MYSQL_HOST');
$dbuser = getenv('MYSQL_USER');
$dbpw = getenv('MYSQL_PASSWORD');
$dbname = getenv('MYSQL_DATABASE');
