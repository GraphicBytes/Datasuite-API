<?php



use Adestra\Create;
use Adestra\Update;




Autoloader::register();

$main_user_email_field_name = $api_email_data_key;
$main_user_first_name_field_name = $api_first_name_data_key ;
$main_user_last_name_field_name = $api_last_name_data_key;
$main_user_full_name_field_name = $api_full_name_data_key;

$api_email_address = $data[$main_user_email_field_name];

if (isset($data[$main_user_first_name_field_name])) {
    $api_first_name = $data[$main_user_first_name_field_name];
} else {
    $api_first_name = "";
}

if (isset($data[$main_user_last_name_field_name])) {
    $api_last_name = $data[$main_user_last_name_field_name];
} else {
    $api_last_name = "";
}

if (isset($data[$main_user_full_name_field_name])) {
    $api_full_name = $data[$main_user_full_name_field_name];
} else {
    $api_full_name = "";
}


if (isset($data['qr_code']) && $api_qr_path_target !== null) {
    $api_qr_path = STATIC_URL . "QR_Cache/" . $data['qr_code'];
    $api_qr_path = str_replace("https://", "", $api_qr_path);
    $api_qr_path = str_replace("http://", "", $api_qr_path);
} else {
    $api_qr_path = null;
}


$extraData = array();

if($api_field_pairs !== null){

    $api_field_pairs = unserialize($api_field_pairs);

    foreach ($api_field_pairs as $key => $value) {

        $extraData[$key] = $data[$value];

    }

}




if ($actionValue_1 == 1) {

    $thisActionID = 0;

    $actionValuesSQL = $db->sql("SELECT * FROM ppat_forms WHERE id=?", "i", $formID);
    while ($actionValuesRow = $actionValuesSQL->fetch_assoc()) {
        $thisActionID = $actionValuesRow['act1_id'];
    }

    if ($thisActionID != 0) {
        $actionValuesSQL = $db->sql("SELECT * FROM ppat_entry_actions WHERE id=?", "i", $thisActionID);
        while ($actionValuesRow = $actionValuesSQL->fetch_assoc()) {
            $thisActionID = $actionValuesRow['id'];

            $extraData[$actionValuesRow['api_trigger_field_name']] = $actionValuesRow['api_trigger_value'];
        }
    }
}

if ($actionValue_2 == 1) {

    $thisActionID = 0;

    $actionValuesSQL = $db->sql("SELECT * FROM ppat_forms WHERE id=?", "i", $formID);
    while ($actionValuesRow = $actionValuesSQL->fetch_assoc()) {
        $thisActionID = $actionValuesRow['act2_id'];
    }

    if ($thisActionID != 0) {
        $actionValuesSQL = $db->sql("SELECT * FROM ppat_entry_actions WHERE id=?", "i", $thisActionID);
        while ($actionValuesRow = $actionValuesSQL->fetch_assoc()) {
            $thisActionID = $actionValuesRow['id'];

            $extraData[$actionValuesRow['api_trigger_field_name']] = $actionValuesRow['api_trigger_value'];
        }
    }
}

if ($actionValue_3 == 1) {

    $thisActionID = 0;

    $actionValuesSQL = $db->sql("SELECT * FROM ppat_forms WHERE id=?", "i", $formID);
    while ($actionValuesRow = $actionValuesSQL->fetch_assoc()) {
        $thisActionID = $actionValuesRow['act3_id'];
    }

    if ($thisActionID != 0) {
        $actionValuesSQL = $db->sql("SELECT * FROM ppat_entry_actions WHERE id=?", "i", $thisActionID);
        while ($actionValuesRow = $actionValuesSQL->fetch_assoc()) {
            $thisActionID = $actionValuesRow['id'];

            $extraData[$actionValuesRow['api_trigger_field_name']] = $actionValuesRow['api_trigger_value'];
        }
    }
}

if ($actionValue_4 == 1) {

    $thisActionID = 0;

    $actionValuesSQL = $db->sql("SELECT * FROM ppat_forms WHERE id=?", "i", $formID);
    while ($actionValuesRow = $actionValuesSQL->fetch_assoc()) {
        $thisActionID = $actionValuesRow['act4_id'];
    }

    if($thisActionID !=0){
        $actionValuesSQL = $db->sql("SELECT * FROM ppat_entry_actions WHERE id=?", "i", $thisActionID);
        while ($actionValuesRow = $actionValuesSQL->fetch_assoc()) {
            $thisActionID = $actionValuesRow['id'];

            $extraData[$actionValuesRow['api_trigger_field_name']] = $actionValuesRow['api_trigger_value'];
        }
    }
}

