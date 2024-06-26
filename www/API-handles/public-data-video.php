<?php

if (is_malicious()) {

    $status = 0;
    $result['feedback'] = "PERMISSION DENIED!";
} else {

    $selectedImageData = array();
    $allFileData = array();
    $publicPostsData = array();

    $sql = "SELECT * FROM ppat_uploads WHERE status='1' AND form_id=? ORDER BY id DESC";
    $res = $db->sql($sql, 'i', $typea);
 
  
    // while ($row = $res->fetch_assoc()) {
    //     $access_token = getFileAccessToken($row['id']);
    //     $row['full_file'] = $row['full_file']."?token=".$access_token;
    //     $postvideo[] = $row;
    //     $selectedImageData[] = $postvideo;
    // }
 
    $publicFieldKeys = array();
    $sql = "SELECT * FROM ppat_form_fields WHERE form_id=? AND public=? ORDER BY id DESC";
    $res = $db->sql($sql, 'ii', $typea, 1);
    while ($row = $res->fetch_assoc()) {
        $publicFieldKeys[] = $row['field_name'];
    }


  

    $publicToggleActionNumbers = array();
    $publicFilterArgs = "";
    $filterArgAdded = 0;
    $sql = "SELECT * FROM ppat_entry_actions WHERE form_id=? ORDER BY id DESC";
    $res = $db->sql($sql, 'i', $typea);
    while ($row = $res->fetch_assoc()) {
        if ($row['public'] == 1) {

            if ($row['action_number'] == 1) {
                if ($filterArgAdded == 1) {
                    $thisAndOr = " OR";
                } else {
                    $thisAndOr = "";
                }
                $publicFilterArgs = $publicFilterArgs . $thisAndOr . " action_1 = 1";
                $filterArgAdded = 1;
            }

            if ($row['action_number'] == 2) {
                if ($filterArgAdded == 1) {
                    $thisAndOr = " OR";
                } else {
                    $thisAndOr = "";
                }
                $publicFilterArgs = $publicFilterArgs . $thisAndOr . " action_2 = 1";
                $filterArgAdded = 1;
            }

            if ($row['action_number'] == 3) {
                if ($filterArgAdded == 1) {
                    $thisAndOr = " OR";
                } else {
                    $thisAndOr = "";
                }
                $publicFilterArgs = $publicFilterArgs . $thisAndOr . " action_3 = 1";
                $filterArgAdded = 1;
            }

            if ($row['action_number'] == 4) {
                if ($filterArgAdded == 1) {
                    $thisAndOr = " OR";
                } else {
                    $thisAndOr = "";
                }
                $publicFilterArgs = $publicFilterArgs . $thisAndOr . " action_4 = 1";
                $filterArgAdded = 1;
            }

            if ($row['action_number'] == 5) {
                if ($filterArgAdded == 1) {
                    $thisAndOr = " OR";
                } else {
                    $thisAndOr = "";
                }
                $publicFilterArgs = $publicFilterArgs . $thisAndOr . " action_5 = 1";
                $filterArgAdded = 1;
            }

            if ($row['action_number'] == 6) {
                if ($filterArgAdded == 1) {
                    $thisAndOr = " OR";
                } else {
                    $thisAndOr = "";
                }
                $publicFilterArgs = $publicFilterArgs . $thisAndOr . " action_6 = 1";
                $filterArgAdded = 1;
            }

            if ($row['action_number'] == 7) {
                if ($filterArgAdded == 1) {
                    $thisAndOr = " OR";
                } else {
                    $thisAndOr = "";
                }
                $publicFilterArgs = $publicFilterArgs . $thisAndOr . " action_7 = 1";
                $filterArgAdded = 1;
            }

            if ($row['action_number'] == 8) {
                if ($filterArgAdded == 1) {
                    $thisAndOr = " OR";
                } else {
                    $thisAndOr = "";
                }
                $publicFilterArgs = $publicFilterArgs . $thisAndOr . " action_8 = 1";
                $filterArgAdded = 1;
            }

            if ($row['action_number'] == 9) {
                if ($filterArgAdded == 1) {
                    $thisAndOr = " OR";
                } else {
                    $thisAndOr = "";
                }
                $publicFilterArgs = $publicFilterArgs . $thisAndOr . " action_9 = 1";
                $filterArgAdded = 1;
            }

            if ($row['action_number'] == 10) {
                if ($filterArgAdded == 1) {
                    $thisAndOr = " OR";
                } else {
                    $thisAndOr = "";
                }
                $publicFilterArgs = $publicFilterArgs . $thisAndOr . " action_10 = 1";
                $filterArgAdded = 1;
            }
        }
    }
    
 
    $publicFilterArgs = " action_3 = 0 OR action_1 = 0";
    $sql = "SELECT * FROM ppat_submissions WHERE form_id=? AND ($publicFilterArgs) ORDER BY id DESC";

    if ($publicFilterArgs !== null && $publicFilterArgs != "") {
        $res = $db->sql($sql, 'i', $typea);
    }

  
    while ($row = $res->fetch_assoc()) {
        
        $entry_id = $row['id'];
        $entry_data = $row['form_data'];
        $entry_data = unserialize($entry_data);

        $override_data = $row['override_data'];
        if ($override_data !== null && $override_data != "") {
            $override_data = unserialize($override_data);
        } else {
            $override_data = array();
        }


        $postfiles = array();
        $thisPostData = array();
        $postimages = array();

 

        $thisPostData['a_1'] = $row['action_1'];
        $thisPostData['a_2'] = $row['action_2'];
        $thisPostData['a_3'] = $row['action_3'];
        $thisPostData['a_4'] = $row['action_4'];
        $thisPostData['a_5'] = $row['action_5'];
        $thisPostData['a_6'] = $row['action_6'];
        $thisPostData['a_7'] = $row['action_7'];
        $thisPostData['a_8'] = $row['action_8'];
        $thisPostData['a_9'] = $row['action_9'];
        $thisPostData['a_10'] = $row['action_10'];

         
        foreach ($publicFieldKeys as $key) {

            if (isset($override_data[$key])) {
                
                $thisPostData[$key] = $override_data[$key];
            } else {

                if (isset($entry_data[$key])) {
                    $thisPostData[$key] = $entry_data[$key];
                }
            }
        }

        $files = null;
        if (isset($entry_data["dropbox"])) {
            $files = $entry_data["dropbox"];
        }
 

        if ($files !== null) {
            
            $sql2 = "SELECT * FROM ppat_uploads WHERE status='1' AND id IN (" . implode(",", array_map('intval', $files)) . ") ORDER BY id ASC";
            $res2 = $db->sql($sql2);
         
            while ($row2 = $res2->fetch_assoc()) { 
              
                if($row2['status'] === 1 ){
                    $fileId = $row2['id'];
                    $file_extension = $row2['extension'];
                    $access_token = getFileAccessToken($fileId);
                  
                    $row2['full_file'] = STATIC_URL .  $fileId . "_full." . $file_extension . "?token=" . $access_token;
                    $row2['thumbnail_file'] = STATIC_URL . $fileId . "_thumb.png?token=" . $access_token;
                    
                    $postimages[] = $row2;
                }
                
 
            }
        }

        if(!empty($postimages)) {
            $thisPostData["images"] = $postimages;
            $publicPostsData[] = $thisPostData;
        }

 
        
    }

   



    $status = 1;
    //$result["selectedImageData"] = $selectedImageData;
    //$result["allFileData"] = $allFileData;
    $result["publicPostsData"] = $publicPostsData;
}



$result["status"] = $status;
