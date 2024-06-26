<?php
$cut_off_time = $current_time - 14400; //4 hours

$formres = $db->sql("SELECT * FROM file_access_tokens WHERE token_age < ? AND permanent = 0", 'i', $cut_off_time);
while ($formrow = $formres->fetch_assoc()) {

    $the_token_row_id = $formrow['id'];

    $db->sql("DELETE FROM file_access_tokens WHERE id = ?", 'i', $the_token_row_id);



}
