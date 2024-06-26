<?php

$token_check = 0;
if (isset($_POST['CSRFtoken'])) {
    $token = $_POST['CSRFtoken'];
    $token_check = $tokens->check_token($token);
}

if (is_malicious() or $token_check == 0) {

    log_malicious();
    $result['status'] = 0;
    $result['feedback'] = "INCORRECT CODE";
} else {

    $twoFaCodetwoFaCode = $_POST['twoFaCodetwoFaCode'];
    $twoFaToken = $_POST['twoFaToken'];

    $user_token = json_decode($crypt->decrypt($twoFaToken));

    $twoFA_user_id = 0;
    if (isset($user_token->twoFA_user_id)) {
        $twoFA_user_id = $user_token->twoFA_user_id;
        $result['feedback'] = "INVALID TOKEN ENTERED!";
    }

    $token_cutofF_time = $current_time - 900;

    $valid = 0;

    $userData = array();

    $res = $db->sql("SELECT * FROM ppat_2fa_codes WHERE user_id=? AND code_time > ? ORDER BY id DESC LIMIT 1", 'ii', $twoFA_user_id, $token_cutofF_time);
    while ($row = $res->fetch_assoc()) {

        $saved_token = $crypt->decrypt($row['validation_code']);

        if ($twoFaCodetwoFaCode == $saved_token) {

            $valid = 0;

            $res2 = $db->sql("SELECT * FROM ppat_users WHERE id=? AND login_fail < 10 ORDER BY id DESC LIMIT 1", 'i', $twoFA_user_id);
            while ($row2 = $res2->fetch_assoc()) {
                $user_id = $id = $row2['id'];
                $cookie_id = $row2['cookie_id'];

                $valid = 1;
            }
            $db->sql("DELETE FROM ppat_malicious_ips WHERE ip_address=?", 's', $user_ip);
            $db->sql("DELETE FROM ppat_malicious_useragents WHERE agent_ip=?", 's', $user_ip);

            //User Token Data Data
            $userData['user_id'] = $user_id;
            $userData['cookie_id'] = $cookie_id;
        } else {
            $result['feedback'] = "INVALID TOKEN!";
            $db->sql("UPDATE ppat_users SET login_fail=login_fail+1 WHERE id=?", 'i', $twoFA_user_id);
            log_malicious();
        }
    }

    $result['Token'] = $crypt->encrypt(json_encode($userData));
    $result['status'] = $valid;
}
