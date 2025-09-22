<?php
namespace BookManager\Admin;
use BookManager\Functions\Helpers;
use BookManager\Admin\Book;
use BookManager\Admin\Author;
use BookManager\Admin\Publisher;

use BookManager\Functions\Hook;

class Menu {
    use Hook;
    public function register() {
        $this->action('admin_menu', [$this, 'add_admin_menu']);
        $this->action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
    }

    public function enqueue_admin_assets( $hook ) {
        if ( ! in_array( $hook, Helpers::get_allowed_pages() )) {
            return;
        }

        wp_enqueue_style(
            'book-manager-admin',
            BOOK_MANAGER_URL . 'assets/css/admin.css',
            [],
            BOOK_MANAGER_VERSION
        );

        wp_enqueue_script(
            'book-manager-admin',
            BOOK_MANAGER_URL . 'assets/js/admin.js',
            ['jquery'],
            BOOK_MANAGER_VERSION,
            true
        );

         wp_localize_script('book-manager-admin', 'BM_AJAX', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('bm_publisher_nonce')
        ]);
    }

    public function add_admin_menu() {

        $book       = new Book();
        $author     = new Author();
        $publisher  = new Publisher();

        // Parent menu
        add_menu_page(
            __('Book Manager', 'book-manager'),
            __('Book Manager', 'book-manager'),
            'manage_options',
            'book-manager',
            [$book, 'render_list_page'],
            'dashicons-book',
            20
        );

        // Publisher submenu
        add_submenu_page(
            'book-manager',
            __('Publishers', 'book-manager'),
            __('Publishers', 'book-manager'),
            'manage_options',
            'book-manager-publishers',
            [$publisher, 'render_publishers_page']
        );

        // Author submenu
        add_submenu_page(
            'book-manager',
            __('Authors', 'book-manager'),
            __('Authors', 'book-manager'),
            'manage_options',
            'book-manager-authors',
            [$author, 'render_authors_page']
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

        // Reports submenu
        add_submenu_page(
            'book-manager',
            __('Reports', 'book-manager'),
            __('Reports', 'book-manager'),
            'manage_options',
            'book-manager-reports',
            [$this, 'render_reports_page']
        );

        
    }    

   

    public function render_settings_page() {
        echo '<div class="wrap"><h1>Book Settings</h1><p>Settings content will go here.</p></div>';
    }

    public function render_reports_page() {
        echo '<div class="wrap"><h1>Book Reports</h1><p>Reports content will go here.</p></div>';
    }

    

    
}
