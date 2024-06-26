<?php

$deleteCutTriggerTime = $request_time - 3600;

$deleteTriggersSql = $db->sql("SELECT * FROM ppat_entry_actions WHERE delete_trigger=1");
while ($deleteTriggersRow = $deleteTriggersSql->fetch_assoc()) {

    $formID = $deleteTriggersRow['form_id'];
    $actionNumber = $deleteTriggersRow['action_number'];

    $checkSQL = "SELECT * FROM ppat_submissions WHERE form_id=" . $formID . " AND action_" . $actionNumber .  "=1 AND last_mod_time<" . $deleteCutTriggerTime . " ORDER BY ID ASC";

    $doDeleteSql = $db->sql($checkSQL);
    while ($doDeleteRow = $doDeleteSql->fetch_assoc()) {

        $subID = $doDeleteRow['id'];

        $form_data = unserialize($doDeleteRow['form_data']);

        $formHasDropzone = 0;
        // Does it do drop zone
        $res = $db->sql("SELECT * FROM ppat_form_fields WHERE form_id=? AND field_type='dropzone'", "i", $formID);
        while ($row = $res->fetch_assoc()) {

            $formHasDropzone = 1;
            $dropZoneFieldID = $row['field_name'];
            $dropZoneData = $form_data[$dropZoneFieldID];
        }

        $formHasQR = 0;
        // Does it do QR
        $res = $db->sql("SELECT * FROM ppat_forms WHERE id=?", "i", $formID);
        while ($row = $res->fetch_assoc()) {
            $formHasQR = $row['qr_gen'];
            if ($formHasQR == 1) {

                $qrCodeFile = "./uploads/QR_Cache/" . $form_data['qr_code'];

                if (file_exists($qrCodeFile)) {
                    unlink($qrCodeFile);
                }
            }  
        }


        if ($formHasDropzone == 1) {
            if (isset($form_data['dropbox'])) {

                $uploads = $form_data['dropbox'];

                foreach ($uploads as $uploadsKey => $uploadsValue) {

                    $uploadID = $uploadsValue;

                    $res = $db->sql("SELECT * FROM ppat_uploads WHERE id=?", "i", $uploadID);
                    while ($row = $res->fetch_assoc()
                    ) {
                        $fullFilePath = $row['full_file'];
                        $mediumFilePath = $row['medium_file'];
                        $thumbnailFilePath = $row['thumbnail_file'];


                        if (file_exists( "./uploads/" . $fullFilePath)) {
                            unlink("./uploads/" . $fullFilePath);
                        }
                        if (file_exists("./uploads/" . $mediumFilePath)) {
                            unlink("./uploads/" . $mediumFilePath);
                        }
                        if (file_exists("./uploads/" . $thumbnailFilePath)) {
                            unlink("./uploads/" . $thumbnailFilePath);
                        }

                        $db->sql("DELETE FROM ppat_uploads WHERE id=?", "i", $uploadID);
                        $db->sql("DELETE FROM file_access_tokens WHERE file_id=?", "i", $uploadID);
                        $db->sql("DELETE FROM ppat_api_conditional_tracker WHERE submission_id=?", "i", $uploadID);
                        $db->sql("DELETE FROM ppat_entry_comments WHERE entry_id=?", "i", $uploadID);

                    }
                }
            }
        }

        $db->sql("DELETE FROM ppat_submissions WHERE id=?", "i", $subID);
    }


   


}
