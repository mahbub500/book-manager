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
        
        $authors = $this->get_authors();
        ?>
        <div class="wrap">
            <h1>Author Manager</h1>

            <!-- Tab Navigation -->
            <h2 id="author-tabs" class="nav-tab-wrapper">
                <a href="javascript:void(0);" class="nav-tab nav-tab-active" data-tab="bm-author-list">Author List</a>
                <a href="javascript:void(0);" class="nav-tab" data-tab="bm-author-add">Add Author</a>
            </h2>

            <!-- Author List Table -->
            <div id="bm-author-list" class="tab-content" style="display:block;">
                <h3>List of Authors</h3>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Image</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($authors)) : ?>
                            <?php foreach ($authors as $authors) : 
                                $email = get_post_meta($authors->ID, 'author_email', true);
                                $logo_id = get_post_meta($authors->ID, 'author_logo', true);
                                $logo_url = $logo_id ? wp_get_attachment_url($logo_id) : '';
                            ?>
                                <tr>
                                    <td><?php echo esc_html($authors->post_title); ?></td>
                                    <td><?php echo esc_html($email); ?></td>
                                    <td>
                                        <?php if ($logo_url) : ?>
                                            <img src="<?php echo esc_url($logo_url); ?>" alt="" width="50">
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <!-- You can add Edit/Delete buttons here -->
                                        <button class="button button-small">Edit</button>
                                        <button class="button button-small">Delete</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4">No Authors found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div id="bm-author-add" class="tab-content" style="display:none;">
                <h3>Add Author</h3>
                <form method="post" enctype="multipart/form-data">
                    <table class="form-table">
                        <tr>
                            <th><label for="author_name">Author Name</label></th>
                            <td><input type="text" name="author_name" id="author_name" class="regular-text" required></td>
                        </tr>
                        <tr>
                            <th><label for="author_email">Author Email</label></th>
                            <td><input type="email" name="author_email" id="author_email" class="regular-text"></td>
                        </tr>
                        <tr>
                            <th><label for="author_logo">Profie picture</label></th>
                            <td><input type="file" name="author_logo" id="author_logo"></td>
                        </tr>
                    </table>
                    <p class="submit">
                        <input type="submit" name="save_author" id="save_author" class="button button-primary" value="Add Author">
                    </p>
                </form>
            </div>
        </div>    
        <?php 
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
