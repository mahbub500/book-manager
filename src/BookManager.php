<?php
namespace BookManager;

use BookManager\PostTypes\BookPostType;
use BookManager\Admin\Menu;

/**
 * Class BookManager
 *
 * Main plugin class to initialize all components.
 *
 * @package BookManager
 */
class BookManager {

    /**
     * Initialize the plugin.
     */
    public function init() {

        // Only initialize admin functionality in the backend.
        if ( is_admin() ) {
            $this->init_post_types();
            $this->init_admin_menus();
        }
    }

    /**
     * Register all custom post types.
     */
    protected function init_post_types() {
        $book_post_type = new BookPostType();
        $book_post_type->register();
    }

    /**
     * Register all admin menus.
     */
    protected function init_admin_menus() {
        $menu = new Menu();
        $menu->register();
    }
}
