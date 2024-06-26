<?php

if (is_malicious()) {

    $status = 0;
    $result['feedback'] = "PERMISSION DENIED!";
} else {

    $selectedImageData = array();
    $allFileData = array();
    $publicPostsData = array();

    //Do we neew ?
    $sql = "SELECT * FROM ppat_uploads WHERE status='1' AND form_id=? ORDER BY id DESC";
    $res = $db->sql($sql, 'i', $typea);
    while ($row = $res->fetch_assoc()) {

        $image_id = $row['id'];

        $access_token = getFileAccessToken($image_id);

        $image_full_file = $row['full_file'];
        $image_medium_file = $row['medium_file'];
        $image_thumbnail_file = $row['thumbnail_file'];
        $image_extension = $row['extension'];
        $image_original_filename  = $row['original_filename'];
        $image_upload_time  = $row['upload_time'];

        $image_status  = $row['status'];

        $postimage['id'] = $image_id;
        $postimage['url'] = STATIC_URL . $image_id . "_full." . $image_extension . "?token=" . $access_token;
        $postimage['fileName'] = $image_original_filename;

        if (
            $image_extension == "jpg"
            || $image_extension == "jpeg"
            || $image_extension == "png"
        ) {
            $postimage['thumburl'] = STATIC_URL . $image_id . "_thumb." . $image_extension . "?token=" . $access_token;
            $postimage['medium'] = STATIC_URL . $image_id . "_medium." . $image_extension . "?token=" . $access_token;
            $postimage['image_status'] = $image_status;
            $selectedImageData[] = $postimage;
        } else {
            $allFileData[] = $postimage;
        }
    }


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

                $image_id = $row2['id'];

                $access_token = getFileAccessToken($image_id);

                $image_full_file = $row2['full_file'];
                $image_medium_file = $row2['medium_file'];
                $image_thumbnail_file = $row2['thumbnail_file'];
                $image_extension = $row2['extension'];
                $image_original_filename  = $row2['original_filename'];
                $image_upload_time  = $row2['upload_time'];

                $image_status  = $row2['status'];

                $postimage['id'] = $image_id;
                $postimage['url'] = STATIC_URL . $image_id . "_full." . $image_extension . "?token=" . $access_token;
                $postimage['fileName'] = $image_original_filename;


                if (
                    $image_extension == "jpg"
                    || $image_extension == "jpeg"
                    || $image_extension == "png"
                ) {
                    $postimage['thumburl'] = STATIC_URL . $image_id . "_thumb." . $image_extension . "?token=" . $access_token;
                    $postimage['medium'] = STATIC_URL . $image_id . "_medium." . $image_extension . "?token=" . $access_token;
                    $postimage['image_status'] = $image_status;
                    $postimages[] = $postimage;
                }
            }
        }

        $thisPostData["images"] = $postimages;
        $publicPostsData[] = $thisPostData;
    }




    $status = 1;
    //$result["selectedImageData"] = $selectedImageData;
    //$result["allFileData"] = $allFileData;
    $result["publicPostsData"] = $publicPostsData;
}



$result["status"] = $status;
