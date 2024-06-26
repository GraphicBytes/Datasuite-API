<?php


// $data contains the submitted form data

// these must exist in these places in the ppat_api_triggers table
$api_dd_address_book_id = $main_dd_addr_book_id;
$api_email_address = $data[$api_email_data_key];

if (isset($data[$api_first_name_data_key])) {
	$api_first_name = $data[$api_first_name_data_key];
} else {
	$api_first_name = "";
}

if (isset($data[$api_last_name_data_key])) {
	$api_last_name = $data[$api_last_name_data_key];
} else {
	$api_last_name = "";
}

$api_username = $api_username_crpt;
$api_key = $api_password_crpt;


// these are optional additional fields but must match a custom datafield name in DotDigital
$extraData = array();


// - - DotDigital API connect & send - -
$url = "/v2/address-books/" . $api_dd_address_book_id . "/contacts";
$content = [
	'email' => $api_email_address,
	'optInType' => 'Single',
	'emailType' => 'Html',
	'dataFields' => [
		['key' => 'Firstname', 'value' => $api_first_name],
		['key' => 'Lastname', 'value' => $api_last_name]
	]
];


if ($api_field_pairs !== null) {

	$api_field_pairs = unserialize($api_field_pairs);
	foreach ($api_field_pairs as $key => $value) {

		if (isset($data[$key])) {
			array_push($content['dataFields'], ['key' => $key, 'value' => $data[$key]]);
		}
	}
}


$apiUsername = $crypt->decrypt($api_username);
$apiPassword = $crypt->decrypt($api_key);
$updatingContact = true;
$response = updateDotDigital($url, $content, true, false, $apiUsername, $apiPassword);
// - - /DotDigital API connect & send - -

// $test = $apiUsername;
// $test2 = $apiPassword;

// echo $apiUsername;
// echo "###############";
// echo $apiPassword;
// // die();

// echo "###############";
// print_r($response);
// $error_data["data"] = $content;
// $error_data["dotdigital"] = $response;
// echo print_r($content);
// echo "###############\n";
// echo print_r($response);
// echo "###############\n";
