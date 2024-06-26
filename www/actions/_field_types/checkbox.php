
var field_html_<?php echo $field_id; ?> = '<input id="ppat-<?php echo $field_name; ?>" class="ppat-checkbox ppat-checkbox-<?php echo $field_name; ?> ppat-checkbox-<?php echo $field_id; ?>" type="checkbox" name="<?php echo $field_name; ?>">';

<?php include('./actions/_common/ppat-field-feedback-msg.php'); ?>

<?php include('./actions/_common/ppat-field-label.php'); ?>

if(ppat_form_basic_found == 1){

  html = html + '<div class="ppat-form-row ppat-form-row-checkbox ppat-form-row-<?php echo $field_name; ?> ppat-form-row-<?php echo $field_id; ?>">';
  
  html = html + field_feedback_msg_html_<?php echo $field_id; ?>;

  html = html + field_html_<?php echo $field_id; ?>;

  html = html + label_html_<?php echo $field_id; ?>;

  html = html + '</div>';
}



if(ppat_form_basic_found == 0){

  if(document.body.contains(document.getElementById('ppat-<?php echo $form_id; ?>-<?php echo $field_name; ?>'))){
                    
    $("#ppat-<?php echo $form_id; ?>-<?php echo $field_name; ?>").addClass("ppat-form-row");
    $("#ppat-<?php echo $form_id; ?>-<?php echo $field_name; ?>").addClass("ppat-form-row-checkbox");
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




