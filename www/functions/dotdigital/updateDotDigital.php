<?php
/* --- DotDigital API connect & send ---*/

function updateDotDigital($url, $content, $sendingPostData, $updatingContact, $apiUsername, $apiPassword) {

    $baseUrl = 'https://api.dotmailer.com';
    $url = $baseUrl . $url;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json','Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_USERPWD, "$apiUsername" . ':' . $apiPassword);

    // if we're sending post data
    if($sendingPostData) {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($content));
    }

    // if we're updating a contact
    if($updatingContact) {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($content));
    }

    // send the update to Dotmailer and return the response
    $response = json_decode(curl_exec($ch));
    return $response;
}




function getDotDigitalAddressBook($listID, $apiUsername, $apiPassword)
{

    $baseUrl = 'https://r1-api.dotdigital.com/v2/address-books/';
    $url = $baseUrl . $listID . "/contacts";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_USERPWD, "$apiUsername" . ':' . $apiPassword);

    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

    // send the update to Dotmailer and return the response
    $response = json_decode(curl_exec($ch));
    return $response;
}
