// alert( 'HEllo' );
jQuery(document).ready(function($) {
    // Function to activate a tab
    function activateTab(tab) {
        $('.nav-tab').removeClass('nav-tab-active');
        $('.tab-content').hide();
        $('.nav-tab[data-tab="' + tab + '"]').addClass('nav-tab-active');
        $('#' + tab).show();
    }

    // Get last active tab from localStorage or default to 'general'
    var activeTab = localStorage.getItem('book_manager_active_tab') || 'general';
    activateTab(activeTab);

    // Click event
    $('.nav-tab').on('click', function(e) {
        e.preventDefault();
        var tab = $(this).data('tab');
        activateTab(tab);

        // Save selected tab in localStorage
        localStorage.setItem('book_manager_active_tab', tab);
    });
});