if ($actionValue_5 == 1) {

    $thisActionID = 0;

    $actionValuesSQL = $db->sql("SELECT * FROM ppat_forms WHERE id=?", "i", $formID);
    while ($actionValuesRow = $actionValuesSQL->fetch_assoc()) {
        $thisActionID = $actionValuesRow['act5_id'];
    }

    if ($thisActionID != 0) {
        $actionValuesSQL = $db->sql("SELECT * FROM ppat_entry_actions WHERE id=?", "i", $thisActionID);
        while ($actionValuesRow = $actionValuesSQL->fetch_assoc()) {
            $thisActionID = $actionValuesRow['id'];

            $extraData[$actionValuesRow['api_trigger_field_name']] = $actionValuesRow['api_trigger_value'];
        }
    }
}

if ($actionValue_6 == 1) {

    $thisActionID = 0;

    $actionValuesSQL = $db->sql("SELECT * FROM ppat_forms WHERE id=?", "i", $formID);
    while ($actionValuesRow = $actionValuesSQL->fetch_assoc()) {
        $thisActionID = $actionValuesRow['act6_id'];
    }

    if ($thisActionID != 0) {
        $actionValuesSQL = $db->sql("SELECT * FROM ppat_entry_actions WHERE id=?", "i", $thisActionID);
        while ($actionValuesRow = $actionValuesSQL->fetch_assoc()) {
            $thisActionID = $actionValuesRow['id'];

            $extraData[$actionValuesRow['api_trigger_field_name']] = $actionValuesRow['api_trigger_value'];
        }
    }
}

if ($actionValue_7 == 1) {

    $thisActionID = 0;

    $actionValuesSQL = $db->sql("SELECT * FROM ppat_forms WHERE id=?", "i", $formID);
    while ($actionValuesRow = $actionValuesSQL->fetch_assoc()) {
        $thisActionID = $actionValuesRow['act7_id'];
    }

    if ($thisActionID != 0) {
        $actionValuesSQL = $db->sql("SELECT * FROM ppat_entry_actions WHERE id=?", "i", $thisActionID);
        while ($actionValuesRow = $actionValuesSQL->fetch_assoc()) {
            $thisActionID = $actionValuesRow['id'];

            $extraData[$actionValuesRow['api_trigger_field_name']] = $actionValuesRow['api_trigger_value'];
        }
    }
}

if ($actionValue_8 == 1) {

    $thisActionID = 0;

    $actionValuesSQL = $db->sql("SELECT * FROM ppat_forms WHERE id=?", "i", $formID);
    while ($actionValuesRow = $actionValuesSQL->fetch_assoc()) {
        $thisActionID = $actionValuesRow['act8_id'];
    }

    if ($thisActionID != 0) {
        $actionValuesSQL = $db->sql("SELECT * FROM ppat_entry_actions WHERE id=?", "i", $thisActionID);
        while ($actionValuesRow = $actionValuesSQL->fetch_assoc()) {
            $thisActionID = $actionValuesRow['id'];

            $extraData[$actionValuesRow['api_trigger_field_name']] = $actionValuesRow['api_trigger_value'];
        }
    }
}

if ($actionValue_9 == 1) {

    $thisActionID = 0;

    $actionValuesSQL = $db->sql("SELECT * FROM ppat_forms WHERE id=?", "i", $formID);
    while ($actionValuesRow = $actionValuesSQL->fetch_assoc()) {
        $thisActionID = $actionValuesRow['act9_id'];
    }

    if ($thisActionID != 0) {
        $actionValuesSQL = $db->sql("SELECT * FROM ppat_entry_actions WHERE id=?", "i", $thisActionID);
        while ($actionValuesRow = $actionValuesSQL->fetch_assoc()) {
            $thisActionID = $actionValuesRow['id'];

            $extraData[$actionValuesRow['api_trigger_field_name']] = $actionValuesRow['api_trigger_value'];
        }
    }
}

if ($actionValue_10 == 1) {

    $thisActionID = 0;

    $actionValuesSQL = $db->sql("SELECT * FROM ppat_forms WHERE id=?", "i", $formID);
    while ($actionValuesRow = $actionValuesSQL->fetch_assoc()) {
        $thisActionID = $actionValuesRow['act10_id'];
    }

    if ($thisActionID != 0) {
        $actionValuesSQL = $db->sql("SELECT * FROM ppat_entry_actions WHERE id=?", "i", $thisActionID);
        while ($actionValuesRow = $actionValuesSQL->fetch_assoc()) {
            $thisActionID = $actionValuesRow['id'];

            $extraData[$actionValuesRow['api_trigger_field_name']] = $actionValuesRow['api_trigger_value'];
        }
    }
}

