<?php
  function clean_ob_for_js($str){

    $str = str_replace("\n", '', $str);
    $str = str_replace("\r", '', $str);
    $str = str_replace("  ", ' ', $str);
    $str = str_replace("  ", ' ', $str);
    $str = str_replace("  ", ' ', $str);
    $str = str_replace("  ", ' ', $str);

    return $str;

  }
