<?php
namespace BookManager\Admin;

/**
 * Class Book
 *
 * Handles all book-related operations: add, retrieve, and list books.
 *
 * @package BookManager\Admin
 */
class Book {

     public function render_list_page() {
        ?>
        <div class="wrap">
            <h1>Book Manager</h1>

            <!-- Tab Navigation -->
            <h2 class="nav-tab-wrapper">
                <a href="javascript:void(0);" class="nav-tab nav-tab-active" data-tab="book-list">Book List</a>
                <a href="javascript:void(0);" class="nav-tab" data-tab="add-book">Add Book</a>
            </h2>

            <!-- Tab Content -->
            <div id="book-list" class="tab-content" style="display:block;">
                <h3>Book List</h3>
                <p>List of all books will go here.</p>
            </div>

            <div id="add-book" class="tab-content" style="display:none;">
                <h3>Add Book</h3>
                <p>Add book in main list.</p>
            </div>
        </div>
        <?php 
    }

    /**
     * Add a new book.
     *
     * @param array $data  Book form data.
     * @param array $files Book uploaded files.
     *
     * @return int|false Post ID on success, false on failure.
     */
    public function add_book( $data, $files ) {

        $book_id = wp_insert_post(
            [
                'post_type'    => 'book',
                'post_title'   => sanitize_text_field( $data['book_name'] ),
                'post_content' => sanitize_textarea_field( $data['book_writer'] ),
                'post_status'  => 'publish',
            ]
        );

        if ( ! $book_id ) {
            return false;
        }

        // Save custom fields
        if ( ! empty( $data['book_price'] ) ) {
            update_post_meta( $book_id, 'book_price', floatval( $data['book_price'] ) );
        }

        if ( ! empty( $data['book_isbn'] ) ) {
            update_post_meta( $book_id, 'book_isbn', sanitize_text_field( $data['book_isbn'] ) );
        }

        // Assign publisher
        if ( ! empty( $data['book_publisher'] ) ) {
            wp_set_post_terms( $book_id, [ intval( $data['book_publisher'] ) ], 'publisher' );
        }

        // Assign author
        if ( ! empty( $data['book_author'] ) ) {
            wp_set_post_terms( $book_id, [ intval( $data['book_author'] ) ], 'book_author' );
        }

        // Handle featured image upload
        if ( ! empty( $files['book_image']['name'] ) ) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
            require_once ABSPATH . 'wp-admin/includes/image.php';
            require_once ABSPATH . 'wp-admin/includes/media.php';

            $attachment_id = media_handle_upload( 'book_image', $book_id );

            if ( ! is_wp_error( $attachment_id ) ) {
                set_post_thumbnail( $book_id, $attachment_id );
            }
        }

        return $book_id;
    }

    /**
     * Get all books.
     *
     * @param array $args Optional. Additional query arguments.
     *
     * @return array Array of WP_Post objects.
     */
    public function get_books( $args = [] ) {
        $default_args = [
            'post_type'   => 'book',
            'post_status' => 'publish',
            'numberposts' => -1,
        ];

        $query_args = wp_parse_args( $args, $default_args );

        return get_posts( $query_args );
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
            ]
        );
    }

    /**
     * Get all authors.
     *
     * @return array Array of WP_Post objects.
     */
    public function get_authors() {
        return get_posts(
            [
                'post_type'   => 'author',
                'numberposts' => -1,
            ]
        );
    }

    /**
     * Get single book by ID.
     *
     * @param int $id Book post ID.
     *
     * @return WP_Post|null
     */
    public function get_book( $id ) {
        return get_post( intval( $id ) );
    }
}
