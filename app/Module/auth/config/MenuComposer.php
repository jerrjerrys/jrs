<?php

namespace App\Module\auth\config;

use Illuminate\Contracts\View\View;

class MenuComposer {
    
    protected $menus;
    
    public function __construct() {
        
    }

    public function compose(View $view) {
        $view->with('count', $this->users->count());
    }

}
