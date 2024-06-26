<?php
//##########################################
//############### KNOWN BOTS ###############
//##########################################

$bots = array(
  'google',
  'bing',
  'yahoo',
  'bot',
  'crawl',
  'spider',
  'slurp',
  'facebook',
  'scan',
  'bubing',
  'adscanner',
  'quant',
  'jeeves',
  'teoma',
  'jobboerse',
  'scout',
  'cloud',
  'addthis',
  'pinterest',
  'news',
  'index',
  'mega',
  'daum',
  'charlotte',
  'openstat',
  'alexa',
  'riddler'
);

if (preg_match('/(' . join('|', $bots) . ')/is', strtolower($user_agent))) {
  $known_bot = 1;
} else {
  $known_bot = 0;
}
