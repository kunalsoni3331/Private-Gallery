
/**** Set product image popup in private gallery page start ****/
jQuery(document).ready(function () {

    loadGallery(true, 'button.thumbnail');

    function disableButtons(counter_max, counter_current) {
        jQuery('#show-previous-image, #show-next-image').show();
        if (counter_max === counter_current) {
            jQuery('#show-next-image').hide();
        } else if (counter_current === 1) {
            jQuery('#show-previous-image').hide();
        }
    }

    function loadGallery(setIDs, setClickAttr) {
        let current_image, selector, counter = 0;

        jQuery('#show-next-image, #show-previous-image').click(function () {
            if (jQuery(this).attr('id') === 'show-previous-image') {
            current_image--;
            } else {
            current_image++;
            }

            selector = jQuery('[data-image-id="' + current_image + '"]');
            updateGallery(selector);
        });

        function updateGallery(selector) {
            let $sel = selector;
            current_image = $sel.data('image-id');
            jQuery('#image-gallery-title').text($sel.data('title'));
            jQuery('#image-gallery-image').attr('src', $sel.data('image'));
            disableButtons(counter, $sel.data('image-id'));
        }

        if (setIDs == true) {
            jQuery('[data-image-id]').each(function () {
                counter++;
                jQuery(this).attr('data-image-id', counter);
            });
        }
        jQuery(setClickAttr).on('click', function () {
            updateGallery(jQuery(this));
        });
    }
});

/**** Set product image popup in private gallery page end ****/



/**** set validation of submit private gallery start ****/
jQuery(document).ready(function() {
    jQuery("#submit_product").attr('disabled','disabled');
    jQuery('.private_checkbox').change(function() {
        if ( jQuery("#private_gallery_frm input:checkbox:checked").length > 0 ) {
            jQuery("#submit_product").removeAttr('disabled','disabled');
        } else {
           jQuery("#submit_product").attr('disabled','disabled');
        }
    });

    jQuery("#protect_product").click( function() {
        if( jQuery("#private_url").val() == '' ) {
            jQuery(".error-url").show();
            return false;
        }
        if( jQuery("#user_pass").val() == '' ) {
            jQuery(".error-password").show();
            return false;
        }
    });
});
/**** set validation of submit private gallery end ****/



/**** Set links page data table and change password start ****/
jQuery(document).ready(function() {
    jQuery('#example').DataTable();
});

jQuery('.kpg_change_passwrod').on("click", function() {
    jQuery(this).parents('.link_field').hide();
    jQuery(this).parents('td').find('.change_password_input').fadeIn();
});

jQuery('.kpg_link_cancel').on("click", function() {
    jQuery(this).parents('.change_password_input').hide();
    jQuery(this).parents('td').find('.link_field').fadeIn();
});

jQuery('.kpg_link_password_update').on("click", function() {
    var $this = jQuery(this).parents('td');
    var post_id = $this.find('#post_id').val();
    var new_pass = $this.find('#new_password').val();
    $this.find('#new_password').val('');
    if( new_pass == '' ) {
        $this.find('#new_password').css('border-color', '#ff0000');
        return false;
    } else {
        $.ajax({
            method: "POST",
            url: ajaxurl,
            data: { action: "kpg_link_password_update", post_id: post_id, new_password: new_pass },
            beforeSend: function( xhr ) {
                $this.css('opacity','0.5');
            }
        }).done(function( msg ) {
            $this.css('opacity','1');
            $this.find('.change_password_input').hide();
            $this.find('.change_password_success_msg').fadeIn();
            setTimeout(function(){ 
                $this.find('.change_password_success_msg').hide();
                $this.find('.link_field').fadeIn();
            }, 3000);
        });
    }
});
/**** Set links page data table and change password end ****/



/**** Set validation of front end start ****/
jQuery('#private_gallery').click(function() {
    if( jQuery("#post_password").val() == '' ) {
        jQuery(".error_password_msg").show();
        return false;
    }
});
/**** Set validation of front end end ****/



/**** set validation of front end for select product start ****/
jQuery(document).ready(function() {
    jQuery(".mybtton").attr('disabled','disabled');
    jQuery('.private_checkbox').change(function() {
        if ( jQuery("#private_gallery_frm input:checkbox:checked").length > 0 ) {
            jQuery(".mybtton").removeAttr('disabled','disabled');
        } else {
           jQuery(".mybtton").attr('disabled','disabled');
        }
    });

    jQuery("#protect_product").click( function() {
        if( jQuery("#private_url").val() == '' ) {
            jQuery(".error-url").show();
            return false;
        }
        if( jQuery("#user_pass").val() == '' ) {
            jQuery(".error-password").show();
            return false;
        }
    });
});
/**** set validation of front end for select product end ****/

