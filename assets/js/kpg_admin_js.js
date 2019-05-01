
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