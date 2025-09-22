<?php
namespace BookManager\PostTypes;

class BookPostType {
    public function register() {
        add_action('init', [$this, 'register_book_cpt']);
        add_action('init', [$this, 'register_publisher_cpt']);
        add_action('init', [$this, 'register_author_cpt']);
    }

    // Books CPT
    public function register_book_cpt() {
        register_post_type('book', [
            'label' => 'Books',
            'public' => true,
            'show_ui' => true,
            'menu_icon' => 'dashicons-book',
            'supports' => ['title', 'editor', 'thumbnail'],
        ]);

        // Publisher taxonomy
        register_taxonomy('publisher', 'book', [
            'label' => 'Publisher',
            'public' => true,
            'hierarchical' => true,
            'show_ui' => true,
        ]);

        // Author taxonomy
        register_taxonomy('book_author', 'book', [
            'label' => 'Author',
            'public' => true,
            'hierarchical' => true,
            'show_ui' => true,
        ]);
    }

    // Publisher CPT
    public function register_publisher_cpt() {
        register_post_type('publisher', [
            'label' => 'Publishers',
            'public' => true,
            'show_ui' => true,
            'menu_icon' => 'dashicons-building',
            'supports' => ['title'],
        ]);
    }

    // Author CPT
    public function register_author_cpt() {
        register_post_type('author', [
            'label' => 'Authors',
            'public' => true,
            'show_ui' => true,
            'menu_icon' => 'dashicons-admin-users',
            'supports' => ['title'],
        ]);
    }
}
