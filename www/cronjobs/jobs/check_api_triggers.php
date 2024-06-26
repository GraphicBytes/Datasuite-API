<?php

$apiPushed = 0;
$apiPushPerMinute = 3;
$submissionsToCheckPerCron = 100;


// pull in all conditionals
$apiConditionalsSql = $db->sql("SELECT * FROM ppat_api_conditionals");
$apiConditionals = array();
while ($apiConditionalsRow = $apiConditionalsSql->fetch_assoc()) {
    $apiConditionals[] = $apiConditionalsRow;
}

//format: 0,0,0,0,0,0,0,0,0,0
//format: action_1,action_2,action_3,action_4,action_5,action_6,action_7,action_8,action_9,action_10
//Zero values are ignored


$ApiTriggersSql = $db->sql("SELECT * FROM ppat_api_triggers");
$apiTriggers = array();
while ($ApiTriggersRow = $ApiTriggersSql->fetch_assoc()) {
    $apiTriggers[$ApiTriggersRow['id']] = $ApiTriggersRow;
}



$res = $db->sql("SELECT * FROM ppat_submissions WHERE apis_complete = 0 ORDER BY cron_timer ASC LIMIT ?", "i", $submissionsToCheckPerCron);
while ($row = $res->fetch_assoc()) {

    

    if ($apiPushed < $apiPushPerMinute) {

        // set initial values
        $totalConditionalsCount = 0;
        $totalConditionalsMet = 0;
        $conditionsAlreadyMetFetched = 0;
        $conditionsAlreadyMet = null;
        $trackerRecordFound = 0;

        $subID = $row['id'];
        $formID = $row['form_id'];

        $formData = unserialize($row['form_data']);

        $actionValue_1 = $row['action_1'];
        $actionValue_2 = $row['action_2'];
        $actionValue_3 = $row['action_3'];
        $actionValue_4 = $row['action_4'];
        $actionValue_5 = $row['action_5'];
        $actionValue_6 = $row['action_6'];
        $actionValue_7 = $row['action_7'];
        $actionValue_8 = $row['action_8'];
        $actionValue_9 = $row['action_9'];
        $actionValue_10 = $row['action_10'];
        $actionValue_11 = $row['action_11'];
        $actionValue_12 = $row['action_12'];
        $actionValue_13 = $row['action_13'];
        $actionValue_14 = $row['action_14'];
        $actionValue_15 = $row['action_15'];       

        foreach ($apiConditionals as $conditionKey => $conditionValue) {

            $thisConditionID = $apiConditionals[$conditionKey]['id'];

            if ($apiConditionals[$conditionKey]['form_id'] == $formID) {

                $totalConditionalsCount = $totalConditionalsCount + 1;

                $WhenModerationEquals = $apiConditionals[$conditionKey]['when_moderation_equals'];
                $whenFieldValuesEqual = $apiConditionals[$conditionKey]['when_field_values_equal'];
                $apiTriggerId = $apiConditionals[$conditionKey]['when_field_values_equal'];

                $whenModerationEqualsExploded = explode(",", $WhenModerationEquals);

                $moderationConditionsMet = 1;

                if (
                    ($whenModerationEqualsExploded[0] == 1 && $actionValue_1 == 0)
                    || ($whenModerationEqualsExploded[1] == 1 && $actionValue_2 == 0)
                    || ($whenModerationEqualsExploded[2] == 1 && $actionValue_3 == 0)
                    || ($whenModerationEqualsExploded[3] == 1 && $actionValue_4 == 0)
                    || ($whenModerationEqualsExploded[4] == 1 && $actionValue_5 == 0)
                    || ($whenModerationEqualsExploded[5] == 1 && $actionValue_6 == 0)
                    || ($whenModerationEqualsExploded[6] == 1 && $actionValue_7 == 0)
                    || ($whenModerationEqualsExploded[7] == 1 && $actionValue_8 == 0)
                    || ($whenModerationEqualsExploded[8] == 1 && $actionValue_9 == 0)
                    || ($whenModerationEqualsExploded[9] == 1 && $actionValue_10 == 0)
                    || ($whenModerationEqualsExploded[10] == 1 && $actionValue_11 == 0)
                    || ($whenModerationEqualsExploded[11] == 1 && $actionValue_12 == 0)
                    || ($whenModerationEqualsExploded[12] == 1 && $actionValue_13 == 0)
                    || ($whenModerationEqualsExploded[13] == 1 && $actionValue_14 == 0)
                    || ($whenModerationEqualsExploded[14] == 1 && $actionValue_15 == 0)
                ) {
                    $moderationConditionsMet = 0;
                }


                if ($moderationConditionsMet == 1) {

                    if($whenFieldValuesEqual !== null){

                        $whenFieldValuesEqual = unserialize($whenFieldValuesEqual);

                        foreach ($whenFieldValuesEqual as $formDataKey => $fieldValue) {

                            if ($fieldValue != $formData[$formDataKey]) {
                                $moderationConditionsMet = 0;
                            } 
                        }
                    }
                }

                if ($moderationConditionsMet == 1) {

                    
                    // check for tracker records
                    if ($conditionsAlreadyMetFetched == 0) {

                        $conditionsTrackerSql = $db->sql("SELECT * FROM ppat_api_conditional_tracker WHERE submission_id=?", "i", $subID);
                        while ($conditionsTrackerRow = $conditionsTrackerSql->fetch_assoc()) {

                            $trackerRecordFound = 1;
                            $conditionsAlreadyMetRaw = $conditionsTrackerRow['conditions_met'];
                            $conditionsAlreadyMet = explode(",", $conditionsAlreadyMetRaw);
                        }

                        $conditionsAlreadyMetFetched == 1;
                    }


                    //check if trigger has already fired
                    $alreadyTriggered = 0;
                    if ($trackerRecordFound == 1) {
                        foreach ($conditionsAlreadyMet as $trackerRecordKey => $trackerRecordValue) {

                            if ($trackerRecordValue == $thisConditionID) {
                                $alreadyTriggered = 1;
                            }

                        }
                    }


                    if ($alreadyTriggered == 0) {

                        $triggerID = $apiConditionals[$conditionKey]['api_trigger_id'];
                        $ApiTriggerData = $apiTriggers[$triggerID];

                        $apiType = $ApiTriggerData['api_type'];

                        $api_username_crpt = $ApiTriggerData['api_username'];
                        $api_password_crpt = $ApiTriggerData['api_password'];
                        $api_email_data_key = $ApiTriggerData['email_data_key'];
                        $api_full_name_data_key = $ApiTriggerData['full_name_data_key'];
                        $api_first_name_data_key = $ApiTriggerData['first_name_data_key'];
                        $api_last_name_data_key = $ApiTriggerData['last_name_data_key'];
                        $api_qr_path_target = $ApiTriggerData['qr_path_target'];
                        $api_field_pairs = $ApiTriggerData['api_field_pairs'];
                        $main_adestra_workspace = $ApiTriggerData['adestra_workspace'];
                        $main_adestra_list = $ApiTriggerData['adestra_list'];
                        $main_dd_addr_book_id = $ApiTriggerData['dd_addr_book_id'];
                        $data = $formData;
                        

                        if ($apiType == "adestra") {
                            include('_trigger_handlers/adestra.php');
                        }

                        if ($apiType == "dotdigital") {
                            include('_trigger_handlers/dotdigital.php');
                        }

                        if($trackerRecordFound == 1){

                            $conditionsAlreadyMetUpdate = $conditionsAlreadyMetRaw . "," . $thisConditionID;
                            $db->sql("UPDATE ppat_api_conditional_tracker SET conditions_met=? WHERE submission_id = ?", "si", $conditionsAlreadyMetUpdate, $subID);

                        } else {

                            $db->sql("INSERT INTO ppat_api_conditional_tracker SET submission_id=?, conditions_met=?", "is", $subID, $thisConditionID);
                        }


                        
                        $db->sql("UPDATE ppat_options SET meta_value=meta_value+1 WHERE meta_key='adestra_apis_pushed'");
                        $apiPushed = $apiPushed + 1;
                    } 

                    $totalConditionalsMet = $totalConditionalsMet + 1;
                    
                }
            }
        }


        if ($totalConditionalsMet >= $totalConditionalsCount) {
            // spare DB by removing this submission from the loop
            $db->sql("UPDATE ppat_submissions SET cron_timer=?, apis_complete=1 WHERE id=?", "ii", $current_time,  $row['id']);
        } else {
            //bump to the back of the queue
            $db->sql("UPDATE ppat_submissions SET cron_timer=? WHERE id=?", "ii", $current_time,  $row['id']);
        }
        
    }

    //echo $row['id'] . " " . $totalConditionalsMet . " " . $totalConditionalsCount . "<br />";
}


//print_r($apiConditionals);