<?php
namespace BookManager\Admin;
use \BookManager\Admin\Author;
use \BookManager\Admin\Publisher;

if ( ! class_exists( 'WP_List_Table' ) ) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * Class Book
 *
 * Handles all book-related operations: add, retrieve, and list books.
 *
 * @package BookManager\Admin
 */
class Book extends \WP_List_Table {

    

    public function __construct() {
        parent::__construct([
            'singular' => __( 'Book', 'book-manager' ),
            'plural'   => __( 'Books', 'book-manager' ),
            'ajax'     => false,
        ]);
    }

    public function render_list_page() {
        // Get authors and publishers (assuming you have get_authors() and get_publishers())   

        $authors    = get_authors();
        $publishers = get_publishers();

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

            <form method="get">
                <input type="hidden" name="page" value="<?php echo esc_attr( $_REQUEST['page'] ); ?>">
                <?php
                $this->process_bulk_action();
                $this->prepare_items();
                $this->search_box( __( 'Search Books', 'book-manager' ), 'book-search' );
                $this->display();
                ?>
            </form>
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
    public function get_sortable_columns() {
        return [
            'ID'     => ['ID', true],     // true = default sort
            'title'     => ['title', false],     // true = default sort
            'author'    => ['author', false],
            'publisher' => ['publisher', false],
            'price'     => ['price', false],
            'year'      => ['year', false],
        ];
    }

    public function get_columns() {
        return [
            'cb'        => '<input type="checkbox" />',
            'ID'        => __( 'ID', 'book-manager' ),
            'title'     => __( 'Book Name', 'book-manager' ),
            'author'    => __( 'Author', 'book-manager' ),
            'publisher' => __( 'Publisher', 'book-manager' ),
            'price'     => __( 'Price', 'book-manager' ),
            'isbn'      => __( 'ISBN', 'book-manager' ),
            'year'      => __( 'Year', 'book-manager' ),
            'image'     => __( 'Image', 'book-manager' ),
            'actions'   => __( 'Actions', 'book-manager' ),
        ];
    }

    public function search_box( $text, $input_id ) {
        if ( empty($_REQUEST['s']) && !$this->has_items() ) {
            return;
        }
        $input_id = $input_id . '-search-input';
        ?>
        <p class="search-box">
            <label class="screen-reader-text" for="<?php echo esc_attr($input_id); ?>"><?php echo esc_html( $text ); ?>:</label>
            <input type="search" id="<?php echo esc_attr($input_id); ?>" name="s" value="<?php echo isset($_REQUEST['s']) ? esc_attr($_REQUEST['s']) : ''; ?>">
            <?php submit_button( $text, '', '', false, ['id' => 'search-submit'] ); ?>
        </p>
        <?php
    }


    public function prepare_items() {
        $columns  = $this->get_columns();
        $hidden   = [];
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = [$columns, $hidden, $sortable];

        // Get all books
        $books = get_books();

        // --- Search Filter ---
        $search = !empty($_GET['s']) ? sanitize_text_field($_GET['s']) : '';
        if ($search) {
            $books = array_filter($books, function($book) use ($search) {
                return (stripos(get_the_title($book->ID), $search) !== false);
            });
        }

        // --- Sorting ---
        $orderby = (!empty($_GET['orderby'])) ? sanitize_text_field($_GET['orderby']) : 'title';
        $order   = (!empty($_GET['order'])) ? sanitize_text_field($_GET['order']) : 'asc';

        usort($books, function($a, $b) use ($orderby, $order) {
            switch ($orderby) {
                case 'title':
                    $result = strcmp(get_the_title($a->ID), get_the_title($b->ID));
                    break;
                case 'author':
                    $a_author = get_post_meta($a->ID, 'book_author', true);
                    $b_author = get_post_meta($b->ID, 'book_author', true);
                    $result = strcmp(get_the_title($a_author), get_the_title($b_author));
                    break;
                case 'price':
                    $result = floatval(get_post_meta($a->ID, 'book_price', true)) - floatval(get_post_meta($b->ID, 'book_price', true));
                    break;
                case 'year':
                    $result = intval(get_post_meta($a->ID, 'book_year', true)) - intval(get_post_meta($b->ID, 'book_year', true));
                    break;
                default:
                    $result = 0;
            }
            return ($order === 'asc') ? $result : -$result;
        });

        // Assign paginated items
        $this->items = $books;

        // Optionally, set pagination if needed
        $total_items = count($books);
        $per_page = 10;
        $current_page = $this->get_pagenum();
        $offset = ($current_page - 1) * $per_page;

        $this->items = array_slice($books, $offset, $per_page);

        $this->set_pagination_args([
            'total_items' => $total_items,
            'per_page'    => $per_page,
            'total_pages' => ceil($total_items / $per_page)
        ]);
    }




    public function column_cb( $item ) {
        return sprintf(
            '<input type="checkbox" name="book[]" value="%s" />',
            $item->ID
        );
    }

    public function get_bulk_actions() {
        return [
            'delete' => __( 'Delete', 'book-manager' ),
            // You can add more actions here like 'mark_as_featured' => 'Mark as Featured'
        ];
    }


     public function column_default( $item, $column_name ) {
        switch ( $column_name ) {
            case 'ID':
                return $item->ID;
            case 'title':
                return sprintf(
                    '<a href="%s">%s</a>',
                    esc_url( add_query_arg( 'book_id', $item->ID ) ),
                    esc_html( get_the_title( $item->ID ) )
                );
            case 'author':
                $author_id = get_post_meta( $item->ID, 'book_author', true );
                return $author_id ? get_the_title( $author_id ) : '';
            case 'publisher':
                $publisher_id = get_post_meta( $item->ID, 'book_publisher', true );
                return $publisher_id ? get_the_title( $publisher_id ) : '';
            case 'price':
                return get_post_meta( $item->ID, 'book_price', true );
            case 'isbn':
                return get_post_meta( $item->ID, 'book_isbn', true );
            case 'year':
                return get_post_meta( $item->ID, 'book_year', true );
            case 'image':
                $image_id = get_post_meta( $item->ID, 'book_image', true );
                if ( $image_id ) {
                    $image_url = wp_get_attachment_image_url( $image_id, 'thumbnail' );
                    return sprintf( '<img src="%s" width="50" height="50" />', esc_url( $image_url ) );
                }
                return __( 'No Image', 'book-manager' );
            case 'actions':
                $edit_url   = add_query_arg([ 'action' => 'edit', 'book_id' => $item->ID ]);
                $delete_url = wp_nonce_url(add_query_arg(['action' => 'delete', 'book_id' => $item->ID]), 'bm_delete_book_' . $item->ID);
                return sprintf(
                    '<a href="%s" class="button button-small">%s</a> <a href="%s" class="button button-small button-danger" onclick="return confirm(\'%s\');">%s</a>',
                    esc_url($edit_url),
                    esc_html__('Edit', 'book-manager'),
                    esc_url($delete_url),
                    esc_attr__('Are you sure you want to delete this book?', 'book-manager'),
                    esc_html__('Delete', 'book-manager')
                );
            default:
                return '';
        }
    }

    public function process_bulk_action() {
        // Detect bulk action
        $action = $this->current_action();

        if ($action === 'delete') {
            // Check for selected books
            if (!empty($_REQUEST['book']) && is_array($_REQUEST['book'])) {
                foreach ($_REQUEST['book'] as $book_id) {
                    // Verify nonce for security
                    if (current_user_can('delete_post', $book_id)) {
                        wp_delete_post(intval($book_id), true);
                    }
                }
            }
            // Redirect to avoid resubmission
            wp_redirect(remove_query_arg(['action', 'book']));
            exit;
        }
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
