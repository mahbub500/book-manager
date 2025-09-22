<?php
/**
 * Plugin Name: Book Manager
 * Description: Manage books with custom post type and admin menus.
 * Version: 1.0.0
 * Author: Mahbub
 */

if ( ! defined('ABSPATH') ) {
    exit;
}

// Plugin Version
if ( ! defined('BOOK_MANAGER_VERSION') ) {
    define('BOOK_MANAGER_VERSION', '1.0.0');
}

// Plugin Folder Path
if ( ! defined('BOOK_MANAGER_PATH') ) {
    define('BOOK_MANAGER_PATH', plugin_dir_path(__FILE__));
}

// Plugin Folder URL
if ( ! defined('BOOK_MANAGER_URL') ) {
    define('BOOK_MANAGER_URL', plugin_dir_url(__FILE__));
}

// Plugin Base Name
if ( ! defined('BOOK_MANAGER_BASENAME') ) {
    define('BOOK_MANAGER_BASENAME', plugin_basename(__FILE__));
}

// Autoload files via Composer
require_once BOOK_MANAGER_PATH . 'vendor/autoload.php';

use BookManager\BookManager;

// Initialize plugin
function book_manager_init() {
    $plugin = new BookManager();
    $plugin->init();
}
add_action('plugins_loaded', 'book_manager_init');
