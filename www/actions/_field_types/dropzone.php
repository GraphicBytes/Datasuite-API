var field_html_<?php echo $field_id; ?> = '<div id="ppat-<?php echo $field_name; ?>" class="ppat-dropzone dropzone dropzone-<?php echo $field_id; ?> dropzone-<?php echo $field_name; ?> ppat-upload ppat-upload-<?php echo $field_name; ?> ppat-upload-<?php echo $field_id; ?>" style="min-height:25px; min-width:50px; overflow:hidden;">';
  field_html_<?php echo $field_id; ?> = field_html_<?php echo $field_id; ?> + '<p><?php echo $extended_label; ?></p>';
  field_html_<?php echo $field_id; ?> = field_html_<?php echo $field_id; ?> + '<div class="dz-default dz-message"></div>';
  field_html_<?php echo $field_id; ?> = field_html_<?php echo $field_id; ?> + '</div>';
field_html_<?php echo $field_id; ?> = field_html_<?php echo $field_id; ?> + '</div>';
field_html_<?php echo $field_id; ?> = field_html_<?php echo $field_id; ?> + "<style>
  <?php
  ob_start();
  include_once('/var/www/html/actions/css/dropzone.css');
  echo clean_ob_for_js(ob_get_clean());
  ?>
</style>";

<?php include('./actions/_common/ppat-field-feedback-msg.php'); ?>

<?php include('./actions/_common/ppat-field-label.php'); ?>

var dropzone_div_id = "#ppat-<?php echo $field_name; ?>";

if(ppat_form_basic_found == 1){

html = html + '<div class="ppat-form-row ppat-form-row-dropzone ppat-form-row-<?php echo $field_name; ?> ppat-form-row-<?php echo $field_id; ?>">';

  if(label_html_<?php echo $field_id; ?> === null){
  label_html_<?php echo $field_id; ?> = "";
  }

  html = html + label_html_<?php echo $field_id; ?>;

  html = html + field_html_<?php echo $field_id; ?>;

  html = html + field_feedback_msg_html_<?php echo $field_id; ?>;

  }



  if(ppat_form_basic_found == 0){

  if(document.body.contains(document.getElementById('ppat-<?php echo $form_id; ?>-<?php echo $field_name; ?>'))){

  $("#ppat-<?php echo $form_id; ?>-<?php echo $field_name; ?>").addClass("ppat-form-row");
  $("#ppat-<?php echo $form_id; ?>-<?php echo $field_name; ?>").addClass("ppat-form-row-dropzone");
  $("#ppat-<?php echo $form_id; ?>-<?php echo $field_name; ?>").addClass("ppat-form-row-<?php echo $field_name; ?>");
  $("#ppat-<?php echo $form_id; ?>-<?php echo $field_name; ?>").addClass("ppat-form-row-<?php echo $field_id; ?>");

  $("#ppat-<?php echo $form_id; ?>-<?php echo $field_name; ?>").html(field_html_<?php echo $field_id; ?>);

  }

  if(document.body.contains(document.getElementById('ppat-fm-<?php echo $form_id; ?>-<?php echo $field_name; ?>'))){

  $("#ppat-fm-<?php echo $form_id; ?>-<?php echo $field_name; ?>").addClass("ppat-field-feedback-msg-container");
  $("#ppat-fm-<?php echo $form_id; ?>-<?php echo $field_name; ?>").addClass("ppat-field-feedback-msg-container-<?php echo $field_name; ?>");
  $("#ppat-fm-<?php echo $form_id; ?>-<?php echo $field_name; ?>").addClass("ppat-field-feedback-msg-container-<?php echo $field_id; ?>");

  $("#ppat-fm-<?php echo $form_id; ?>-<?php echo $field_name; ?>").html(field_feedback_msg_html_<?php echo $field_id; ?>);

  }

  if(document.body.contains(document.getElementById('ppat-lb-<?php echo $form_id; ?>-<?php echo $field_name; ?>'))){

  $("#ppat-lb-<?php echo $form_id; ?>-<?php echo $field_name; ?>").addClass("ppat-label-container");
  $("#ppat-lb-<?php echo $form_id; ?>-<?php echo $field_name; ?>").addClass("ppat-label-container-<?php echo $field_name; ?>");
  $("#ppat-lb-<?php echo $form_id; ?>-<?php echo $field_name; ?>").addClass("ppat-label-container-<?php echo $field_id; ?>");

  $("#ppat-lb-<?php echo $form_id; ?>-<?php echo $field_name; ?>").html(label_html_<?php echo $field_id; ?>);

  }

  }