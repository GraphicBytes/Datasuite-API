var field_html_<?php echo $field_id; ?> = '<select name="<?php echo $field_name; ?>" id="ppat-<?php echo $field_name; ?>" class="ppat-select ppat-select-<?php echo $field_name; ?> ppat-select-<?php echo $field_id; ?>">';
field_html_<?php echo $field_id; ?> = field_html_<?php echo $field_id; ?> + '<option disabled selected value="PPAT-NONE"><?php echo $placeholder; ?></option>';

<?php
$selectres=$db->sql( "SELECT * FROM ppat_field_values WHERE field_id=? ORDER BY id ASC  ", 'i' , $field_id );
while($selectrow=$selectres->fetch_assoc()) { ?>

  field_html_<?php echo $field_id; ?> = field_html_<?php echo $field_id; ?> + '<option <?php if ($selectrow['selected'] == 1) {
    echo "selected";
  } ?> value="<?php echo $selectrow['option_value']; ?>"><?php echo $selectrow['option_label']; ?></option>';

<?php } ?>

field_html_<?php echo $field_id; ?> = field_html_<?php echo $field_id; ?> + '';
field_html_<?php echo $field_id; ?> = field_html_<?php echo $field_id; ?> + '';
field_html_<?php echo $field_id; ?> = field_html_<?php echo $field_id; ?> + '</select>';

<?php include('./actions/_common/ppat-field-feedback-msg.php'); ?>

<?php include('./actions/_common/ppat-field-label.php'); ?>


if(ppat_form_basic_found == 1){

  html = html + '<div class="ppat-form-row ppat-form-row-select ppat-form-row-<?php echo $field_name; ?> ppat-form-row-<?php echo $field_id; ?>">';

  html = html + field_feedback_msg_html_<?php echo $field_id; ?>;

  html = html + label_html_<?php echo $field_id; ?>;

  html = html + field_html_<?php echo $field_id; ?>;

  html = html + '</div>';

}



if(ppat_form_basic_found == 0){

  if(document.body.contains(document.getElementById('ppat-<?php echo $form_id; ?>-<?php echo $field_name; ?>'))){
                    
    $("#ppat-<?php echo $form_id; ?>-<?php echo $field_name; ?>").addClass("ppat-form-row");
    $("#ppat-<?php echo $form_id; ?>-<?php echo $field_name; ?>").addClass("ppat-form-row-select");
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





