<?php
require_once '/var/www/html/actions/enforce.php';

$middle_name = $_POST['middle_name'];
if ($middle_name && !empty($middle_name)) {
    http_response_code(400);
    echo json_encode([
        'error' => 'Something went wrong'
    ]);
    generate_log_record("Middle name filled, country code: " . $ip_country_code, $client_ip);

    exit;
}

$ip_field = $_POST['ip'];
if (!isset($ip_field)) {
    http_response_code(400);
    echo json_encode([
        'error' => 'Something went wrong'
    ]);
    generate_log_record("IP input missing, country code: " . $ip_country_code, $client_ip);

    exit;
}

$_POST['ip'] = $client_ip;

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

if ($form_id !== null or $form_id != "") {

    $token_check = $tokens->check_token($csrf_token);

    $first_error_target = null;

    if ($token_check == 1 && $session_token_check == 1) {

        $api_triggers = array();

        $qr_gen = 0;
        $form_exist = 0;

        $formres = $db->sql("SELECT * FROM ppat_forms WHERE id=?", 'i', $form_id);
        while ($formrow = $formres->fetch_assoc()) {
            $formdata = $formrow;
            $form_enabled = $formrow['status'];
            $form_user_group = $formrow['user_group'];
            $form_exist = 1;
            $qr_gen = $formrow['qr_gen'];
        }

        if ($form_exist == 0) {
            die();
        }

        // check if email is 2fa verified

        $user_email = $_POST['email'];

        if (!$user_email || !isEmailValid($user_email)) {
            $errors = 1;
            $response = 1;
            $message = "Invalid email";
            $error_data['email'] = "Invalid email";
            $first_error_target = "#ppat-email";
        }

        $email_verified = $db->sql("SELECT * FROM ppat_2fa_codes_enforced WHERE user_email=? ORDER BY id DESC LIMIT 1", 's', $user_email);

        if ($email_verified->num_rows < 1) {
            $errors = 1;
            $response = 1;
            $message = "Email not verified";
            $error_data['email'] = "Email not verified";
            $first_error_target = "#ppat-email";
        }

        while ($row = $email_verified->fetch_assoc()) {
            if ($row['validation_code'] !== 'verified') {
                $errors = 1;
                $response = 1;
                $message = "Email not verified";
                $error_data['email'] = "Email not verified";
                $first_error_target = "#ppat-email";
            }
        }

        // check if email is 2fa verified

        $formfields = array();
        $formres = $db->sql("SELECT * FROM ppat_form_fields WHERE form_id=? ORDER BY field_order ASC  ", 'i', $form_id);
        while ($formrow = $formres->fetch_assoc()) {
            $formfields[$formrow['id']] = $formrow;
        }

        $formfield_count = 0;
        $formfield_debug = "";
        $data = array();

        $uniqueFieldsUsed = 0;

        foreach ($formfields as $key) {

            $formfield_count = $formfield_count + 1;

            $field_name = $key['field_name'];
            $field_type = $key['field_type'];
            $required = $key['required'];

            $uniqueField = $key['unique_field'];
            if ($uniqueField == 1) {

                $alreadyTaken = 0;

                $submittedValue = $_POST[$field_name];

                $res = $db->sql("SELECT form_id, form_data, override_data FROM ppat_submissions WHERE form_id=?", "i", $form_id);
                while ($row = $res->fetch_assoc()) {

                    $thisFormData = unserialize($row['form_data']);

                    $savedValue = $thisFormData[$field_name];
                    if ($submittedValue == $savedValue) {
                        $alreadyTaken = 1;
                    }

                    if ($row['override_data'] !== null) {
                        $thisOverrideData = unserialize($row['override_data']);
                        if (isset($thisOverrideData[$field_name])) {
                            $savedValue = $thisOverrideData[$field_name];
                            if ($submittedValue == $savedValue) {
                                $alreadyTaken = 1;
                            }
                        }
                    }
                }

                if ($alreadyTaken == 1) {
                    $response = 1;
                    $message = $field_name;
                    $uniqueFieldsUsed = 1;

                    $error_data[$field_name] = $key['unique_warning'];
                }
            }

            $field_value_clean = "";

            //make null values if not set
            if (isset($_POST[$field_name])) {
                $field_value = $_POST[$field_name];
            } else {
                $field_value = null;
            }

            if ($key['api_trigger'] != 0) {
                $api_triggers[$key['api_trigger']] = $field_value;
            }

            if ($field_type == "text" or $field_type == "TEXT") {

                if (is_string($field_value)) {
                    $field_value_character_count = strlen($field_value);
                } else {
                    $field_value_character_count = 0;
                }

                if (($field_value_character_count < 1 or $field_value_character_count === null or $field_value === null or $field_value == "") && $required == 1) {
                    $errors = 1;
                    $response = 1;
                    $error_data[$field_name] = $key['error_label'];
                    $message = $key['error_label'];
                    if ($first_error_target === null) {
                        $first_error_target = "#ppat-" . $field_name;
                    }
                } else {

                    if ($field_value == "" or $field_value === null) {
                        $field_value_clean = "";
                    } else {
                        $field_value_clean = htmlspecialchars($field_value, ENT_QUOTES);
                    }
                }
            }

            if ($field_type == "hidden" or $field_type == "HIDDEN") {

                //count field characters
                if (is_string($field_value)) {
                    $field_value_character_count = strlen($field_value);
                } else {
                    $field_value_character_count = 0;
                }

                if (($field_value_character_count < 1 or $field_value_character_count === null) && $required == 1) {
                    $errors = 1;
                    $response = 1;
                    $error_data[$field_name] = $key['error_label'];
                    $message = $key['error_label'];
                    if ($first_error_target === null) {
                        $first_error_target = "#ppat-" . $field_name;
                    }
                } else {
                    $field_value_clean = htmlspecialchars($field_value, ENT_QUOTES);
                }
            }

            if ($field_type == "textarea" or $field_type == "TEXTAREA") {

                //count field characters
                if (is_string($field_value)) {
                    $field_value_character_count = strlen($field_value);
                } else {
                    $field_value_character_count = 0;
                }

                if (($field_value_character_count < 1 or $field_value_character_count === null) && $required == 1) {
                    $errors = 1;
                    $response = 1;
                    $error_data[$field_name] = $key['error_label'];
                    $message = $key['error_label'];
                    if ($first_error_target === null) {
                        $first_error_target = "#ppat-" . $field_name;
                    }
                } else {
                    $field_value_clean = htmlspecialchars($field_value, ENT_QUOTES);
                }
            }

            if ($field_type == "email" or $field_type == "EMAIL") {

                //count field characters
                if (is_string($field_value)) {
                    $field_value_character_count = strlen($field_value);
                } else {
                    $field_value_character_count = 0;
                }

                if ($field_value_character_count < 1 && $required == 1) {
                    $errors = 1;
                    $response = 1;
                    $error_data[$field_name] = $key['error_label'];
                    $message = $key['error_label'];
                    if ($first_error_target === null) {
                        $first_error_target = "#ppat-" . $field_name;
                    }
                } else {
                    if (filter_var($field_value, FILTER_VALIDATE_EMAIL)) {
                        $field_value_clean = htmlspecialchars($field_value, ENT_QUOTES);
                    } else {
                        $errors = 1;
                        $response = 1;
                        $error_data[$field_name] = "INVALID EMAIL";
                        $message = "INVALID EMAIL";
                        if ($first_error_target === null) {
                            $first_error_target = "#ppat-" . $field_name;
                        }
                    }
                }
            }

            if ($field_type == "checkbox" or $field_type == "CHECKBOX") {
                if ($field_value == "on") {
                    $field_value_clean = "yes";
                } else {
                    $field_value_clean = "no";
                }

                if ($field_value_clean == "no" && $required == 1) {
                    $errors = 1;
                    $response = 1;
                    $error_data[$field_name] = $key['error_label'];
                    $message = $key['error_label'];
                    if ($first_error_target === null) {
                        $first_error_target = "#ppat-" . $field_name;
                    }
                }
            }

            if ($field_type == "select" or $field_type == "SELECT") {
                if (($field_value == "PPAT-NONE" || $field_value == "" || $field_value === null) && $required == 1) {
                    $errors = 1;
                    $response = 1;
                    $error_data[$field_name] = $key['error_label'];
                    $message = $key['error_label'];
                    if ($first_error_target === null) {
                        $first_error_target = "#ppat-" . $field_name;
                    }
                } else {
                    $field_value_clean = $field_value;
                }
            }

            if ($field_type == "dropzone" or $field_type == "DROPZONE") {
                $run_dropzone = 1;
                $dropzone_required = $required;
                $dropzone_error_target = "#ppat-" . $field_name;
                $dropzone_field_name = $field_name;

                $dropzone_error_label = $key['error_label'];
            }

            $data[$field_name] = $field_value_clean;
        }

        // seperating dropzone so we don't need to keep re-rendering the images if other fields are not complete.
        //if ($run_dropzone == 1 && ($errors == 0)) {
        if ($run_dropzone == 1 && $uniqueFieldsUsed == 0) {

            $files_uploaded = 0;

            $tempfileres = $db->sql("SELECT * FROM ppat_temp_files WHERE session_id=? ORDER BY id", 's', $session);
            while ($tempfilerow = $tempfileres->fetch_assoc()) {
                $files_uploaded = 1;

                $temp_file_id = $tempfilerow['id'];
                $temp_file_name = $tempfilerow['the_file'];
                $extension = $tempfilerow['extension'];
                $original_filename = $tempfilerow['original_filename'];

                $file_to_use = $tempFolder . $temp_file_name . "." . $extension;
                $folder_to_save_too = $uploadsFolder . date("Y") . "/" . date('m') . "/" . date('d') . "/" . $session . "/";
                if (!file_exists($folder_to_save_too)) {
                    $old = umask(0);
                    mkdir($folder_to_save_too, 0777, true);
                    umask($old);
                }

                $db_full_file = date("Y") . "/" . date('m') . "/" . date('d') . "/" . $session . "/" . $temp_file_name . "." . $extension;
                $db_medium_file = date("Y") . "/" . date('m') . "/" . date('d') . "/" . $session . "/" . $temp_file_name . "_medium." . $extension;
                $db_thumbnail_file = date("Y") . "/" . date('m') . "/" . date('d') . "/" . $session . "/" . $temp_file_name . "_thumb." . $extension;

                $file_to_save_too = $folder_to_save_too . $temp_file_name . "." . $extension;
                $medium_to_save_too = $folder_to_save_too . $temp_file_name . "_medium." . $extension;
                $thumbnail_to_save_too = $folder_to_save_too . $temp_file_name . "_thumb." . $extension;
                $videoType = ['mp4', 'mov', 'wmv', 'avi', 'avchd', 'flv', 'f4v', 'swf', 'mkv', 'webm', 'html5', 'mpeg-2'];

                if (in_array($extension, $allowed_file_types_to_render)) {

                    $resize_thumb = new resize($file_to_use);
                    $resize_thumb->resizeImage(320, 320, 'crop');
                    $resize_thumb->saveImage($thumbnail_to_save_too, 70);

                    $resize_md = new resize($file_to_use);
                    $resize_md->resizeImage(800, 800, 'auto');
                    $resize_md->saveImage($medium_to_save_too, 70);

                    $resize_full = new resize($file_to_use);
                    $resize_full->resizeImage(3000, 3000, 'auto');
                    $resize_full->saveImage($file_to_save_too, 70);

                    $db->sql("INSERT INTO ppat_uploads SET session_id=?, full_file=?, medium_file=?, thumbnail_file=?, extension=?, original_filename=?, upload_time=?", 'ssssssi', $session, $db_full_file, $db_medium_file, $db_thumbnail_file, $extension, $original_filename, $current_time);
                } elseif (in_array($extension, $videoType)) { //video save
                    $thumbnail_to_save_video = $folder_to_save_too . $temp_file_name . "_thumb.png";
                    $db_thumbnail_video = date("Y") . "/" . date('m') . "/" . date('d') . "/" . $session . "/" . $temp_file_name . "_thumb.png";

                    //Generete video preview image
                    if (isset($_POST['upload_video_cover'])) {

                        $data64 = $_POST['upload_video_cover'];
                        list($type, $data64) = explode(';', $data64);
                        list(, $data64) = explode(',', $data64);
                        $data64 = base64_decode($data64);

                        file_put_contents($thumbnail_to_save_video, $data64);

                    }

                    $db->sql("INSERT INTO ppat_uploads SET session_id=?, full_file=?, medium_file=?, thumbnail_file=?, extension=?, original_filename=?, upload_time=?", 'ssssssi', $session, $db_full_file, "n/a", $db_thumbnail_video, $extension, $original_filename, $current_time);

                    copy($file_to_use, $file_to_save_too);
                } else {

                    $db->sql("INSERT INTO ppat_uploads SET session_id=?, full_file=?, medium_file=?, thumbnail_file=?, extension=?, original_filename=?, upload_time=?", 'ssssssi', $session, $db_full_file, "n/a", "n/a", $extension, $original_filename, $current_time);

                    copy($file_to_use, $file_to_save_too);
                }
            }

            if ($files_uploaded == 0 && $dropzone_required == 1) {
                $errors = 1;
                $response = 1;
                $error_data[$dropzone_field_name] = $dropzone_error_label;
                $message = $dropzone_error_label;
                if ($first_error_target === null) {
                    $first_error_target = $dropzone_error_target;
                }
            } else {

                $image_count = 0;
                $submitted_images = array();
                $newfileres = $db->sql("SELECT * FROM ppat_uploads WHERE session_id=? ORDER BY id", 's', $session);
                while ($newfilerow = $newfileres->fetch_assoc()) {

                    $submitted_images[$image_count] = $newfilerow['id'];
                    $image_count = $image_count + 1;
                }

                $data["dropbox"] = $submitted_images;
            }
        }

        if ($errors == 0 && $uniqueFieldsUsed == 0) {

            if ($qr_gen == 1) {

                $qr_dir = date("Y") . '/' . date("n") . '/' . date("j") . '/' . date("H") . '/';
                $qr_full_dir = $QRCache . $qr_dir;
                $qr_save_file = $qr_full_dir . $session . '.png';
                $qr_save_db = $qr_dir . $session . '.png';

                if (!file_exists($qr_full_dir)) {
                    mkdir($qr_full_dir, 0777, true);
                }

                $codeContents = $session;
                QRcode::png($codeContents, $qr_save_file, QR_ECLEVEL_L, 10);

                $data['qr_code'] = $qr_save_db;
            }

            $save_data = serialize($data);
            $db->sql("INSERT INTO ppat_submissions SET form_id=?, session_id=?, form_data=?, user_ip=?, cron_timer=?", 'isssi', $form_id, $session, $save_data, $user_ip, 0);
            // $db->sql("UPDATE ppat_2fa_codes_enforced SET validation_code=?, code_time=? WHERE user_email=?", 'sis', 'invalidated', $current_time, $user_email);

            $response = 2;
            $message = "SUCCESS";
        }
    } else {
        $response = 0;
        $message = "TOKEN ERROR! PLEASE REFRESH THE PAGE AND TRY AGAIN!";
    }
} else {
    $response = 0;
    $error_data = $message = "NO ID SET";
}

//$session="";
$return_arr = array(
    "response" => $response,
    "message" => $message,
    "session" => $session,
    "error_data" => $error_data,
    "to" => $first_error_target,
);

echo json_encode($return_arr);