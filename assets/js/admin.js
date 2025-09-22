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
            console.log(tab); // should log both book and publisher tabs
            activateTab($wrapper, tab);
            localStorage.setItem(storageKey, tab);
        });
    });

});
