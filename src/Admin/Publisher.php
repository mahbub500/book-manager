<?php
namespace BookManager\Admin;

/**
 * Class Publisher
 *
 * Handles all publisher-related operations.
 *
 * @package BookManager\Admin
 */
class Publisher {

	public function render_publishers_page() {
        echo '<div class="wrap"><h1>Publishers</h1><p>Publisher management will go here.</p></div>';
    }

	/**
	 * Add a new publisher.
	 *
	 * @param string $name Publisher name.
	 *
	 * @return int|false Post ID on success, false on failure.
	 */
	public function add_publisher( $name ) {
		$publisher_id = wp_insert_post(
			[
				'post_type'   => 'publisher',
				'post_title'  => sanitize_text_field( $name ),
				'post_status' => 'publish',
			]
		);

		if ( ! $publisher_id ) {
			return false;
		}

		return $publisher_id;
	}

	/**
	 * Get all publishers.
	 *
	 * @return array Array of WP_Post objects.
	 */
	public function get_publishers() {
		return get_posts(
			[
				'post_type'   => 'publisher',
				'numberposts' => -1,
				'post_status' => 'publish',
			]
		);
	}

	/**
	 * Get single publisher by ID.
	 *
	 * @param int $id Publisher post ID.
	 *
	 * @return WP_Post|null
	 */
	public function get_publisher( $id ) {
		return get_post( intval( $id ) );
	}
}
