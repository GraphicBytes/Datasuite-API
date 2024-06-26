if(ppat_form_basic_found == 1){

  html = html + '<div class="ppat-form-row ppat-form-row-html ppat-form-row-<?php echo $field_name; ?> ppat-form-row-<?php echo $field_id; ?>">';

  <?php
      if ($extended_label != "" OR $extended_label !== NULL) {
        $field_label_to_use = $extended_label;
      } else {
        $field_label_to_use = $field_label;
      }
  ?>
    html = html + decodeHTMLEntities('<?php echo htmlentities($field_label_to_use); ?>');

  html = html + '</div>';

}


if(ppat_form_basic_found == 0){

  if(document.body.contains(document.getElementById('ppat-<?php echo $form_id; ?>-<?php echo $field_name; ?>'))){
                    
    $("#ppat-<?php echo $form_id; ?>-<?php echo $field_name; ?>").addClass("ppat-form-row");
    $("#ppat-<?php echo $form_id; ?>-<?php echo $field_name; ?>").addClass("ppat-form-row-html");
    $("#ppat-<?php echo $form_id; ?>-<?php echo $field_name; ?>").addClass("ppat-form-row-<?php echo $field_name; ?>");
    $("#ppat-<?php echo $form_id; ?>-<?php echo $field_name; ?>").addClass("ppat-form-row-<?php echo $field_id; ?>");

    $("#ppat-<?php echo $form_id; ?>-<?php echo $field_name; ?>").html(decodeHTMLEntities('<?php echo htmlentities($field_label_to_use); ?>'));

  } else {
    console.log("<DIV> for '<?php echo $form_id; ?>' MISSING!");
  }


  

}

