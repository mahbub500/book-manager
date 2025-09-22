<?php
namespace BookManager\Admin;
use \BookManager\Admin\Author;
use \BookManager\Admin\Publisher;

/**
 * Class Book
 *
 * Handles all book-related operations: add, retrieve, and list books.
 *
 * @package BookManager\Admin
 */
class Book {

    public function render_list_page() {
    // Get authors and publishers (assuming you have get_authors() and get_publishers())   

    $authors    = get_publishers();
    $publishers = get_authors();
    ?>
    <div class="wrap">
        <h1>Book Manager</h1>

        <!-- Tab Navigation -->
        <h2 id="book-tabs" class="nav-tab-wrapper">
            <a href="javascript:void(0);" class="nav-tab nav-tab-active" data-tab="book-list">Book List</a>
            <a href="javascript:void(0);" class="nav-tab" data-tab="add-book">Add Book</a>
        </h2>

            <!-- Book List -->
    <!-- Book List -->
    <div id="book-list" class="tab-content" style="display:block;">
        <h3><?php esc_html_e( 'Book List', 'book-manager' ); ?></h3>

        <?php
        $books = get_books();

        if ( ! empty( $books ) ) : ?>
            <table class="widefat fixed striped">
                <thead>
                    <tr>
                        <th><?php esc_html_e( 'ID', 'book-manager' ); ?></th>
                        <th><?php esc_html_e( 'Book Name', 'book-manager' ); ?></th>
                        <th><?php esc_html_e( 'Author', 'book-manager' ); ?></th>
                        <th><?php esc_html_e( 'Publisher', 'book-manager' ); ?></th>
                        <th><?php esc_html_e( 'Price', 'book-manager' ); ?></th>
                        <th><?php esc_html_e( 'ISBN', 'book-manager' ); ?></th>
                        <th><?php esc_html_e( 'Year', 'book-manager' ); ?></th>
                        <th><?php esc_html_e( 'Image', 'book-manager' ); ?></th>
                        <th><?php esc_html_e( 'Actions', 'book-manager' ); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ( $books as $book ) : 
                        $author_id    = get_post_meta( $book->ID, 'book_author', true );
                        $publisher_id = get_post_meta( $book->ID, 'book_publisher', true );
                        $price        = get_post_meta( $book->ID, 'book_price', true );
                        $isbn         = get_post_meta( $book->ID, 'book_isbn', true );
                        $year         = get_post_meta( $book->ID, 'book_year', true );
                        $image_id     = get_post_meta( $book->ID, 'book_image', true );

                        $author_name    = $author_id ? get_the_title( $author_id ) : '';
                        $publisher_name = $publisher_id ? get_the_title( $publisher_id ) : '';
                        $image_url      = $image_id ? wp_get_attachment_image_url( $image_id, 'thumbnail' ) : '';
                    ?>
                        <tr>
                            <td><?php echo esc_html( $book->ID ); ?></td>
                            <td><?php echo esc_html( get_the_title( $book->ID ) ); ?></td>
                            <td><?php echo esc_html( $author_name ); ?></td>
                            <td><?php echo esc_html( $publisher_name ); ?></td>
                            <td><?php echo esc_html( $price ); ?></td>
                            <td><?php echo esc_html( $isbn ); ?></td>
                            <td><?php echo esc_html( $year ); ?></td>
                            <td>
                                <?php if ( $image_url ) : ?>
                                    <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( get_the_title( $book->ID ) ); ?>" width="50" height="50">
                                <?php else : ?>
                                    <span><?php esc_html_e( 'No Image', 'book-manager' ); ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?php echo esc_url( add_query_arg( [ 'action' => 'edit', 'book_id' => $book->ID ] ) ); ?>" class="button button-small"><?php esc_html_e( 'Edit', 'book-manager' ); ?></a>
                                <a href="<?php echo esc_url( wp_nonce_url( add_query_arg( [ 'action' => 'delete', 'book_id' => $book->ID ] ), 'bm_delete_book_' . $book->ID ) ); ?>" class="button button-small button-danger" onclick="return confirm('<?php esc_attr_e( 'Are you sure you want to delete this book?', 'book-manager' ); ?>');"><?php esc_html_e( 'Delete', 'book-manager' ); ?></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p><?php esc_html_e( 'No books found.', 'book-manager' ); ?></p>
        <?php endif; ?>
    </div>




        <!-- Add Book Form -->
        <div id="add-book" class="tab-content" style="display:none;">
            <h3>Add Book</h3>
            <form method="post" enctype="multipart/form-data" id="bm-book-form">
                <table class="form-table">
                    <tr>
                        <th><label for="book_name">Book Name</label></th>
                        <td><input type="text" name="book_name" id="book_name" class="regular-text" required></td>
                    </tr>
                    <tr>
                        <th><label for="book_author">Author</label></th>
                        <td>
                            <select name="book_author" id="book_author" required>
                                <option value="">Select Author</option>
                                <?php foreach ( $authors as $author ) : ?>
                                    <option value="<?php echo esc_attr( $author->ID ); ?>">
                                        <?php echo esc_html( $author->post_title ); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="book_publisher">Publisher</label></th>
                        <td>
                            <select name="book_publisher" id="book_publisher" required>
                                <option value="">Select Publisher</option>
                                <?php foreach ( $publishers as $publisher ) : ?>
                                    <option value="<?php echo esc_attr( $publisher->ID ); ?>">
                                        <?php echo esc_html( $publisher->post_title ); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="book_image">Book Cover</label></th>
                        <td><input type="file" name="book_image" id="book_image"></td>
                    </tr>
                    <tr>
                        <th><label for="book_price">Price</label></th>
                        <td><input type="number" step="0.01" name="book_price" id="book_price" class="regular-text" required></td>
                    </tr>
                    <tr>
                        <th><label for="book_isbn">ISBN</label></th>
                        <td><input type="text" name="book_isbn" id="book_isbn" class="regular-text" required></td>
                    </tr>
                    <tr>
                        <th><label for="book_year">Publication Year</label></th>
                        <td><input type="number" name="book_year" id="book_year" class="regular-text" placeholder="2025"></td>
                    </tr>
                    <tr>
                        <th><label for="book_description">Description</label></th>
                        <td><textarea name="book_description" id="book_description" rows="5" class="large-text"></textarea></td>
                    </tr>
                </table>

                <p class="submit">
                    <input type="submit" name="save_book" id="save_book" class="button button-primary" value="Add Book">
                </p>
            </form>
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
