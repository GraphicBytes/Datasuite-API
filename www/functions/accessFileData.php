<?php
function accessFileData($imageID){
    
    global $db;
    global $crypt;

    $fileData=array();

    $res = $db->sql("SELECT * FROM ppat_uploads WHERE id='$imageID'");
    while ($row = $res->fetch_assoc()) {


        if ($row['access_token'] === null || $row['access_token'] == "") {
            $access_token = random_str(7);
            $db->sql("UPDATE ppat_uploads SET access_token='$access_token' WHERE id='$imageID'");            
        } else {
            $access_token = $row['access_token'];
        }

        $access_token = $crypt->encrypt($access_token);
        $access_token = str_replace("=","_1", $access_token);
        $access_token = str_replace("+", "_2", $access_token);
        $access_token = str_replace("/", "_3", $access_token);
        $access_token = str_replace(".", "_4", $access_token);

        
        $fileData['full_file'] = $row['full_file'];
        $fileData['medium_file'] = $row['medium_file'];
        $fileData['thumbnail_file'] = $row['thumbnail_file'];
        $fileData['extension'] = $row['extension'];
        $fileData['original_filename'] = $row['original_filename'];
        $fileData['upload_time'] = $row['upload_time'];
        $fileData['access_token'] = $access_token;

    }
    

    return $fileData;

}   