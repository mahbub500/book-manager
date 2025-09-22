<?php
namespace BookManager\Common;

use BookManager\Functions\Hook;

/**
 * Class Book
 *
 * Handles all book-related operations: add, retrieve, and list books.
 *
 * @package BookManager\Admin
 */
class Ajax {

	use Hook;

	public function __construct() {
        // Register AJAX handler
        $this->ajax('bm_save_publisher', [ $this, 'save_publisher'] );
    } 

    /**
     * AJAX handler to save publisher
     */
    public function save_publisher() {
    	update_option( 'test', 1 );
        // check_ajax_referer('bm_publisher_nonce', '_wpnonce');

        if (empty($_POST['publisher_name'])) {
            wp_send_json_error('Publisher name is required.');
        }

        $name  = sanitize_text_field($_POST['publisher_name']);
        $email = sanitize_email($_POST['publisher_email']);

        $logo_id = 0;
        if (!empty($_FILES['publisher_logo']['name'] )) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
            require_once ABSPATH . 'wp-admin/includes/media.php';
            require_once ABSPATH . 'wp-admin/includes/image.php';

            $attachment_id = media_handle_upload( 'publisher_logo', 0 );
            if (is_wp_error( $attachment_id )) {
                wp_send_json_error( $attachment_id->get_error_message() );
            }
            $logo_id = $attachment_id;
        }

        $post_id = wp_insert_post([
            'post_title'  => $name,
            'post_type'   => 'publisher',
            'post_status' => 'publish',
            'meta_input'  => [
                'publisher_email' => $email,
                'publisher_logo'  => $logo_id,
            ],
        ]);

        if ( $post_id ) {
            wp_send_json_success( $post_id );
        } else {
            wp_send_json_error('Could not save publisher.');
        }
    }

}
