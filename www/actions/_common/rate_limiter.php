<?php
function rate_limit($client_ip, $db)
{
    $limit = 10;
    $interval = 60;
    $currentTime = time();
    $allowedRequests = true;

    $check_ip_limit = $db->sql("SELECT * FROM ppat_rate_limit WHERE client_ip=?", 's', $client_ip);

    if ($check_ip_limit && $check_ip_limit->num_rows > 0) {
        $row = $check_ip_limit->fetch_assoc();

        // Check if the interval has passed since the last request
        if ($currentTime - $row['timestamp'] > $interval) {
            // Reset the timestamp and request count
            $sql = "UPDATE ppat_rate_limit SET timestamp = $currentTime, request_count = 1 WHERE client_ip = '$client_ip'";
            $db->sql($sql);
        } else {
            // Increment the request count
            $requestCount = $row['request_count'] + 1;

            // Check if the request count exceeds the limit
            if ($requestCount > $limit) {
                $allowedRequests = false;
            } else {
                // Update the request count
                $sql = "UPDATE ppat_rate_limit SET request_count = $requestCount WHERE client_ip = '$client_ip'";
                $db->sql($sql);
            }
        }
    } else {
        // Create a new entry
        $sql = "INSERT INTO ppat_rate_limit (client_ip, timestamp, request_count) VALUES ('$client_ip', $currentTime, 1)";
        $db->sql($sql);
    }

    return $allowedRequests;
}