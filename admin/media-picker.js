jQuery(document).ready(function($){

    function setupPicker(buttonClass, fieldClass, previewSelector, mediaType) {

        $(document).on('click', buttonClass, function(e){
            e.preventDefault();

            const wrapper = $(this).closest('.aig-media-wrapper');
            const field = wrapper.find(fieldClass);
            const preview = wrapper.find(previewSelector);

            const frame = wp.media({
                title: 'Select ' + mediaType,
                button: { text: 'Use this ' + mediaType },
                library: { type: mediaType === 'icon' ? 'image' : 'audio' },
                multiple: false
            });

            frame.on('select', function(){
                const attachment = frame.state().get('selection').first().toJSON();

                field.val(attachment.id);

                if (mediaType === 'icon') {
                    preview.attr('src', attachment.url).show();
                } else {
                    preview.text(attachment.filename);
                }
            });

            frame.open();
        });
    }

    // Remove icon
    $(document).on('click', '.aig-remove-icon', function(e){
        e.preventDefault();
        const wrapper = $(this).closest('.aig-media-wrapper');
        wrapper.find('.aig-icon-field').val('');
        wrapper.find('.aig-preview').attr('src', '').hide();
    });

    // Remove audio
    $(document).on('click', '.aig-remove-audio', function(e){
        e.preventDefault();
        const wrapper = $(this).closest('.aig-media-wrapper');
        wrapper.find('.aig-audio-field').val('');
        wrapper.find('.aig-audio-preview').text('');
    });

    // Remove entire row
    $(document).on('click', '.aig-remove-row', function(e){
        e.preventDefault();
        $(this).closest('tr').remove();
    });

    setupPicker('.aig-select-icon', '.aig-icon-field', '.aig-preview', 'icon');
    setupPicker('.aig-select-audio', '.aig-audio-field', '.aig-audio-preview', 'audio');

});
