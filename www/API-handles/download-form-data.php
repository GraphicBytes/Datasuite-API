<?php
if (is_malicious()) {

    log_malicious();
    $result['status'] = 0;
    $result['feedback'] = "INVALID REQUEST!";
} else {

    $status = 0;

    if (!empty($userGroupsAll)) {

        $userAllowed = 0;

        $fileOutput = '"ID"';

        $sql = "SELECT * FROM ppat_forms WHERE user_group IN (" . implode(",", array_map('intval', $userGroupsAll)) . ") AND id='$typea'";
        $res = $db->sql($sql);
        while ($row = $res->fetch_assoc()) {
            $formID = $row['id'];
            $userAllowed = 1;
        }

        if ($userAllowed == 1) {

            $status = 1;

            $fieldKeys = $_POST['fieldKeys'];
            $fieldKeysExloaded = explode(",", $fieldKeys);
            $exportKeyArray = array();

            $sql = "SELECT * FROM ppat_form_fields WHERE field_name IN ('" . str_replace(",", "','", $fieldKeys) . "') AND form_id='$formID' ORDER BY field_order ASC";
            $res = $db->sql($sql);
            while ($row = $res->fetch_assoc()) {
                $fileOutput = $fileOutput . ',"' . $row['short_label'] . '"';

                $exportKeyArray[] = $row['field_name'];
            }

            $timeSubHeader = "";
            $QRSubHeader = "";
            $actionHeaders = "";

            foreach ($fieldKeysExloaded as $thiskey => $thisvalue) {

                if (str_contains($thisvalue, 'dsdata___')) {

                    $thisFieldKey = str_replace("dsdata___", "", $thisvalue);

                    if ($thisFieldKey == "system_submission_time") {

                        $QRSubHeader = $QRSubHeader . ',"' . "QR Image URL" . '"';

                    } else if ($thisFieldKey == "qr_code_file_url") {

                        $timeSubHeader = $timeSubHeader . ',"' . "Submission Time" . '"';

                    } else {

                        $sql = "SELECT * FROM ppat_entry_actions WHERE form_id = ? AND action_number = ?";
                        $res = $db->sql($sql, "is", $formID, $thisFieldKey);
                        while ($row = $res->fetch_assoc()) {

                            $actionHeaders = $actionHeaders . ',"' . $row['action_label'] . '"';
                        }
                    }
                }
            }
            $fileOutput = $fileOutput . $actionHeaders . $QRSubHeader . $timeSubHeader;

            $fileOutput = $fileOutput . "\r\n";


            $sql = "SELECT * FROM ppat_submissions WHERE form_id = ?";
            $res = $db->sql($sql, "i", $formID);
            while ($row = $res->fetch_assoc()) {

                $entryID = $row['id'];
                $thisFormData = unserialize($row['form_data']);

                $form_override_data = $row['override_data'];
                if ($form_override_data !== NULL) {
                    $form_override_data = unserialize($form_override_data);
                }

                $fileOutput = $fileOutput . '"' . $entryID . '"';

                foreach ($exportKeyArray as $key) {

                    if (isset($thisFormData[$key])) {

                        if (isset($form_override_data[$key])) {
                            $thisValue = htmlspecialchars_decode($form_override_data[$key]);
                        } else {
                            $thisValue = htmlspecialchars_decode($thisFormData[$key]);
                        }

                        $thisValue = str_replace('"', "'", $thisValue);

                        $fileOutput = $fileOutput . ',"' . $thisValue . '"';
                    } else {
                        $fileOutput = $fileOutput . ',""';
                    }
                }

                $timeContent = "";
                $QRContent = "";
                $actionValues = "";

                foreach ($fieldKeysExloaded as $thiskey => $thisvalue) {

                    if (str_contains($thisvalue, 'dsdata___')) {

                        $thisKey = str_replace("dsdata___", "", $thisvalue);

                        if ($thisKey == "system_submission_time") {
                            $timeContent = $timeContent . ',"' . $row['submission_time'] . '"';
                        } else if ($thisKey == "qr_code_file_url") {
                            $QRContent = $QRContent . ',"' . STATIC_URL . "QR_Cache/" . $thisFormData['qr_code'] . '"';
                        } else {
                            $actionValues = $actionValues . ',"' . $row['action_' . $thisKey] . '"';
                        }
                    }
                }



                $fileOutput = $fileOutput . $actionValues . $QRContent . $timeContent . "\r\n";
            }
        }


        $exportFileName = "data-export-" . date("j") . "-" . date("n") . "-" . date("Y") . "-" . $formID . "-" . $logged_in_id . "-" . random_str(10) . ".csv";

        $exportDirectory = date("Y") . '/' . date("n") . '/';
        $exportFullDirectory = "./" . $ExportCache . $exportDirectory;
        $exportFullFile = $exportFullDirectory . $exportFileName;

        $dbPath = str_replace($uploadsFolder, "", $ExportCache . $exportDirectory . $exportFileName);

        if (!file_exists($exportFullDirectory)) {
            mkdir($exportFullDirectory, 0777, true);
        }

        file_put_contents($exportFullFile, $fileOutput);

        $randString = random_str(50);

        $db->sql(
            "INSERT INTO ppat_uploads SET session_id=?, full_file=?, extension=?, original_filename=?, upload_time=?",
            "ssssi",
            $randString,
            $dbPath,
            "csv",
            $exportFileName,
            $current_time
        );


        $sql = "SELECT * FROM ppat_uploads WHERE session_id='$randString'";
        $res = $db->sql($sql);
        while ($row = $res->fetch_assoc()) {
            $SavedFileID = $row['id'];
            $SavedFilePath = $row['full_file'];
        }

        $access_token = getFileAccessToken($SavedFileID);


        $result['download'] = STATIC_URL . $SavedFileID . "_full.csv?token=" . $access_token;
    }
}



$result['status'] = $status;
