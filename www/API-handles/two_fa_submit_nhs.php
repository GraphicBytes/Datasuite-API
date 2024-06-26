<?php

$token_check = 0;
if (isset($_POST['CSRFtoken'])) {
    $token = $_POST['CSRFtoken'];
    $token_check = $tokens->check_token($token);
}

if (is_malicious() or $token_check == 0) {
    log_malicious();
    $result['form_error'] = true;
    $result['message'] = "Invalid token";
} else {
    $validation_passed = true;
    $twoFaCodetwoFaCode = $_POST['twoFaCodetwoFaCode'];
    $verification_email = $_POST['verification_email'];
    $twoFA_user_email = null;
    $user_type = isset($_POST['user_type']) ? $_POST['user_type'] : '';
    $coordinator_email = isset($_POST['coordinator_email']) ? $_POST['coordinator_email'] : '';

    if (!isset($twoFaCodetwoFaCode) || empty($twoFaCodetwoFaCode)) {
        $result['form_error'] = true;
        $result['message'] = "Please enter the 2FA code we emailed you";
        $validation_passed = false;
    }

    $twoFaToken = $_POST['twoFaToken'];
    $user_token = json_decode($crypt->decrypt($twoFaToken));

    if (isset($user_token->twoFA_user_email)) {
        $twoFA_user_email = $user_token->twoFA_user_email;

        if ($verification_email !== $twoFA_user_email) {
            $result['form_error'] = true;
            $result['message'] = "Original email was changed";
            $validation_passed = false;
        }
    } else {
        $result['form_error'] = true;
        $result['message'] = "Invalid email";
        $validation_passed = false;
    }

    if ($validation_passed) {

        $token_cutofF_time = $current_time - 900;
        $valid_2fa = false;
        $userData = array();

        $res = $db->sql("SELECT * FROM ppat_2fa_codes WHERE user_email=? AND code_time > ? ORDER BY id DESC LIMIT 1", 'si', $twoFA_user_email, $token_cutofF_time);
        while ($row = $res->fetch_assoc()) {
            $saved_token = $crypt->decrypt($row['validation_code']);

            if ($twoFaCodetwoFaCode == $saved_token) {
                $valid_2fa = true;
            } else {
                $result['form_error'] = true;
                $result['message'] = "Invalid 2FA code entered";
                log_malicious();
            }
        }

        $result['Token'] = $crypt->encrypt(json_encode($userData));
        $result['email'] = $verification_email;
        $result['user_type'] = $user_type;
        $result['coordinator_email'] = $coordinator_email;
        $result['valid_2fa'] = $valid_2fa;
    }
}
