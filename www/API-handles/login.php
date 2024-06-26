<?php

$token_check = 0;
if (isset($_POST['CSRFtoken'])) {
    $token = $_POST['CSRFtoken'];
    $token_check = $tokens->check_token($token);
}

if (is_malicious() or $token_check == 0) {

    log_malicious();
    $result['status'] = 0;
    $result['feedback'] = "INCORRECT PASSWORD";
} else {

    $email = $_POST['username'];
    $submitted_password = $_POST['password'];

    //Does Account Exist
    $account_exists = 0;

    $res = $db->sql("SELECT * FROM ppat_users WHERE email=? ORDER BY id DESC LIMIT 1", 's', $email);
    while ($row = $res->fetch_assoc()) {
        $this_row = $row;
        $user_id = $id = $row['id'];
        $cookie_id = $row['cookie_id'];
        $login_fail = $row['login_fail'];
        $saved_password = $row['password'];
        $display_name = $row['display_name'];

        //account is good
        $account_exists = 1;
    }

    if ($account_exists == 1 && $login_fail < 10) {

        if ($cookie_id == null) {
            $cookie_id = random_str(256);
        }

        $password_match = pw_check($submitted_password, $saved_password);

        if ($password_match == 1) {

            $time = $request_time;

            $db->sql("UPDATE ppat_users SET cookie_id=?, cookie_age=?, login_fail ='0', cookie_fail='0' WHERE id=?", 'sii', $cookie_id, $time, $user_id);

            $db->sql("DELETE FROM ppat_malicious_ips WHERE ip_address=?", 's', $user_ip);
            $db->sql("DELETE FROM ppat_malicious_useragents WHERE agent_ip=?", 's', $user_ip);

            //User Token Data Data
            $userData['user_id'] = $user_id;
            $userData['cookie_id'] = $cookie_id;

            $two_fa_code = random_str(8);
            $two_fa_code_encrypted = $crypt->encrypt($two_fa_code);

            $subject = "New login attempt";

            $themessage = '<table width="300" style="display:block; width:300px; border:none; margin:20px auto; border-collapse:collapse;"><tr><th>';
            $themessage = $themessage . '<h1 style="color:#0066a3; font-weight:bold; font-size:16pt; display:block; width:80%; text-align:center; padding:0 10% 0 10%; margin:0 0 20px 0;">' . $system_name . '</h1>';
            $themessage = $themessage . '<p style="color:#222222; font-weight:normal; font-size:11pt; display:block; width:80%; text-align:center; padding:0 10% 0 10%; margin:0 0 20px 0;">A new login has been attempted from IP:' . $user_ip . '. If this was not you please report this to your system admin, otherwise copy and paste the following code to complete your login.</p>';
            $themessage = $themessage . '<h2 style="color:#0066a3; font-weight:bold; font-size:12pt; display:block; width:80%; text-align:center; padding:0 10% 0 10%; margin:0 0 20px 0;">' . $two_fa_code . '</h2>';

            $themessage = $themessage . '</th></tr></table>';

            $altbody = 'A new login has been attempted from IP:' . $user_ip . '. If this was not you please report this to your system admin, otherwise copy and paste the following code to complete your login:' . $two_fa_code;

            if (sendEmail($email, $display_name, $subject, $themessage, $altbody)) {

                $userData = array();
                $randomDataKey = random_str(8); //acting as a random salt key
                $userData[$randomDataKey] = random_str(16); //acting as a random salt value
                $userData['twoFA_user_id'] = $user_id;

                $result['status'] = 1;
                $result['feedback'] = "CHECK EMAIL FOR VALIDATION CODE";

                $result['token'] = $crypt->encrypt(json_encode($userData));

                $db->sql("INSERT INTO ppat_2fa_codes SET user_id=?, validation_code=?, code_time=?", 'isi', $user_id, $two_fa_code_encrypted, $current_time);
            } else {
                $result['status'] = 0;
                $result['feedback'] = "UNKNOW ERROR";
            }
        } else {
            if ($_POST['password'] == "") {
            } else {
                $login_fail = $login_fail + 1;
            }
            $db->sql("UPDATE ppat_users SET login_fail=? WHERE id=?", 'ii', $login_fail, $user_id);
            log_malicious();
            $result['status'] = 0;
            $result['feedback'] = "INCORRECT PASSWORD";
        }
    } else if ($account_exists == 1 && $login_fail < 20) {
        $result['status'] = 0;
        $result['feedback'] = "TO MANY LOGIN REQUESTS, PLEASE CONTACT ADMIN";
        log_malicious();
    } else {
        $result['status'] = 0;
        $result['feedback'] = "NO ACCOUNT REGISTERED WITH THAT EMAIL";
        log_malicious();
    }
}
