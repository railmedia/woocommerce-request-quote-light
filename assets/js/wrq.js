jQuery(document).ready(function(){

    jQuery('body').on('click', '.open-submit-wrq', function(e){
        e.preventDefault();
        var prodId = jQuery(this).attr('data-prodid');

        var html = '<div class="wrq-form-overlay">';
            html += '<div class="wrq-form-panel">';
                html += '<div class="wrq_close_trigger">X</div>';
                html += jQuery('.wrq-form[data-prodid="' + prodId + '"]').html();
            html += '</div>';
        html += '</div>';

        jQuery('body').append( html );

    });

    jQuery('body').on('click', '.wrq_close_trigger', function(e){
        jQuery('.wrq-form-overlay').fadeOut();
        setTimeout(function(){
            jQuery('.wrq-form-overlay').remove();
        }, 1000);
    });

    jQuery('body').on('click', '.submit-wrq', function(e){

        var emailReg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,5})$/,
            prodId = jQuery(this).attr('data-prodid'),
            preloader = jQuery('.wrq-preloader-' + prodId + ' .wrq-loader'),
            msg = jQuery('.wrq-success-msg-' + prodId),
            errors = 0,
            fields = [
                jQuery('.wrq-form-overlay #user_name_' + prodId),
                jQuery('.wrq-form-overlay #user_email_' + prodId)
            ];

        jQuery.each( fields, function(index, field) {

            if( ! jQuery('.wrq-form-overlay #user_name_' + prodId).val() ) {
                jQuery('.wrq-form-overlay #user_name_' + prodId).css('border', '1px solid red').focus();
                errors = 1;
                return false;
            } else {
                jQuery('.wrq-form-overlay #user_name_' + prodId).css('border', 'initial');
                errors = 0;
            }

            if( ! jQuery('.wrq-form-overlay #user_email_' + prodId).val() || emailReg.test( jQuery('.wrq-form-overlay #user_email_' + prodId).val() ) == false ) {
                jQuery('.wrq-form-overlay #user_email_' + prodId).css('border', '1px solid red').focus();
                errors = 1;
                return false;
            } else {
                jQuery('.wrq-form-overlay #user_email_' + prodId).css('border', 'initial');
                errors = 0;
            }

        } );

        if( ! errors ) {

            preloader.css({ display: 'inline-block' });

            jQuery.ajax('/wp-json/wrq/v1/send-quote', {
                async: true,
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-WP-Nonce', wrq.nonce);
                },
                data: JSON.stringify({
                    product_id: jQuery(this).attr('data-prodid'),
                    product_name: jQuery(this).attr('data-prodtitle'),
                    user_name: jQuery('.wrq-form-overlay #user_name_' + prodId).val(),
                    user_email: jQuery('.wrq-form-overlay #user_email_' + prodId).val(),
                    user_phone:  jQuery('.wrq-form-overlay #user_phone_' + prodId).val()
                }),
                dataType: 'json',
                success: function(mailSent) {
                    preloader.hide();
                    msg.text( mailSent.message );
                    jQuery('.wrq-form-panel input').val('');
                }
            });

        }

    });

});
