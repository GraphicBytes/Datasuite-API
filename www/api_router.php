<?php
///////////////////////
////// CRON JOBS //////
///////////////////////

if ($page == "cron-rKxBBNnFLg") {
    include_once('cronjobs/cron_master.php');
}


//////////////////////////
////// API REQUESTS //////
//////////////////////////

// user-token
else if ($page == "user-token") {
    $api_request_type = 1;
    include_once('API-handles/user-token.php');
}
// login
else if ($page == "login") {
    $api_request_type = 1;
    include_once('API-handles/login.php');
}
// nhs login
// else if ($page == "nhs-login") {
//     $api_request_type = 1;
//     include_once('API-handles/nhs-email.php');
// }
// two_fa_submit
else if ($page == "two_fa_submit") {
    $api_request_type = 1;
    include_once('API-handles/two_fa_submit.php');
}
// two_fa_submit nhs
// else if ($page == "two_fa_submit_nhs") {
//     $api_request_type = 1;
//     include_once('API-handles/two_fa_submit_nhs.php');
// }
// two_fa_submit enforced
else if ($page == "two-fa-email-enforced") {
    $api_request_type = 1;
    include_once('API-handles/two_fa_submit_email_enforced.php');
}
else if ($page == "two-fa-code-enforced") {
    $api_request_type = 1;
    include_once('API-handles/two_fa_submit_code_enforced.php');
}
// logout
else if ($page == "logout" && $logged_in_id > 0) {
    $api_request_type = 1;
    include_once('API-handles/logout.php');
}
// view-forms
else if ($page == "view-forms" && $logged_in_id > 0) {
    $api_request_type = 1;
    include_once('API-handles/view-forms.php');
}
// form-data
else if ($page == "form-data" && $logged_in_id > 0) {
    $api_request_type = 1;
    include_once('API-handles/form-data.php');
}
// get-data-entry-id
else if ($page == "get-data-entry-id" && $logged_in_id > 0) {
    $api_request_type = 1;
    include_once('API-handles/get-data-entry-id.php');
}
// data-entry
else if (
    $page == "data-entry" && $logged_in_id > 0
) {
    $api_request_type = 1;
    include_once('API-handles/data-entry.php');
}

// update-data-entry
else if ($page == "update-data-entry" && $logged_in_id > 0) {
    $api_request_type = 1;
    include_once('API-handles/update-data-entry.php');
}
// toggle-upload-privacy
else if ($page == "toggle-upload-privacy" && $logged_in_id > 0) {
    $api_request_type = 1;
    include_once('API-handles/toggle-upload-privacy.php');
}
// public-data
else if ($page == "public-data") {
    $api_request_type = 1;
    include_once('API-handles/public-data.php');
}

else if ($page == "public-data-video") {
    $api_request_type = 1;
    include_once('API-handles/public-data-video.php');
}

 
// check-qr
else if ($page == "check-qr") {
    $api_request_type = 1;
    include_once('API-handles/check-qr.php');
}
// check-qr
else if ($page == "download-form-data") {
    $api_request_type = 1;
    include_once('API-handles/download-form-data.php');
}
// Get Form Quota
else if ($page == "get-quota" && $api_request_type == 0) {
    $api_request_type = 1;
    include_once('actions/get_quota.php');
}





///////////////////////////
////// FORM ACTIONS ///////
///////////////////////////

// get token and create session
else if ($page == "get-token" && $api_request_type == 0) {
    include_once('actions/get-token.php');
}

// Image Upload
else if ($page == "image_upload" && $api_request_type == 0) {
    include_once('actions/image_upload.php');
}

// Image Upload
else if ($page == "image_delete" && $api_request_type == 0) {
    include_once('actions/image_delete.php');
}

// Form Submit
else if (($page == "submit" || $page == "submit-form") && $api_request_type == 0) {
    include_once('actions/submit.php');
}

// Form Submit Location restricted
else if ($page == "submit-form-enforced" && $api_request_type == 0) {
    include_once('actions/submit_restricted.php');
}

// Fetch Form JS
else if ($page == "get") {
    include_once('actions/get_form.php');
}


///////////////////////
////// DEV JUNK ///////
///////////////////////

// test page
else if ($page == "test") {
    include_once('API-handles/test.php');
}

else if ($page == "" || $page == null) {
  $api_request_type = 1;
  $result['status'] = 0;
}


//###############################
//##### API REQUEST RESULT ######
//###############################
if ($api_request_type == 1) {
    header('Content-type: application/json');
    echo json_encode($result);
}
