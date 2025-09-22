<?php
namespace BookManager\Admin;

/**
 * Class Author
 *
 * Handles all author-related operations.
 *
 * @package BookManager\Admin
 */
class Author {

    public function render_authors_page() {
        echo '<div class="wrap"><h1>Authors</h1><p>Author management will go here.</p></div>';
    }

    /**
     * Add a new author.
     *
     * @param string $name Author name.
     *
     * @return int|false Post ID on success, false on failure.
     */
    public function add_author( $name ) {
        $author_id = wp_insert_post(
            [
                'post_type'   => 'author',
                'post_title'  => sanitize_text_field( $name ),
                'post_status' => 'publish',
            ]
        );

        if ( ! $author_id ) {
            return false;
        }

        return $author_id;
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
                'post_status' => 'publish',
            ]
        );
    }

    /**
     * Get single author by ID.
     *
     * @param int $id Author post ID.
     *
     * @return WP_Post|null
     */
    public function get_author( $id ) {
        return get_post( intval( $id ) );
    }
}
