<?php
function getFileAccessToken($fileID){
    
    global $db;
    global $crypt;
    global $logged_in_id;
    global $request_time;
 

    $res = $db->sql("SELECT * FROM ppat_uploads WHERE id='$fileID'");
    
    while ($row = $res->fetch_assoc()) {
       
        $row;
        
        $access_token = random_str(7);

        $test = $db->sql("INSERT INTO file_access_tokens SET user_id=?, file_id=?, access_token=?, token_age=?", "iisi", $logged_in_id, $fileID, $access_token, $request_time); 
        
        $access_token = $crypt->encrypt($access_token);
        $access_token = str_replace("=","_1", $access_token);
        $access_token = str_replace("+", "_2", $access_token);
        $access_token = str_replace("/", "_3", $access_token);
        $access_token = str_replace(".", "_4", $access_token);

        
    }
    
   

    return $access_token;

}   