<?php
namespace App\Module\filter;

use Closure;
use Request;
use App\Module\classes\FirstToDo;

class BeforeStart {

    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $ftd;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(FirstToDo $ftd) {
        
        $this->ftd = $ftd;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {    
        
        $this->ftd->setHeader();
        $this->ftd->setConnection();
        $this->ftd->setViewComposer();
        
        return $next($request);
    }

}
