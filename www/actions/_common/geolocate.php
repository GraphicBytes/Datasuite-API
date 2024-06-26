<?php
function get_latlng($postal_code)
{
    $postal_code = str_replace(" ", "+", urlencode($postal_code));
    $details_url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . $postal_code . "&sensor=false&key=AIzaSyAiM9J4AgwBKKAAeq7A5r3TyvqpXbSkpH0";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $details_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = json_decode(curl_exec($ch), true);
    curl_close($ch);

    // If Status Code is ZERO_RESULTS, OVER_QUERY_LIMIT, REQUEST_DENIED or INVALID_REQUEST
    if ($response['status'] != 'OK') {
        // echo $response['status'];
        return $response['status'];
    }

    $geometry = $response['results'][0]['geometry'];

    $array = array(
        'lat' => $geometry['location']['lat'],
        'lng' => $geometry['location']['lng'],
    );

    return $array;
}

// Function to calculate the distance between two sets of coordinates using the Haversine formula
function calculateDistance($lat1, $lon1, $lat2, $lon2)
{
    $earth_radius_km = 6371; // Earth's radius in kilometers
    $delta_lat = deg2rad($lat2 - $lat1);
    $delta_lon = deg2rad($lon2 - $lon1);
    $a = sin($delta_lat / 2) * sin($delta_lat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($delta_lon / 2) * sin($delta_lon / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    $distance = $earth_radius_km * $c;
    return $distance;
}