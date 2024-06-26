<?php if ($field_show_label==1){

    if ($extended_label != "" OR $extended_label !== NULL) {
      $field_label_to_use = $extended_label;
    } else {
      $field_label_to_use = $field_label;
    }
?>
  var label_html_<?php echo $field_id; ?> = '<label for="<?php echo $field_name; ?>" class="ppat-form-label ppat-form-label-<?php echo $field_name; ?> ppat-form-label-<?php echo $field_id; ?>">' + decodeHTMLEntities('<?php echo htmlentities($field_label_to_use, ENT_QUOTES); ?>') + '</label>';
  
<?php } else { ?>
  var label_html_<?php echo $field_id; ?> = null;
<?php }; ?>