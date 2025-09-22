<?php
namespace BookManager\PostTypes;

class BookPostType {
    public function register() {
        add_action('init', [$this, 'register_book_cpt']);
    }

    public function register_book_cpt() {
        register_post_type('books', [
            'label' => 'Books',
            'public' => true,
            'show_ui' => false,
            'supports' => ['title', 'editor'],
        ]);
    }
}
