<?php
header("Content-Type: application/javascript");


if ($env == 1) {
    ob_start();
}
include_once '_common/jQuery_check.php';
?>

<?php include_once '_common/decodeEntities.php';?>
<?php include_once '_common/getParameterByName.php';?>

P_PAT_RUN = function(){

<?php
$form_enabled = 0;
$form_exist = 0;
$form_id = $typea;
$enforced_forms = [68];
?>

<?php if ($form_id !== null or $form_id != "") {?>

  <?php
$formres = $db->sql("SELECT * FROM ppat_forms WHERE id=?", 'i', $form_id);
    while ($formrow = $formres->fetch_assoc()) {
        $formdata = $formrow;
        $form_enabled = $formrow['status'];
        $submit_button_text = $formrow['submit_button_text'];
        $form_exist = 1;
    }?>


  var showcallbackmsg = 0;

  <?php
if ($form_exist == 1) {

        $callbackres = $db->sql("SELECT * FROM ppat_callback_messages WHERE form_id=? ORDER BY id ASC  ", 'i', $form_id);
        while ($callbackrow = $callbackres->fetch_assoc()) {

            $query_key = $callbackrow['query_key'];
            $query_key = str_replace("-", "_", $query_key);

            ?>

      var get_<?php echo $query_key; ?> = getParameterByName('<?php echo $callbackrow['query_key']; ?>');

      if(get_<?php echo $query_key; ?> == "<?php echo $callbackrow['query_value']; ?>"){

      var showcallbackmsg = 1;
      var showcallbackmsgHTML = '<?php echo clean_ob_for_js(htmlspecialchars($callbackrow['content'], ENT_QUOTES)); ?>';

      }

  <?php
}
    }
    ?>

  <?php if ($form_exist == 1) {?>

    <?php if ($form_enabled == 1) {?>

      <?php
$formfields = array();
        $formres = $db->sql("SELECT * FROM ppat_form_fields WHERE form_id=? ORDER BY field_order ASC  ", 'i', $form_id);
        while ($formrow = $formres->fetch_assoc()) {
            $formfields[$formrow['id']] = $formrow;
        }

        $formfield_count = 0;
        $formfield_debug = "";
        $data = array();

        include_once '_common/drop_zone_check.php';
        ?>

      var ppat_form_basic_found = 0;

      if(
      document.body.contains(document.getElementById('ppat-form'))
      ||
      document.body.contains(document.getElementById('ppat-form-m-<?php echo $form_id; ?>'))
      ){


      let form = document.querySelector('#ppat-form');

      var submit_success_message = "";

      var html = '<div class="ppat-field-feedback-msg-complete ppat-field-feedback-msg-complete-<?php echo $form_id; ?> "></div>';

      if(showcallbackmsg == 1){

      setTimeout(function(){
      $(".ppat-field-feedback-msg-complete-<?php echo $form_id; ?>").html(decodeHTMLEntities(showcallbackmsgHTML));
      $(".ppat-field-feedback-msg-complete-<?php echo $form_id; ?>").addClass("complete");
      }, 100);

      }
      if(showcallbackmsg == 0){

      if(document.body.contains(document.getElementById('ppat-form'))){
      ppat_form_basic_found = 1;
      }

      if(ppat_form_basic_found == 1){

      html = html + '<form id="ppat-form-<?php echo $form_id; ?>" class="ppat-form ppat-form-<?php echo $form_id; ?>" action="<?php echo $base_url; ?>submit-form<?php echo (in_array($form_id, $enforced_forms) ? '-enforced' : ''); ?>/<?php echo $form_id; ?>/" method="post" enctype="multipart/form-data">';

        html = html + '<div class="ppat-field-feedback-msg ppat-field-feedback-msg-main-<?php echo $form_id; ?> "></div>';

        }

        <?php
foreach ($formfields as $key):
            $formfield_count = $formfield_count + 1;
            $field_id = $key['id'];
            $field_name = $key['field_name'];
            $field_show_label = $key['show_label'];
            $field_label = $key['label'];
            $field_type = $key['field_type'];
            $placeholder = $key['placeholder'];
            $required = $key['required'];
            $extended_label = $key['extended_label'];
            $allowed_file_types = $key['allowed_file_types'];

            if ($field_type == "submit_success_message"):
            ?>submit_success_message = '<?php echo clean_ob_for_js($field_label); ?>'; <?php
endif;

        if ($field_type == "html" or $field_type == "HTML"):
            ob_start();
            include '_field_types/html.php';
            echo clean_ob_for_js(ob_get_clean());
        endif;

        if ($field_type == "text" or $field_type == "TEXT"):
            ob_start();
            include '_field_types/text-field.php';
            echo clean_ob_for_js(ob_get_clean());
        endif;

        if ($field_type == "textarea" or $field_type == "TEXTAREA"):
            ob_start();
            include '_field_types/textarea.php';
            echo clean_ob_for_js(ob_get_clean());
        endif;

        if ($field_type == "email" or $field_type == "EMAIL"):
            ob_start();
            include '_field_types/email.php';
            echo clean_ob_for_js(ob_get_clean());
        endif;

        if ($field_type == "checkbox" or $field_type == "CHECKBOX"):
            ob_start();
            include '_field_types/checkbox.php';
            echo clean_ob_for_js(ob_get_clean());
        endif;

        if ($field_type == "dropzone" or $field_type == "DROPZONE"):
            ob_start();
            include '_field_types/dropzone.php';
            $dz_allowed_file_types = $allowed_file_types;
            echo clean_ob_for_js(ob_get_clean());
        endif;

        if ($field_type == "select" or $field_type == "SELECT"):
            ob_start();
            include '_field_types/select.php';
            echo clean_ob_for_js(ob_get_clean());
        endif;

        if ($field_type == "hidden" or $field_type == "HIDDEN"):
            ob_start();
            include '_field_types/hidden.php';
            echo clean_ob_for_js(ob_get_clean());
        endif;

        endforeach;

        include '_field_types/submit-button.php';
        ?>


    var csrf_token_html = '<input class="csrf_token-<?php echo $form_id; ?>" type="hidden" name="csrf_token" value="" />';
    var session_html = '<input class="session-<?php echo $form_id; ?>" type="hidden" name="session" value="" />';


    if(ppat_form_basic_found == 1){

    html = html + csrf_token_html;
    html = html + session_html;
    html = html + '</form>';
      form.innerHTML = html;

      }

      if(ppat_form_basic_found == 0){

      if(document.body.contains(document.getElementById('ppat-<?php echo $form_id; ?>__token'))){
      $("#ppat-<?php echo $form_id; ?>__token").html(csrf_token_html);
      }

      if(document.body.contains(document.getElementById('ppat-<?php echo $form_id; ?>__session'))){
      $("#ppat-<?php echo $form_id; ?>__session").html(session_html);
      }

      }

      setTimeout(function(){

      <?php if ($will_need_dropzone == 1) {
            include_once '_common/drop_zone.php';
        }?>

      get_tokens();

      }, 100);


      if(ppat_form_basic_found == 0){

      if(document.body.contains(document.getElementById('ppat-form-m-<?php echo $form_id; ?>'))){

      $("#ppat-form-m-<?php echo $form_id; ?>").addClass("ppat-form");
      $("#ppat-form-m-<?php echo $form_id; ?>").addClass("ppat-form-<?php echo $form_id; ?>");
      $("#ppat-form-m-<?php echo $form_id; ?>").attr('action', '<?php echo $base_url; ?>submit-form<?php echo (in_array($form_id, $enforced_forms) ? '-enforced' : ''); ?>/<?php echo $form_id; ?>/');
      $("#ppat-form-m-<?php echo $form_id; ?>").attr('method', 'post');
      $("#ppat-form-m-<?php echo $form_id; ?>").attr('enctype', 'multipart/form-data');

      } else {
      console.log("FORM ELEMENT NOT FOUND!");
      }

      if(document.body.contains(document.getElementById('ppat-feedback-msg-<?php echo $form_id; ?>'))){

      $("#ppat-feedback-msg-<?php echo $form_id; ?>").addClass("ppat-field-feedback-msg");
      $("#ppat-feedback-msg-<?php echo $form_id; ?>").addClass("ppat-field-feedback-msg-main-<?php echo $form_id; ?>");

      } else {
      console.log("MAIN FEEDBACK MESSAGE <DIV> MISSING!");
        }

        }

        }


        <?php
foreach ($formfields as $key):
            $formfield_count = $formfield_count + 1;
            $field_id = $key['id'];
            $field_name = $key['field_name'];
            $field_show_label = $key['show_label'];
            $field_label = $key['label'];
            $field_type = $key['field_type'];
            $placeholder = $key['placeholder'];
            $required = $key['required'];
            $extended_label = $key['extended_label'];
            $allowed_file_types = $key['allowed_file_types'];

            if ($field_type == "submit_success_message"):
            ?>submit_success_message = '<?php echo clean_ob_for_js($field_label); ?>'; <?php
endif;

        if ($field_type == "html" or $field_type == "HTML"):

        endif;

        if ($field_type == "text" or $field_type == "TEXT"):

        endif;

        if ($field_type == "textarea" or $field_type == "TEXTAREA"):

        endif;

        if ($field_type == "email" or $field_type == "EMAIL"):

        endif;

        if ($field_type == "checkbox" or $field_type == "CHECKBOX"):

        endif;

        if ($field_type == "dropzone" or $field_type == "DROPZONE"):

        endif;

        if ($field_type == "select" or $field_type == "SELECT"):

        endif;

        endforeach;

        include '_field_types/submit-button.php';
        ?>
    }

    <?php include_once '_common/ajax_submit.php';?>

  <?php } else {?>
    console.log('ERROR: FORM <?php echo $form_id; ?> IS DISABLED');
  <?php }?>

<?php } else {?>
  console.log('ERROR: FORM <?php echo $form_id; ?> DOES NOT EXIST');
<?php }?>

<?php } else {?>
  console.log('ERROR: FORM ID NOT SET');
<?php }?>

<?php
if ($env == 1) {
    echo clean_ob_for_js(ob_get_clean());
}
include_once '_common/get_tokens.php';
?>}