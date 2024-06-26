Dropzone.autoDiscover = false;
$(dropzone_div_id).dropzone({
    url: "<?php echo $base_url; ?>image_upload/<?php echo $form_id; ?>/",
    <?php if ($dz_allowed_file_types !== null) {?>acceptedFiles: '<?php echo $dz_allowed_file_types; ?>',<?php }?>
    addRemoveLinks: true,
    headers: {
         'Cache-Control': null,
         'X-Requested-With': null,
    },
    removedfile: function(file) {
     var name = file.name;

     $.ajax({
           url: '<?php echo $base_url; ?>image_delete/<?php echo $form_id; ?>/',
           type: 'POST',
           data : {
             "name" : name,
             "csrf_token" : $('.csrf_token-<?php echo $form_id; ?>').attr('value'),
             "session" : $('.session-<?php echo $form_id; ?>').attr('value')
           },
           success: function(data){

           }
     });

    var _ref;
    return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;
    },
    sending: function(file, xhr, formData) {
       formData.append("csrf_token", $('.csrf_token-<?php echo $form_id; ?>').attr('value'));
       formData.append("session", $('.session-<?php echo $form_id; ?>').attr('value'));
    },
    success: function(file, response) {
        var imgName = response;
        file.previewElement.classList.add("dz-success");
    },
    error: function(file, response) {
        file.previewElement.classList.add("dz-error");
    }
});
