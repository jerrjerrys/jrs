<?php

namespace App\Module\filter;

use Closure;
use Session;
use Auth;
use App\Module\auth\model\User;

class TokenAuthenticate {

    protected $usr;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(User $usr) {
        $this->usr = $usr;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {        
        if (!Auth::check()) {
            Session::flash('error','You don\'t have a permission to access this action. Please contact your administrator for further information.');
            return redirect("auth/login");
        }else{
            return $next($request);
        }        
    }

}
