<?php
namespace BookManager\Admin;

use BookManager\Functions\Hook;

/**
 * Class Publisher
 *
 * Handles all publisher-related operations.
 *
 * @package BookManager\Admin
 */
class Publisher {
	use Hook;



	   

    


	public function render_publishers_page() {
	    ?>
	    <div class="wrap">
		    <h1>Publisher Manager</h1>

		    <!-- Tab Navigation -->
		    <h2 id="publisher-tabs" class="nav-tab-wrapper">
			    <a href="javascript:void(0);" class="nav-tab nav-tab-active" data-tab="bm-publisher-list">Publisher List</a>
			    <a href="javascript:void(0);" class="nav-tab" data-tab="bm-publisher-add">Add Publisher</a>
			</h2>



		    <!-- Tab Content -->
		    <div id="bm-publisher-list" class="tab-content" style="display:block;">
		        <h3>List of Publishers</h3>
		        <p>List of all publishers will go here.</p>
		    </div>

		    <div id="bm-publisher-add" class="tab-content" style="display:none;">
		        <h3>Add Publisher</h3>
		        <form method="post" enctype="multipart/form-data">
		            <table class="form-table">
		                <tr>
		                    <th><label for="publisher_name">Publisher Name</label></th>
		                    <td><input type="text" name="publisher_name" id="publisher_name" class="regular-text" required></td>
		                </tr>
		                <tr>
		                    <th><label for="publisher_email">Publisher Email</label></th>
		                    <td><input type="email" name="publisher_email" id="publisher_email" class="regular-text"></td>
		                </tr>
		                <tr>
		                    <th><label for="publisher_logo">Publisher Logo</label></th>
		                    <td><input type="file" name="publisher_logo" id="publisher_logo"></td>
		                </tr>
		            </table>
		            <p class="submit">
		                <input type="submit" name="save_publisher" id="save_publisher" class="button button-primary" value="Add Publisher">
		            </p>
		        </form>
		    </div>
		</div>

	    <?php 
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
