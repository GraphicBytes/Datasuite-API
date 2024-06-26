<?php
function sendEmail($email, $display_name, $subject, $themessage, $altbody)
{
    global $db;
    global $last_email_sent;
    global $request_time;
    global $mail;

    global $smtp_host;
    global $smtp_user;
    global $smtp_password;

    $return = false;

    $lastEmailSent = $last_email_sent;

    $ping_email_now = 1;

    if (($request_time - $lastEmailSent) < 10) {
        $ping_email_now = 0;
    }

    $ping_email_now = 1;

    if ($ping_email_now == 1) {

        $mysqlquerycountRes = $db->sql("SELECT id FROM ppat_email_queue WHERE id > 0 ORDER BY id ASC");
        $emailQueueCount = $mysqlquerycountRes->num_rows;

        if ($emailQueueCount > 0) {
            $ping_email_now = 0;
        }
    }

    if ($ping_email_now == 0) {

        $db->sql("INSERT INTO ppat_email_queue SET email=?, display_name=?, subject=?, body=?, altbody=?", 'sssss', $email, $display_name, $subject, $themessage, $altbody);

        $return = true;
    }

    if ($ping_email_now == 1) {

        $mail->IsSMTP();
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
            ),
        );

        $mail->SMTPDebug = false;
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'ssl';
        $mail->Host = $smtp_host;
        $mail->Port = 465;
        $mail->IsHTML(true);
        $mail->Username = $smtp_user;
        $mail->Password = $smtp_password;
        $mail->SetFrom($smtp_user);
        $mail->Subject = $subject;
        $mail->Body = $themessage;
        $mail->AddAddress($email, $display_name);

        $mail->clearCustomHeaders();

        if ($mail->send()) {
            $return = true;
        } else {
            $return = false;
        }

        $db->sql("UPDATE ppat_options SET meta_value=? WHERE meta_key='last_email_sent' ", "i", $request_time);
    }

    return $return;
}
