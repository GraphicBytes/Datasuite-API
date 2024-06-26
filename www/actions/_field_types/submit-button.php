

var field_html_<?php echo $field_id; ?> = '<input id="ppat-submit" class="ppat-submit ppat-submit-<?php echo $form_id; ?>" value="<?php echo $submit_button_text; ?>" type="submit" />';


if(ppat_form_basic_found == 1){

    html = html + '<div class="ppat-form-row ppat-form-row-submit ppat-form-row-<?php echo $form_id; ?>">';
    html = html + field_html_<?php echo $field_id; ?>;
    html = html + '</div>';

}


if(ppat_form_basic_found == 0){

    if(document.body.contains(document.getElementById('ppat-<?php echo $form_id; ?>-submit'))){
                    
    $("#ppat-<?php echo $form_id; ?>-submit").addClass("ppat-form-row");
    $("#ppat-<?php echo $form_id; ?>-submit").addClass("ppat-form-row-submit");
    $("#ppat-<?php echo $form_id; ?>-submit").addClass("ppat-form-row-submit");
    $("#ppat-<?php echo $form_id; ?>-submit").addClass("ppat-form-row-<?php echo $field_id; ?>");

    $("#ppat-<?php echo $form_id; ?>-submit").html(field_html_<?php echo $field_id; ?>);
    
    } else {
    console.log("<DIV> for '<?php echo $form_id; ?>' MISSING!");
    }

}



