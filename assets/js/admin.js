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

    /*Save book*/

    $('#save_book').on('click', function(e) {
        e.preventDefault();

        let formData = new FormData();
        formData.append('action', 'bm_save_book'); // WordPress AJAX action
        formData.append('_wpnonce', BM_AJAX.nonce); // Nonce for security

        formData.append('book_name', $('#book_name').val());
        formData.append('book_author', $('#book_author').val());
        formData.append('book_publisher', $('#book_publisher').val());
        formData.append('book_price', $('#book_price').val());
        formData.append('book_isbn', $('#book_isbn').val());
        formData.append('book_year', $('#book_year').val());
        formData.append('book_description', $('#book_description').val());

        if ($('#book_image')[0].files.length > 0) {
            formData.append('book_image', $('#book_image')[0].files[0]);
        }

        $.ajax({
            url: BM_AJAX.ajax_url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if(response.success) {
                    alert('✅ Book saved successfully!');
                    $('#bm-book-form')[0].reset();
                } else {
                    alert('❌ Error: ' + response.data);
                }
            },
            error: function(xhr, status, error) {
                alert('⚠️ AJAX error: ' + error);
            }
        });
    });

    /*add publisher*/
    $('#save_publisher').on('click', function(e) {
        e.preventDefault();

        var formData = new FormData();
        formData.append('action', 'bm_save_publisher'); // WordPress AJAX action
        formData.append('publisher_name', $('#publisher_name').val());
        formData.append('publisher_address', $('#publisher_address').val());
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
                    $('#publisher_address').val('');
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

    /*add author*/
    $('#save_author').on('click', function(e) {
        e.preventDefault();

        var formData = new FormData();
        formData.append('action', 'bm_save_author'); // WordPress AJAX action
        formData.append('author_name', $('#author_name').val());
        formData.append('author_email', $('#author_email').val());
        formData.append('author_logo', $('#author_logo')[0].files[0]);
        formData.append('_wpnonce', BM_AJAX.nonce); // nonce for security

        $.ajax({
            url: BM_AJAX.ajax_url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if(response.success) {
                    alert('Author saved successfully!');
                    $('#author_name').val('');
                    $('#author_address').val('');
                    $('#author_logo').val('');
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
