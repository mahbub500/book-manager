jQuery(document).ready(function($) {

    // Function to activate a tab inside a wrapper
    function activateTab($wrapper, tab) {
        $wrapper.find('.nav-tab').removeClass('nav-tab-active');
        $wrapper.siblings('.tab-content').hide(); // hide sibling tab contents
        $wrapper.find('.nav-tab[data-tab="' + tab + '"]').addClass('nav-tab-active');
        $('#' + tab).show(); // show the tab content by ID
    }

    // Loop through each nav-tab-wrapper
    $('.nav-tab-wrapper').each(function(index) {
        var $wrapper = $(this);

        // Unique storage key per wrapper
        var wrapperId = $wrapper.attr('id') || 'tab-group-' + index;
        var storageKey = 'bm_active_tab_' + wrapperId;

        // Activate last active tab or first
        var activeTab = localStorage.getItem(storageKey) || $wrapper.find('.nav-tab').first().data('tab');
        activateTab($wrapper, activeTab);

        // Click event for tabs
        $wrapper.find('.nav-tab').on('click', function(e) {
            e.preventDefault();
            var tab = $(this).data('tab');
            activateTab($wrapper, tab);
            localStorage.setItem(storageKey, tab);
        });
    });

    /*add publisher*/
    $('#save_publisher').on('click', function(e) {
        e.preventDefault();

        var formData = new FormData();
        formData.append('action', 'bm_save_publisher'); // WordPress AJAX action
        formData.append('publisher_name', $('#publisher_name').val());
        formData.append('publisher_email', $('#publisher_email').val());
        formData.append('publisher_logo', $('#publisher_logo')[0].files[0]);
        formData.append('_wpnonce', BM_AJAX.nonce); // nonce for security

        $.ajax({
            url: BM_AJAX.ajax_url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if(response.success) {
                    alert('Publisher saved successfully!');
                    $('#publisher_name').val('');
                    $('#publisher_email').val('');
                    $('#publisher_logo').val('');
                } else {
                    alert('Error: ' + response.data);
                }
            },
            error: function(xhr, status, error) {
                alert('AJAX error: ' + error);
            }
        });
    });

});
