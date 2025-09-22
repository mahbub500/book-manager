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
        $this->ajax('bm_save_author', [ $this, 'save_author'] );
    } 

    /**
     * AJAX handler to save publisher
     */
    public function save_publisher() {
    	$response = array(
			'status'  => 0,
			'message' => __( 'Unauthorized!', 'book-manager' ),
		);

		if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'bm_publisher_nonce' ) ) {
			wp_send_json( $response );
		}

        if (empty($_POST['publisher_name'])) {
            wp_send_json_error('Publisher name is required.');
        }

        $name  		= sanitize_text_field($_POST['publisher_name']);
        $address 	= sanitize_textarea_field($_POST['publisher_address']);

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
                'publisher_address' => $address,
                'publisher_logo'  => $logo_id,
            ],
        ]);

        if ( $post_id ) {
            wp_send_json_success( $post_id );
        } else {
            wp_send_json_error('Could not save publisher.');
        }
    }

    /**
     * AJAX handler to save publisher
     */
    public function save_author() {

        $response = array(
			'status'  => 0,
			'message' => __( 'Unauthorized!', 'book-manager' ),
		);

		if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'bm_publisher_nonce' ) ) {
			wp_send_json( $response );
		}

        if (empty($_POST['author_name'])) {
            wp_send_json_error('Publisher name is required.');
        }

        $name  = sanitize_text_field($_POST['author_name']);
        $email = sanitize_email($_POST['author_email']);

        $logo_id = 0;
        if (!empty($_FILES['author_logo']['name'] )) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
            require_once ABSPATH . 'wp-admin/includes/media.php';
            require_once ABSPATH . 'wp-admin/includes/image.php';

            $attachment_id = media_handle_upload( 'author_logo', 0 );
            if (is_wp_error( $attachment_id )) {
                wp_send_json_error( $attachment_id->get_error_message() );
            }
            $logo_id = $attachment_id;
        }

        $post_id = wp_insert_post([
            'post_title'  => $name,
            'post_type'   => 'author',
            'post_status' => 'publish',
            'meta_input'  => [
                'author_email' => $email,
                'author_logo'  => $logo_id,
            ],
        ]);

        if ( $post_id ) {
            wp_send_json_success( $post_id );
        } else {
            wp_send_json_error('Could not save author.');
        }
    }

}
