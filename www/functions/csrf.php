<?php
class csrf {

   var $tokens = "";

   function __construct() {
     global $csrf_tokens;
     $this->tokens = unserialize($csrf_tokens);
   }

   function get_token(){

     global $crypt;

     $current_token = $this->tokens[0];
     $current_token = $crypt->encrypt($current_token);
     $current_token = substr($current_token, 0, -2);

     return $current_token;

   }

   function check_token($token){

     global $crypt;

     $valid = 0;

     $this_token = $token."==";

     $this_token_length = strlen($this_token);
     if ($this_token_length > 24) {
       $this_token = $crypt->decrypt($this_token);
       foreach ($this->tokens as $key => $valid_token) {
         if ($this_token == $valid_token) {$valid = 1;}
       }
     }

     return $valid;

   }

}
