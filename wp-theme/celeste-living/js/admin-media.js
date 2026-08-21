/**
 * Celeste Living — Admin Meta Box Helpers
 * 1) Media Library picker for image fields (.clg-image-field)
 * 2) Add/remove rows for repeater groups (.clg-repeater)
 */
(function($) {
    'use strict';

    // ===== SINGLE IMAGE PICKER =====
    $(document).on('click', '.clg-image-upload', function(e) {
        e.preventDefault();

        var $button = $(this);
        var $container = $button.closest('.clg-image-field');
        var $input = $container.find('.clg-image-url');
        var $preview = $container.find('.clg-image-preview');
        var $remove = $container.find('.clg-image-remove');

        var frame = wp.media({
            title: 'Select Image',
            button: { text: 'Use This Image' },
            multiple: false,
            library: { type: 'image' }
        });

        frame.on('select', function() {
            var attachment = frame.state().get('selection').first().toJSON();
            var url = attachment.url;

            // Use large size if available, otherwise full
            if (attachment.sizes && attachment.sizes.large) {
                url = attachment.sizes.large.url;
            } else if (attachment.sizes && attachment.sizes.full) {
                url = attachment.sizes.full.url;
            }

            $input.val(url);
            $preview.find('img').attr('src', url);
            $preview.show();
            $remove.show();
        });

        frame.open();
    });

    $(document).on('click', '.clg-image-remove', function(e) {
        e.preventDefault();

        var $button = $(this);
        var $container = $button.closest('.clg-image-field');
        var $input = $container.find('.clg-image-url');
        var $preview = $container.find('.clg-image-preview');

        $input.val('');
        $preview.hide();
        $button.hide();
    });

    // Keep the preview in sync when a URL is pasted/typed directly
    $(document).on('change keyup', '.clg-image-url', function() {
        var $container = $(this).closest('.clg-image-field');
        var url = $(this).val();
        if (url) {
            $container.find('.clg-image-preview img').attr('src', url);
            $container.find('.clg-image-preview').show();
            $container.find('.clg-image-remove').show();
        } else {
            $container.find('.clg-image-preview').hide();
            $container.find('.clg-image-remove').hide();
        }
    });

    // ===== REPEATER: ADD / REMOVE ROWS =====

    // Re-number the name="key[i][field]" indexes after add/remove
    function reindexRepeater($repeater) {
        var key = $repeater.data('key');
        $repeater.find('.clg-repeater-item').each(function(i) {
            $(this).find('input, textarea').each(function() {
                var name = $(this).attr('name');
                if (!name) return;
                var newName = name.replace(/^(.+?)\[\d+\]/, key + '[' + i + ']');
                $(this).attr('name', newName);
            });
        });
    }

    $(document).on('click', '.clg-repeater-add', function(e) {
        e.preventDefault();
        var $repeater = $(this).closest('.clg-repeater');
        var $items = $repeater.find('.clg-repeater-items');
        var $last = $items.find('.clg-repeater-item').last();

        if ($last.length) {
            var $clone = $last.clone();
            $clone.find('input, textarea').val('');
            $items.append($clone);
        } else {
            // Build an empty row from the declared field list
            var key = $repeater.data('key');
            var fields = String($repeater.data('fields') || 'title,text').split(',');
            var textareaFields = ['text', 'description', 'answer', 'bio', 'items', 'get_text', 'caption'];
            var html = '<div class="clg-repeater-item" style="background:#f9f9f9;border:1px solid #ddd;padding:10px;margin:5px 0;border-radius:4px;position:relative;">';
            fields.forEach(function(field) {
                var label = field.charAt(0).toUpperCase() + field.slice(1).replace(/_/g, ' ');
                html += '<label><small>' + label + '</small></label>';
                if (textareaFields.indexOf(field) !== -1) {
                    html += '<textarea name="' + key + '[0][' + field + ']" rows="2" style="width:100%;"></textarea>';
                } else {
                    html += '<input type="text" name="' + key + '[0][' + field + ']" value="" style="width:100%;" />';
                }
            });
            html += '<button type="button" class="button-link clg-repeater-remove" style="color:#a00;margin-top:6px;">Remove item</button></div>';
            $items.append(html);
        }
        reindexRepeater($repeater);
    });

    $(document).on('click', '.clg-repeater-remove', function(e) {
        e.preventDefault();
        var $repeater = $(this).closest('.clg-repeater');
        $(this).closest('.clg-repeater-item').remove();
        reindexRepeater($repeater);
    });

})(jQuery);
