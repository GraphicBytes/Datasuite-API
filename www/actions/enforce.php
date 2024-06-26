<?php
header("Access-Control-Allow-Origin: https://fujifilmeventsusa.com");

$timezone = new DateTimeZone('America/New_York');
$currentDateTime = new DateTime('now', $timezone);
$targetDateTime = new DateTime('2024-04-21 23:59', $timezone);

if ($currentDateTime > $targetDateTime) {
    return;
}

require_once '_common/action_variables.php';
require_once '_common/get_client_ip.php';
require_once '_common/rate_limiter.php';
require_once '_common/geolocate.php';
require_once '_common/generate_log.php';
require_once '/var/www/html/vendor/autoload.php';
use GeoIp2\Database\Reader;
use Jaybizzle\CrawlerDetect\CrawlerDetect;

$request_method = $_SERVER["REQUEST_METHOD"];
if ($request_method !== "POST") {
    http_response_code(400);
    // echo json_encode(["error" => "Invalid method"]);
    generate_log_record($request_method . " method used", $client_ip);

    exit;
}

$client_ip = getClientIP();
if (!$client_ip) {
    http_response_code(400);
    echo json_encode([
        'error' => 'Something went wrong'
    ]);
    generate_log_record("Request IP empty", $client_ip);

    exit;
}

$rate_limit = rate_limit($client_ip, $db);
if (!$rate_limit) {
    http_response_code(429);
    echo json_encode([
        'error' => 'Too many requests, please try again later'
    ]);
    generate_log_record("Rate limiter achieved", $client_ip);

    exit;
}

$userAgent = $_SERVER['HTTP_USER_AGENT'];
$CrawlerDetect = new CrawlerDetect;
if ($userAgent && $CrawlerDetect->isCrawler($userAgent)) {
    echo "Invalid user";
    generate_log_record("Invalid user agent, user agent: " . $userAgent, $client_ip);

    exit;
}

$cityDbReader = new Reader('/var/www/html/geo_203804932.mmdb');

$ip_geo = $cityDbReader->city($client_ip);
$ip_country_code = $ip_geo->country->isoCode;
// $ip_postal_code = $ip_geo->postal->code;
// $ip_latitude = $ip_geo->location->latitude;
// $ip_longitude = $ip_geo->location->longitude;

$allowed_countries = ["US"];

if (!$ip_country_code || !in_array($ip_country_code, $allowed_countries)) {
    http_response_code(400);
    echo json_encode([
        'error' => 'Something went wrong'
    ]);
    generate_log_record("Outside allowed countries request, country code: " . $ip_country_code, $client_ip);

    exit;
}

// if (!$ip_latitude || !$ip_longitude) {
//     http_response_code(400);
//     echo json_encode([
//         'error' => 'Something went wrong'
//     ]);
//     generate_log_record("Can't geolocate client IP using Maxmind database", $client_ip);

//     exit;
// }

// $radius_km = 193; // 120 miles
// $postal_code_point = '67655';
// $postal_code_coordinates = get_latlng($postal_code_point);

// if (!is_array($postal_code_coordinates)) {
//     http_response_code(400);
//     echo json_encode([
//         'error' => 'Something went wrong'
//     ]);
//     generate_log_record("Can't geolocate postal code coordinates, google response: " . $postal_code_coordinates, $client_ip);

//     exit;
// }

// $distance = calculateDistance($ip_latitude, $ip_longitude, $postal_code_coordinates['lat'], $postal_code_coordinates['lng']);

// // Check if the distance is within the radius
// if ($distance >= $radius_km) {
//     http_response_code(400);
//     echo json_encode([
//         'error' => 'Something went wrong'
//     ]);
//     generate_log_record("User location is outside the radius distance, country code: " . $ip_country_code, $client_ip);

//     exit;
// }

$enforced = $_POST['enforced'];
if (!isset($_POST['enforced'])) {
    http_response_code(400);
    echo json_encode([
        'error' => 'Something went wrong'
    ]);
    generate_log_record("Enforced input missing", $client_ip);

    exit;
}


$existingIP = $db->sql("SELECT * FROM ppat_submissions WHERE form_id=? AND user_ip=?", 'is', 68, $client_ip);
if ($existingIP->num_rows > 0) {
    http_response_code(400);
    echo json_encode([
        'error' => 'Something went wrong'
    ]);
    generate_log_record("IP already exists in submissions table", $client_ip);

    exit;
}