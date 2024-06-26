<?php

$type = $request = $_SERVER['REQUEST_URI'];
$request = strstr($request, '?', true);
$request = str_replace("/", "", $request);
$request = strstr($request, '_', true);

$QR = 0;
if (str_contains($type, '/QR_Cache/')) {
    $QR = 1;
    $QrPath = $type;
}

$tokenSet = 0;
if (str_contains($type, '?token=')) {

    $tokenSet = 1;

    $tokenCheck = substr($fullURL, strrpos($fullURL, '?token=') + 1);
    $access_token = str_replace("token=", "", $tokenCheck);

    $type = $request = $_SERVER['REQUEST_URI'];
    $request = strstr($request, '?', true);
    $request = str_replace("/", "", $request);
    $request = strstr($request, '_', true);

}

if ($tokenSet == 1 && $QR == 0) {

    $token_length = strlen($access_token);

    if ($token_length << 50) {

        $access_token = str_replace("_1", "=", $access_token);
        $access_token = str_replace("_2", "+", $access_token);
        $access_token = str_replace("_3", "/", $access_token);
        $access_token = str_replace("_4", ".", $access_token);
        $access_token = $crypt->decrypt($access_token);

        if (!empty($access_token)) {
            $raw_access_token_length = strlen($access_token);
        } else {
            $raw_access_token_length = 0;
        }

        if ($raw_access_token_length == 7) {

            $token_age_limit = $current_time - 3600;

            $type = strstr($type, '.', true);
            $type = str_replace("/", "", $type);
            $type = str_replace($request, "", $type);
            $type = str_replace("_", "", $type);

            $valid_token = 0;
            $res = $db->sql("SELECT * FROM file_access_tokens WHERE file_id=? AND access_token=? AND (token_age > ? OR permanent=? ) ORDER BY id DESC", 'isii', $request, $access_token, $token_age_limit, 1);
            while ($row = $res->fetch_assoc()) {
                $valid_token = 1;
            }

            if ($valid_token == 1) {

                $file_found = 0;
                $res = $db->sql("SELECT * FROM ppat_uploads WHERE id=?", "i", $request);
                while ($row = $res->fetch_assoc()) {
                    $file_found = 1;

                    $thumbnail_file = $row['thumbnail_file'];
                    $medium_file = $row['medium_file'];
                    $full_file = $row['full_file'];
                }

                if ($file_found == 1) {

                    if ($type == "thumb") {
                        $file = $thumbnail_file;
                    }
                    if ($type == "medium") {
                        $file = $medium_file;
                    }
                    if ($type == "full") {
                        $file = $full_file;
                    }

                    $mime_type = get_mime_type($file);
                    $full_path = "uploads/" . $file;

                    $content_type = 'Content-Type:' . $mime_type;
                    $filesize = filesize($full_path);

                    header($content_type);
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate');
                    header('Pragma: public');
                    header('Content-Length: ' . $filesize);
                    readfile($full_path);

                    //echo $full_path;

                } else {
                    echo "FILE DOES NOT EXIST";
                }
            } else {
                echo "EXPIRED TOKEN";
            }
        } else {
            echo "ACCESS DENIED";
        }
    } else {
        echo "ACCESS DENIED";
    }
} else if ($QR == 1) {

    $mime_type = get_mime_type($QrPath);
    $content_type = 'Content-Type:' . $mime_type;

    if (!file_exists("uploads" . $QrPath)) {

        $qrCodeFile = basename($QrPath);
        $qrCodeString = basename($QrPath, ".png");

        $shouldBeThere = 0;

        $shouldBeThereRes = $db->sql("SELECT id, session_id FROM ppat_submissions WHERE session_id=? ORDER BY id", 's', $qrCodeString);
        while ($tempfilerow = $shouldBeThereRes->fetch_assoc()) {
            $shouldBeThere = 1;
        }

        if ($shouldBeThere == 1) {
            $folderToSaveIn = "uploads" . str_replace($qrCodeFile, "", $QrPath);
            $placeToSaveIn = $folderToSaveIn . basename($QrPath);

            if (!file_exists($folderToSaveIn)) {
                mkdir($folderToSaveIn, 0777, true);
            }

            QRcode::png($qrCodeString, $placeToSaveIn, QR_ECLEVEL_L, 10);
        } else {
            echo "ACCESS DENIED";
            die();
        }

    }

    header($content_type);
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    readfile($_SERVER['DOCUMENT_ROOT'] . "/uploads" . $QrPath);

} else {
    echo "ACCESS DENIED";
}
