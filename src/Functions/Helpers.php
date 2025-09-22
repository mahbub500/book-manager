<?php
namespace BookManager\Functions;

class Helpers {

    /**
     * Get all allowed admin pages dynamically
     *
     * @return array
     */
    public static function get_allowed_pages() {
        $pages = [];

        // Top-level menu slug
        $pages[] = 'toplevel_page_book-manager';

        // Submenu pages slugs
        $submenus = [
            'book-manager-add',
            'book-manager-settings',
            'book-manager-reports',
            'book-manager-publishers',
            'book-manager-authors',
        ];

        foreach ($submenus as $slug) {
            $pages[] = "book-manager_page_{$slug}";
        }

        return $pages;
    }

    /**
     * Get plugin folder name dynamically
     *
     * @return string
     */
    public static function get_plugin_folder() {
        return basename(BOOK_MANAGER_PATH);
    }

    /**
     * Prints information about a variable in a more readable format.
     *
     * @param mixed $data The variable you want to display.
     * @param bool  $admin_only Should it display in wp-admin area only
     * @param bool  $hide_adminbar Should it hide the admin bar
     */
    public static function pri( $data, $admin_only = true, $hide_adminbar = true ) {
        if ( $admin_only && ! current_user_can( 'manage_options' ) ) {
            return;
        }

        echo '<pre>';
        if ( is_object( $data ) || is_array( $data ) ) {
            print_r( $data );
        } else {
            var_dump( $data );
        }
        echo '</pre>';

        if ( is_admin() && $hide_adminbar ) {
            echo '<style>#adminmenumain{display:none;}</style>';
        }
    }
}
