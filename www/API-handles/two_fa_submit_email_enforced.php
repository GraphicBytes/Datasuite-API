<?php
require_once '/var/www/html/actions/enforce.php';

$form_id = $_POST['form_id'];
if (empty($form_id)) {
    http_response_code(400);
    echo json_encode([
        'error' => 'Invalid form id'
    ]);
    generate_log_record("No form id passed while verifying email, country code: " . $ip_country_code, $client_ip);

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

$twofaemail = $_POST['twofaemail'];
if (!$twofaemail || !isEmailValid($twofaemail)) {
    http_response_code(400);
    echo json_encode([
        'error' => 'Invalid email'
    ]);

    exit;
}

$existingEmail = $db->sql("SELECT * FROM ppat_submissions WHERE form_id=? ORDER BY id DESC", 'i', $form_id);
if ($existingEmail->num_rows > 0) {
    while ($row = $existingEmail->fetch_assoc()) {
        $formData = unserialize($row['form_data']);

        if (in_array($twofaemail, $formData)) {
            http_response_code(400);
            echo json_encode([
                'error' => 'Email already registered'
            ]);

            exit;
        }
    }
}

$existingRecord = $db->sql("SELECT * FROM ppat_2fa_codes_enforced WHERE user_email = ?", 's', $twofaemail);
if ($existingRecord->num_rows > 0) {
    $time_passed = false;
    while ($row = $existingRecord->fetch_assoc()) {
        if (($row['code_time'] + 60) < $current_time) {
            $time_passed = true;
        }
    }

    if (!$time_passed) {
        http_response_code(400);
        echo json_encode([
            'error' => 'Please try again shortly'
        ]);

        exit;
    }
}

$two_fa_code = random_str(8);
$two_fa_code_encrypted = $crypt->encrypt($two_fa_code);
$subject = "Complete One-Time Password Verification Now - FUJIFILM X100VI Limited Edition Raffle";

$email_template =
    '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml" lang="ro">
        <head>
            <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>                
        </head>
        <body>
            <table style="display:block; border:none; margin:0; border-collapse:collapse;">
                <tr>
                    <td colspan="3" class="wrapto100pc" width="650" height="auto" align="center" border="0" cellpadding="0" cellspacing="0">
                    <p class="preheader_wrap" align="center" style="margin:0px; padding:0px; font-size:0px; line-height: 0px; color: #ffffff; display:none; mso-hide:all; opacity:0;">
                        <span id="preheader" style="margin:0px; padding:0px; font-size:0px; line-height: 0px; color: #ffffff; display:none; mso-hide:all; opacity:0;">Must-complete step: Finish 2FA verification to continue your X100VI Limited Edition Raffle entry.</span>
                    </p>
                    <p align="center" style="margin:0px; padding:0px; font-size:0px; line-height: 0px; color: #ffffff; display:none; mso-hide:all; opacity:0;"> &nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;</p>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 40px 0 40px 0;">
                        <img src="https://fujifilmeventsusa.com/assets/images/fujifilm-x-gfx-logo.svg" width="92" alt="FUJIFILM Logo"></td>
                    </tr>
                <tr>
                    <td>
                        <h1 style="color: #000000; font-size: 22px; line-height: 18px; font-family: \'Fjalla One\', \'ff-good-headline-web-pro-ext\', \'Helvetica Neue\', \'Conv_PlacardCondensed\', \'Segoe UI WestEuropean\', Helvetica, sans-serif; font-weight:700; margin: 0; padding: 0;">Raffle Entry</h1>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 10px 0 20px 0;">
                        <p style="color: #000000; font-size: 16px; line-height:20px; font-family: \'Noto sans\', \'Roboto\', Arial, sans-serif; font-weight: 400;">
                            To continue your application please verify your email address.<br/>
                            Copy and paste this code into the field on the web page.
                        </p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p style="color: #FB0020; font-size: 22px; line-height: 18px; font-family: \'Fjalla One\', \'ff-good-headline-web-pro-ext\', \'Helvetica Neue\', \'Conv_PlacardCondensed\', \'Segoe UI WestEuropean\', Helvetica, sans-serif; font-weight: 700;">' . $two_fa_code . '</p>
                    </td>
                </tr>
            </table>
        </body>
    </html>';

$altbody = 'Competition Application - To continue your application please verify your email address. Copy and paste this code into the field on the web page.' . $two_fa_code;

$to_name = explode('@', $twofaemail);

if (sendEmail($twofaemail, $to_name[0], $subject, $email_template, $altbody)) {

    // $db->sql("INSERT INTO ppat_2fa_codes_enforced SET user_email=?, validation_code=?, code_time=?", 'ssi', $twofaemail, $two_fa_code_encrypted, $current_time);

    // $existingRecord = $db->sql("SELECT * FROM ppat_2fa_codes_enforced WHERE user_email = ?", 's', $twofaemail);

    if ($existingRecord->num_rows > 0) {
        // Email exists, update other fields

        $db->sql("UPDATE ppat_2fa_codes_enforced SET validation_code=?, code_time=? WHERE user_email=?", 'sis', $two_fa_code_encrypted, $current_time, $twofaemail);
    } else {
        // Email does not exist, insert new record
        $db->sql("INSERT INTO ppat_2fa_codes_enforced SET user_email=?, validation_code=?, code_time=?", 'ssi', $twofaemail, $two_fa_code_encrypted, $current_time);
    }

    http_response_code(200);
    echo json_encode([
        'success' => 'The 2FA code was sent to your email address',
        'email' => $twofaemail
    ]);

    exit;
} else {
    http_response_code(500);
    echo json_encode([
        'error' => 'Email could not be sent, please try again'
    ]);

    exit;
}