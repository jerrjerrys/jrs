<?php

namespace App\Module\classes\config;

use Illuminate\Contracts\View\View;

class MenuComposer {
    
    protected $menus;
    
    public function __construct() {
        
    }

    public function compose(View $view) {
        $view->with('count', $this->users->count());
    }

}
