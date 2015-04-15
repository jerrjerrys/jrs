<?php

namespace App\Module\classes\config;

use Illuminate\Contracts\View\View;
use Auth, Session;

class ProfileComposer {    

    public function __construct() {
        
    }

    public function compose(View $view) {
        
        if (Auth::check()) {
            
            $user_data = Auth::user();
            
            $view->with('profile_name', $user_data->username);
            $view->with('profile_image', $user_data->image);
            $view->with('profile_join_at', $user_data->created_at->diffForHumans());            
            $view->with('profile_page_url', 'auth/update-user/'.$user_data->id);
            $view->with('profile_logout_url', 'auth/logout');
        }
    }

}