if ($actionValue_11 == 1) {

    $thisActionID = 0;

    $actionValuesSQL = $db->sql("SELECT * FROM ppat_forms WHERE id=?", "i", $formID);
    while ($actionValuesRow = $actionValuesSQL->fetch_assoc()) {
        $thisActionID = $actionValuesRow['act11_id'];
    }

    if ($thisActionID != 0) {
        $actionValuesSQL = $db->sql("SELECT * FROM ppat_entry_actions WHERE id=?", "i", $thisActionID);
        while ($actionValuesRow = $actionValuesSQL->fetch_assoc()) {
            $thisActionID = $actionValuesRow['id'];

            $extraData[$actionValuesRow['api_trigger_field_name']] = $actionValuesRow['api_trigger_value'];
        }
    }
}

if ($actionValue_12 == 1) {

    $thisActionID = 0;

    $actionValuesSQL = $db->sql("SELECT * FROM ppat_forms WHERE id=?", "i", $formID);
    while ($actionValuesRow = $actionValuesSQL->fetch_assoc()) {
        $thisActionID = $actionValuesRow['act12_id'];
    }

    if ($thisActionID != 0) {
        $actionValuesSQL = $db->sql("SELECT * FROM ppat_entry_actions WHERE id=?", "i", $thisActionID);
        while ($actionValuesRow = $actionValuesSQL->fetch_assoc()) {
            $thisActionID = $actionValuesRow['id'];

            $extraData[$actionValuesRow['api_trigger_field_name']] = $actionValuesRow['api_trigger_value'];
        }
    }
}

if ($actionValue_13 == 1) {

    $thisActionID = 0;

    $actionValuesSQL = $db->sql("SELECT * FROM ppat_forms WHERE id=?", "i", $formID);
    while ($actionValuesRow = $actionValuesSQL->fetch_assoc()) {
        $thisActionID = $actionValuesRow['act13_id'];
    }

    if ($thisActionID != 0) {
        $actionValuesSQL = $db->sql("SELECT * FROM ppat_entry_actions WHERE id=?", "i", $thisActionID);
        while ($actionValuesRow = $actionValuesSQL->fetch_assoc()) {
            $thisActionID = $actionValuesRow['id'];

            $extraData[$actionValuesRow['api_trigger_field_name']] = $actionValuesRow['api_trigger_value'];
        }
    }
}

if ($actionValue_14 == 1) {

    $thisActionID = 0;

    $actionValuesSQL = $db->sql("SELECT * FROM ppat_forms WHERE id=?", "i", $formID);
    while ($actionValuesRow = $actionValuesSQL->fetch_assoc()) {
        $thisActionID = $actionValuesRow['act14_id'];
    }

    if ($thisActionID != 0) {
        $actionValuesSQL = $db->sql("SELECT * FROM ppat_entry_actions WHERE id=?", "i", $thisActionID);
        while ($actionValuesRow = $actionValuesSQL->fetch_assoc()) {
            $thisActionID = $actionValuesRow['id'];

            $extraData[$actionValuesRow['api_trigger_field_name']] = $actionValuesRow['api_trigger_value'];
        }
    }
}

if ($actionValue_15 == 1) {

    $thisActionID = 0;

    $actionValuesSQL = $db->sql("SELECT * FROM ppat_forms WHERE id=?", "i", $formID);
    while ($actionValuesRow = $actionValuesSQL->fetch_assoc()) {
        $thisActionID = $actionValuesRow['act15_id'];
    }

    if ($thisActionID != 0) {
        $actionValuesSQL = $db->sql("SELECT * FROM ppat_entry_actions WHERE id=?", "i", $thisActionID);
        while ($actionValuesRow = $actionValuesSQL->fetch_assoc()) {
            $thisActionID = $actionValuesRow['id'];

            $extraData[$actionValuesRow['api_trigger_field_name']] = $actionValuesRow['api_trigger_value'];
        }
    }
}



$api_username = $crypt->decrypt($api_username_crpt);
$api_password = $crypt->decrypt($api_password_crpt);


$contact = Create::contact(
    $api_email_address,
    $main_adestra_workspace,
    $main_adestra_list,
    $api_first_name,
    $api_last_name,
    $api_full_name,
    $api_username,
    $api_password,
    $api_qr_path_target,
    $api_qr_path,
    $extraData,
);

$listIDsarray = array();
$listIDsarray[] = $main_adestra_list;
$contactUpdate = Update::subscription($contact, $listIDsarray, true, $api_username, $api_password);
