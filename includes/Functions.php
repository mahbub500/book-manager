<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Get all allowed admin pages dynamically
 *
 * @return array
 */
if ( ! function_exists( 'get_allowed_pages' ) ) {
    function get_allowed_pages() {
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

        foreach ( $submenus as $slug ) {
            $pages[] = "book-manager_page_{$slug}";
        }

        return $pages;
    }
}

/**
 * Get plugin folder name dynamically
 *
 * @return string
 */
if ( ! function_exists( 'get_plugin_folder' ) ) {
    function get_plugin_folder() {
        return basename( BOOK_MANAGER_PATH );
    }
}

/**
 * Prints information about a variable in a more readable format.
 *
 * @param mixed $data The variable you want to display.
 * @param bool  $admin_only Should it display in wp-admin area only
 * @param bool  $hide_adminbar Should it hide the admin bar
 */
if ( ! function_exists( 'pri' ) ) {
    function pri( $data, $admin_only = true, $hide_adminbar = true ) {
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

/**
 * Get all publishers.
 *
 * @return array Array of WP_Post objects.
 */
if ( ! function_exists( 'get_publishers' ) ) {
    function get_publishers() {
        return get_posts(
            [
                'post_type'   => 'publisher',
                'numberposts' => -1,
            ]
        );
    }
}

/**
 * Get all authors.
 *
 * @return array Array of WP_Post objects.
 */
if ( ! function_exists( 'get_authors' ) ) {
    function get_authors() {
        return get_posts(
            [
                'post_type'   => 'author',
                'numberposts' => -1,
            ]
        );
    }
}

/**
 * Get all books.
 *
 * @return array Array of WP_Post objects.
 */
if ( ! function_exists( 'get_books' ) ) {
    function get_books() {
        return get_posts(
            [
                'post_type'   => 'book',
                'post_status'    => 'publish',
                'numberposts' => -1,
            ]
        );
    }
}
