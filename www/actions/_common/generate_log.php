<?php
function generate_log_record($data, $client_ip)
{
    // Define the log file path
    $logFilePath = '/var/www/html/restrictions.log';

    // Message to log
    $message = date("d-m-Y H:i:s") . ": " . $data . " | Client IP: " . $client_ip . "\n";

    // Open the log file in append mode
    $logFile = fopen($logFilePath, 'a');

    if ($logFile) {
        // Write the message to the log file
        fwrite($logFile, $message);

        // Close the log file
        fclose($logFile);
    }
}