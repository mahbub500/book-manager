<?php
namespace BookManager;

use BookManager\PostTypes\BookPostType;
use BookManager\Admin\Menu;

class BookManager {
    public function init() {
        (new BookPostType())->register();
        (new Menu())->register();
    }
}
