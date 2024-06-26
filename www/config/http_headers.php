<?php
header('Expires: Sun, 01 Jan 2014 00:00:00 GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : $fullURL;
if (filter_var($referer, FILTER_VALIDATE_URL)) { 
  $parsedUrl = parse_url($referer);
  $origin = $parsedUrl['scheme'] . '://' . $parsedUrl['host']; 
  if (!empty($parsedUrl['port']) && (($parsedUrl['scheme'] === 'http' && $parsedUrl['port'] !== 80) || ($parsedUrl['scheme'] === 'https' && $parsedUrl['port'] !== 443))) {
      $origin .= ':' . $parsedUrl['port'];
  } 
  $origin = rtrim($origin, '/');
  
  header('Access-Control-Allow-Origin: ' . $origin);
} else {  
  $fullURL = rtrim(parse_url($fullURL, PHP_URL_SCHEME) . '://' . parse_url($fullURL, PHP_URL_HOST), '/');
  header('Access-Control-Allow-Origin: ' . $fullURL);
} 

header("Access-Control-Expose-Headers: false");
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, POST');
header("Strict-Transport-Security: max-age=31536000; includeSubDomains; preload");
header("X-Frame-Options: SAMEORIGIN");
header("Referrer-Policy: strict-origin-when-cross-origin");
header("X-XSS-Protection: 1; mode=block");
header("X-Content-Type-Options: nosniff");

header("Feature-Policy: geolocation 'none'");
//header('Permissions-Policy: geolocation=(none)');

header('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, PATCH, DELETE');

$csp = "default-src 'self' " . $base_url . "; " .
       "script-src 'self' " . $base_url . "; " . // Removed 'unsafe-inline', 'unsafe-eval', and jquery.com. Consider adding nonces or hashes for inline scripts.
       "object-src 'none'; " .
       "style-src 'self' " . $base_url . " 'unsafe-inline'; " . // Kept 'unsafe-inline' for styles, consider using nonces/hashes if feasible.
       "img-src 'self' " . $base_url . " data:; " .
       "media-src 'self'; " .
       "frame-src 'self' " . $base_url . "; " .
       "font-src 'self' data: " . $base_url . "; " .
       "connect-src 'self' " . $base_url . "; " .
       "frame-ancestors 'none'; " . // This was already set as recommended.
       "form-action 'self'; " . // Restrict form submissions to the same origin.
       "upgrade-insecure-requests; " . // Add this to upgrade insecure requests to secure ones.
       "block-all-mixed-content";
header("Content-Security-Policy: $csp");

//header("X-Content-Security-Policy: default-src 'self' " . $base_url . "; script-src 'self' " . $base_url . " 'unsafe-inline' 'unsafe-eval' data: *.jquery.com; object-src 'none'; style-src 'self' " . $base_url . " 'unsafe-inline' ; img-src 'self' " . $base_url . " data: ; media-src 'self'; frame-src 'self' " . $base_url . "; font-src 'self' data: " . $base_url . " ; connect-src 'self' " . $base_url . "; frame-ancestors 'self' " . $base_url . ";");
