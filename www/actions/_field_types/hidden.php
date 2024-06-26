var field_html_<?php echo $field_id; ?> = '<input id="ppat-<?php echo $field_name; ?>" name="<?php echo $field_name; ?>" value="<?php echo $placeholder; ?>" type="hidden">';


if(ppat_form_basic_found == 1){

  html = html + field_html_<?php echo $field_id; ?>;

}


if(ppat_form_basic_found == 0){

  if(document.body.contains(document.getElementById('ppat-<?php echo $form_id; ?>-<?php echo $field_name; ?>'))){
                    
    $("#ppat-<?php echo $form_id; ?>-<?php echo $field_name; ?>").html(field_html_<?php echo $field_id; ?>);

  } 

}
