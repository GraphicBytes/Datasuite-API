<?php
$will_need_dropzone = 0;
foreach ($formfields as $key) :
  $field_type = $key['field_type'];
  if ($field_type == "dropzone" or $field_type == "DROPZONE") :
    $will_need_dropzone = 1;
  endif;
endforeach;


if ($will_need_dropzone == 1) : ?>
  console.log('DROPZONE NEEDED');
  if (eval("typeof " + "Dropzone") === "function") {
  console.log("DROPZONE FOUND");
  } else {
  console.log("DROPZONE NOT FOUND");
  <?php include_once('dropzoneJS.php'); ?>
  }
<?php endif; ?>