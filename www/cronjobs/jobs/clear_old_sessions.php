<?php
$cut_off_time = $current_time - 86400;



$formres = $db->sql("SELECT * FROM ppat_sessions WHERE session_time < ?", 'i', $cut_off_time);
while ($formrow = $formres->fetch_assoc()) {

  $the_session_row_id = $formrow['id'];
  $the_session_id = $formrow['session_id'];

  // clear out old temp files that might be lingering
  $fileres = $db->sql("SELECT * FROM ppat_temp_files WHERE session_id = ?", 'i', $the_session_id);
  while ($filerow = $fileres->fetch_assoc()) {

    $db_id = $filerow['id'];

    if ($filerow['extension'] == "" or $filerow['extension'] === NULL) {
      $file_to_delete = "../../temp/" . $filerow['the_file'];
    } else {
      $file_to_delete = "../../temp/" . $filerow['the_file'] . "." . $filerow['extension'];
    }

    if (file_exists($file_to_delete)) {
      unlink($file_to_delete);
    }

    $db->sql("DELETE FROM ppat_temp_files WHERE id = ?", 'i', $db_id);
  }


  $db->sql("DELETE FROM ppat_sessions WHERE id = ?", 'i', $the_session_row_id);
}

