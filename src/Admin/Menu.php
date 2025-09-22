<?php
namespace BookManager\Admin;

class Menu {
    public function register() {
        add_action('admin_menu', [$this, 'add_admin_menu']);
    }

    public function add_admin_menu() {
        // Parent menu
        add_menu_page(
            __('Book Manager', 'book-manager'),
            __('Book Manager', 'book-manager'),
            'manage_options',
            'book-manager',
            [$this, 'render_list_page'],
            'dashicons-book',
            20
        );

        // Add Book submenu
        add_submenu_page(
            'book-manager',
            __('Add Book', 'book-manager'),
            __('Add Book', 'book-manager'),
            'manage_options',
            'book-manager-add',
            [$this, 'render_add_page']
        );

        // List Books submenu
        add_submenu_page(
            'book-manager',
            __('List Books', 'book-manager'),
            __('List Books', 'book-manager'),
            'manage_options',
            'book-manager',
            [$this, 'render_list_page']
        );

        // Settings submenu
        add_submenu_page(
            'book-manager',
            __('Settings', 'book-manager'),
            __('Settings', 'book-manager'),
            'manage_options',
            'book-manager-settings',
            [$this, 'render_settings_page']
        );

        // ✅ Reports submenu
        add_submenu_page(
            'book-manager',
            __('Reports', 'book-manager'),
            __('Reports', 'book-manager'),
            'manage_options',
            'book-manager-reports',
            [$this, 'render_reports_page']
        );
    }

    public function render_add_page() {
        echo '<div class="wrap"><h1>Add Book</h1><p>Form will go here.</p></div>';
    }

    public function render_list_page() {
        echo '<div class="wrap"><h1>List Books</h1><p>Book list will go here.</p></div>';
    }

    public function render_settings_page() {
    ?>
    <div class="wrap">
        <h1>Book Manager Settings</h1>

        <!-- Tab Navigation -->
        <h2 class="nav-tab-wrapper">
            <a href="javascript:void(0);" class="nav-tab" data-tab="general">General</a>
            <a href="javascript:void(0);" class="nav-tab" data-tab="advanced">Advanced</a>
        </h2>

        <!-- Tab Content -->
        <div id="general" class="tab-content">
            <h3>General Settings</h3>
            <p>General settings form goes here.</p>
        </div>

        <div id="advanced" class="tab-content">
            <h3>Advanced Settings</h3>
            <p>Advanced settings form goes here.</p>
        </div>
    </div>

    <script type="text/javascript">
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
    </script>

    <style>
        .tab-content { display: none; }
        .nav-tab-active { font-weight: bold; }
    </style>
    <?php
}




    public function render_reports_page() {
        echo '<div class="wrap"><h1>Book Reports</h1><p>Reports content will go here.</p></div>';
    }
}
