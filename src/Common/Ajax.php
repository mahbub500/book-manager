<?php
/**
 * Ajax Handlers for Book Manager
 *
 * @package BookManager\Common
 */

namespace BookManager\Common;

use BookManager\Trait\Hook;

/**
 * Class Ajax
 *
 * Handles all AJAX operations for Books, Authors, and Publishers.
 */
class Ajax {

	use Hook;

	/**
	 * Constructor
	 *
	 * Registers AJAX handlers.
	 */
	public function __construct() {
		$this->ajax( 'bm_save_publisher', [ $this, 'save_publisher' ] );
		$this->ajax( 'bm_save_author', [ $this, 'save_author' ] );
		$this->ajax( 'bm_save_book', [ $this, 'save_book' ] );
	}

	/**
	 * Save Book via AJAX.
	 *
	 * @return void
	 */
	public function save_book() {
		check_ajax_referer( 'bm_publisher_nonce', '_wpnonce' );

		if ( empty( $_POST['book_name'] ) ) {
			wp_send_json_error( __( 'Book name is required.', 'book-manager' ) );
		}

		$name        = sanitize_text_field( wp_unslash( $_POST['book_name'] ) );
		$author_id   = intval( $_POST['book_author'] );
		$publisher_id= intval( $_POST['book_publisher'] );
		$price       = floatval( $_POST['book_price'] );
		$isbn        = sanitize_text_field( wp_unslash( $_POST['book_isbn'] ) );
		$year        = intval( $_POST['book_year'] );
		$description = sanitize_textarea_field( wp_unslash( $_POST['book_description'] ) );

		// Handle image upload.
		$image_id = 0;
		if ( ! empty( $_FILES['book_image']['name'] ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
			require_once ABSPATH . 'wp-admin/includes/media.php';
			require_once ABSPATH . 'wp-admin/includes/image.php';

			$attachment_id = media_handle_upload( 'book_image', 0 );
			if ( is_wp_error( $attachment_id ) ) {
				wp_send_json_error( $attachment_id->get_error_message() );
			}
			$image_id = $attachment_id;
		}

		$post_id = wp_insert_post(
			[
				'post_title'   => $name,
				'post_type'    => 'book',
				'post_status'  => 'publish',
				'post_content' => $description,
				'meta_input'   => [
					'book_author'    => $author_id,
					'book_publisher' => $publisher_id,
					'book_price'     => $price,
					'book_isbn'      => $isbn,
					'book_year'      => $year,
					'book_image'     => $image_id,
				],
			]
		);

		if ( $post_id ) {
			wp_send_json_success( $post_id );
		} else {
			wp_send_json_error( __( 'Could not save book.', 'book-manager' ) );
		}
	}

	/**
	 * Save Publisher via AJAX.
	 *
	 * @return void
	 */
	public function save_publisher() {
		check_ajax_referer( 'bm_publisher_nonce', '_wpnonce' );

		if ( empty( $_POST['publisher_name'] ) ) {
			wp_send_json_error( __( 'Publisher name is required.', 'book-manager' ) );
		}

		$name    = sanitize_text_field( wp_unslash( $_POST['publisher_name'] ) );
		$address = sanitize_textarea_field( wp_unslash( $_POST['publisher_address'] ) );

		// Handle file upload.
		$logo_id = 0;
		if ( ! empty( $_FILES['publisher_logo']['name'] ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
			require_once ABSPATH . 'wp-admin/includes/media.php';
			require_once ABSPATH . 'wp-admin/includes/image.php';

			$attachment_id = media_handle_upload( 'publisher_logo', 0 );
			if ( is_wp_error( $attachment_id ) ) {
				wp_send_json_error( $attachment_id->get_error_message() );
			}
			$logo_id = $attachment_id;
		}

		$post_id = wp_insert_post(
			[
				'post_title'  => $name,
				'post_type'   => 'publisher',
				'post_status' => 'publish',
				'meta_input'  => [
					'publisher_address' => $address,
					'publisher_logo'    => $logo_id,
				],
			]
		);

		if ( $post_id ) {
			wp_send_json_success( $post_id );
		} else {
			wp_send_json_error( __( 'Could not save publisher.', 'book-manager' ) );
		}
	}

	/**
	 * Save Author via AJAX.
	 *
	 * @return void
	 */
	public function save_author() {
		check_ajax_referer( 'bm_publisher_nonce', '_wpnonce' );

		if ( empty( $_POST['author_name'] ) ) {
			wp_send_json_error( __( 'Author name is required.', 'book-manager' ) );
		}

		$name  = sanitize_text_field( wp_unslash( $_POST['author_name'] ) );
		$email = sanitize_email( wp_unslash( $_POST['author_email'] ) );

		// Handle file upload.
		$logo_id = 0;
		if ( ! empty( $_FILES['author_logo']['name'] ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
			require_once ABSPATH . 'wp-admin/includes/media.php';
			require_once ABSPATH . 'wp-admin/includes/image.php';

			$attachment_id = media_handle_upload( 'author_logo', 0 );
			if ( is_wp_error( $attachment_id ) ) {
				wp_send_json_error( $attachment_id->get_error_message() );
			}
			$logo_id = $attachment_id;
		}

		$post_id = wp_insert_post(
			[
				'post_title'  => $name,
				'post_type'   => 'author',
				'post_status' => 'publish',
				'meta_input'  => [
					'author_email' => $email,
					'author_logo'  => $logo_id,
				],
			]
		);

		if ( $post_id ) {
			wp_send_json_success( $post_id );
		} else {
			wp_send_json_error( __( 'Could not save author.', 'book-manager' ) );
		}
	}
}
