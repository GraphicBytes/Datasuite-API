<?php $enforced_forms = [68]; ?>

$('.ppat-submit-<?php echo $form_id; ?>').click( function() {

  event.preventDefault();

  $(".ppat-form-<?php echo $form_id; ?>").addClass("submitting");
  $(".ppat-field-feedback-msg-main-<?php echo $form_id; ?>").html("");
  $(".ppat-field-feedback-msg").html("");

  $("body").addClass("ppat-submitting");  
  $("body").addClass("ppat-submitting-<?php echo $form_id; ?>");

  $.ajax({

    url: '<?php echo $base_url; ?>submit-form<?php echo (in_array($form_id, $enforced_forms) ? '-enforced' : ''); ?>/<?php echo $form_id; ?>/',
    type: 'POST',
    data : $('.ppat-form-<?php echo $form_id; ?>').serialize(),
    success: function(data){

      console.log(data);

      var obj = JSON.parse(data);
      var response_code = obj['response'];
      var message = obj['message'];
      var error_data = obj['error_data'];
      var scroll_to = obj['to'];

      if(response_code==0){
        $(".ppat-field-feedback-msg-main-<?php echo $form_id; ?>").html(message);
        $(".ppat-feedback-msg-<?php echo $form_id; ?>").html(message);
        $(".ppat-form-<?php echo $form_id; ?>").removeClass("submitting");
        $("body").removeClass("ppat-submitting");
        $("body").removeClass("ppat-submitting-<?php echo $form_id; ?>");
      }

      if(response_code==1){
        Object.keys(error_data).forEach(function (item, index) {
          field_with_error = '.ppat-field-feedback-msg-' + item;

          $(".ppat-field-feedback-msg-main-<?php echo $form_id; ?>").html(message);
          $(".ppat-feedback-msg-<?php echo $form_id; ?>").html(message);

          $(field_with_error).html(error_data[item]);

          $(field_with_error).addClass("ppat-field-error");
          $(".ppat-field-error").on('focus', function(){
            $(this).removeClass('ppat-field-error');
          });

          $("body").removeClass("ppat-submitting");
          $("body").removeClass("ppat-submitting-<?php echo $form_id; ?>");

        });

        $(".ppat-form-<?php echo $form_id; ?>").removeClass("submitting");

        $('html,body').animate({
           scrollTop: $(scroll_to).offset().top - 100
        }, 100);

      }

      if(response_code==2){
        $(".ppat-form-<?php echo $form_id; ?>").removeClass("submitting");
        $(".ppat-form-<?php echo $form_id; ?>").addClass("submitted");

        $(".ppat-field-feedback-msg-complete-<?php echo $form_id; ?>").html(submit_success_message);
        $(".ppat-field-feedback-msg-complete-<?php echo $form_id; ?>").addClass("complete");

        $("body").removeClass("ppat-submitting");
        $("body").removeClass("ppat-submitting-<?php echo $form_id; ?>");
        
        $("#ppat-complete-<?php echo $form_id; ?>").html(submit_success_message);
        $("#ppat-complete-<?php echo $form_id; ?>").addClass("complete");

      }

    }

  });



  return false;
});
