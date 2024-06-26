<?php
require_once '/var/www/html/actions/enforce.php';

$twofacode = $_POST['twofacode'];
if (!$twofacode) {
    http_response_code(400);
    echo json_encode([
        'error' => 'Please enter the 2FA code code'
    ]);

    exit;
}

function isEmailValid($email)
{
    // Remove any leading or trailing whitespace
    $email = trim($email);

    // Check if the email is valid using filter_var
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true; // Valid email
    } else {
        return false; // Invalid email
    }
}

$twofaemail = $_POST['entered_twofaemail'];
if (!$twofaemail || !isEmailValid($twofaemail)) {
    http_response_code(400);
    echo json_encode([
        'error' => 'Invalid email'
    ]);

    exit;
}

$token_cutoff_time = $current_time - 60; // 5 minutes
$check_twofa_data = $db->sql("SELECT * FROM ppat_2fa_codes_enforced WHERE user_email=? AND code_time > ? ORDER BY id DESC LIMIT 1", 'si', $twofaemail, $token_cutoff_time);

if ($check_twofa_data->num_rows < 1) {
    http_response_code(400);
    echo json_encode([
        'error' => 'Invalid 2FA code entered'
    ]);
    exit;
}

while ($row = $check_twofa_data->fetch_assoc()) {
    $saved_code = $crypt->decrypt($row['validation_code']);

    if ($twofacode == $saved_code) {
        $valid_2fa = true;

        $db->sql("UPDATE ppat_2fa_codes_enforced SET validation_code=?, code_time=? WHERE user_email=?", 'sis', 'verified', $current_time, $twofaemail);

        http_response_code(200);
        echo json_encode([
            'validcode' => 'Valid 2FA'
        ]);
        exit;
    } else {
        http_response_code(400);
        echo json_encode([
            'error' => 'Invalid 2FA code entered'
        ]);
        exit;
    }
}