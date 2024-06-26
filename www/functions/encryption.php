<?php
// usage
//$encrypted_data = $EncryptData->encrypt($data);
//$decrypted_data = $EncryptData->decrypt($encrypted_data);

class EncryptData
{

  var $key = "";

  function __construct()
  {
    global $encryption_key;
    $this->key = $encryption_key;
  }

  function encrypt($pure_string)
  {
    $cipher     = 'AES-256-CBC';
    $options    = OPENSSL_RAW_DATA;
    $hash_algo  = 'sha256';
    $sha2len    = 32;
    $ivlen = openssl_cipher_iv_length($cipher);
    $iv = openssl_random_pseudo_bytes($ivlen);
    $ciphertext_raw = openssl_encrypt($pure_string, $cipher, $this->key, $options, $iv);
    $hmac = hash_hmac($hash_algo, $ciphertext_raw, $this->key, true);
    $return = $iv . $hmac . $ciphertext_raw;
    return base64_encode($return);
  }

  function decrypt($encrypted_string)
  {

    if ($encrypted_string !== null) {
      # code...
      try {
        $encrypted_string = base64_decode($encrypted_string);
        $cipher     = 'AES-256-CBC';
        $options    = OPENSSL_RAW_DATA;
        $hash_algo  = 'sha256';
        $sha2len    = 32;
        $ivlen = openssl_cipher_iv_length($cipher);
        $iv = substr($encrypted_string, 0, $ivlen);
        $hmac = substr($encrypted_string, $ivlen, $sha2len);
        $ciphertext_raw = substr($encrypted_string, $ivlen + $sha2len);
        $original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $this->key, $options, $iv);
        $calcmac = hash_hmac($hash_algo, $ciphertext_raw, $this->key, true);
        if (function_exists('hash_equals')) {
          if (hash_equals($hmac, $calcmac))
            return $original_plaintext;
        } else {
          if ($this->hash_equals_custom($hmac, $calcmac))
            return $original_plaintext;
        }
      } catch (ErrorException $e) {
        return "ERROR";
      }
    } else {
      return "ERROR";
    }
  }
}
