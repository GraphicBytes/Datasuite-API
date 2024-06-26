<?php

$token_check = 0;
if (isset($_POST['CSRFtoken'])) {
    $token = $_POST['CSRFtoken'];
    $token_check = $tokens->check_token($token);
}

function check_email_domain($email)
{
    $allowed_domains = array('nhs.net', 'nhs.uk', 'bright.uk.com');
    $email_domain = explode("@", $email)[1];

    foreach ($allowed_domains as $allowed_domain) {
        if (strpos($email_domain, $allowed_domain) !== false) {
            return true;
        }
    }

    return false;
}

if (is_malicious() or $token_check == 0) {
    log_malicious();
    $result['form_error'] = true;
    $result['message'] = "INCORRECT TOKEN";
} else {
    $validation_passed = true;
    $employee_email = isset($_POST['employee_email']) ? $_POST['employee_email'] : '';
    $volunteer_email = isset($_POST['volunteer_email']) ? $_POST['volunteer_email'] : '';
    $coordinator_email = isset($_POST['coordinator_email']) ? $_POST['coordinator_email'] : '';
    $user_type = isset($_POST['user_type']) ? $_POST['user_type'] : '';
    $domain_pass = false;
    $formErr = '';
    $app_email = '';
    $fieldErr = null;

    if (empty($user_type)) {
        $formErr = "Please select an user type";
        $validation_passed = false;
    }

    switch ($user_type) {
        case 'employee':
            if (empty($employee_email)) {
                $formErr = "Employee email is required";
                $fieldErr = 'employee_email';
                $validation_passed = false;
            } else {
                if (check_email_domain($employee_email) == false) {
                    $formErr = "Email is not allowed";
                    $fieldErr = 'employee_email';
                    $validation_passed = false;
                }
            }

            if ($validation_passed) {
                $app_email = $employee_email;
            }

            break;

        case 'volunteer':
            if (empty($volunteer_email)) {
                $formErr = "Volunteer email is required";
                $fieldErr = 'volunteer_email';
                $validation_passed = false;
            }

            if (empty($coordinator_email)) {
                $formErr = "Coordinator email is required";
                $fieldErr = 'coordinator_email';
                $validation_passed = false;
            } else {
                if (check_email_domain($coordinator_email) == false) {
                    $formErr = "Email is not allowed";
                    $fieldErr = 'coordinator_email';
                    $validation_passed = false;
                }
            }

            if ($validation_passed) {
                $app_email = $volunteer_email;
            }

            break;
    }

    $result['form_error'] = true;
    $result['field_error'] = $fieldErr;
    $result['message'] = $formErr;

    if ($validation_passed) {
        $result['form_error'] = false;
        $result['message'] = 'success';

        $two_fa_code = random_str(8);
        $two_fa_code_encrypted = $crypt->encrypt($two_fa_code);

        $subject = "Telling your stories through the lens";

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
                            <td style="padding: 50px 0 80px 0;">
                                <img src="https://nhs75-fujifilm.com/assets/img/nhs-email-logo.png" width="172" alt="NHS Logo"></td>
                            </tr>
                        <tr>
                            <td>
                                <h1 style="color: #005EB8; font-size: 26px; font-family: Arial, Helvetica, sans-serif; margin: 0; padding: 0 0 20px 0;">Competition Application</h1>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 0 0 20px 0;">
                                <p style="color: #231F20; font-size: 16px; font-family: Arial, Helvetica, sans-serif; font-weight: bold;">To continue your application please verify your email address.</p>
                                <p style="color: #231F20; font-size: 16px; font-family: Arial, Helvetica, sans-serif; font-weight: bold;">Copy and paste this code into the field on the web page.</p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p style="color: #231F20; font-size: 26px; font-family: Arial, Helvetica, sans-serif; font-weight: bold;">' . $two_fa_code . '</p>
                            </td>
                        </tr>
                    </table>
                </body>
            </html>';

        $altbody = 'Competition Application - To continue your application please verify your email address. Copy and paste this code into the field on the web page.' . $two_fa_code;

        $to_name = explode('@', $app_email);

        if (sendEmail($app_email, $to_name[0], $subject, $email_template, $altbody)) {
            $userData = array();
            $randomDataKey = random_str(8); //acting as a random salt key
            $userData[$randomDataKey] = random_str(16); //acting as a random salt value
            $userData['twoFA_user_email'] = $app_email;

            $result['form_error'] = false;
            $result['message'] = "Check your email for the validation code";

            $result['token'] = $crypt->encrypt(json_encode($userData));

            $db->sql("INSERT INTO ppat_2fa_codes SET user_email=?, validation_code=?, code_time=?", 'ssi', $app_email, $two_fa_code_encrypted, $current_time);
        } else {
            $result['form_error'] = true;
            $result['message'] = "UNKNOW ERROR";
        }
    }
}
