<?php namespace App\Module\classes;

use Config, Session, Request;

class FirstToDo {
    public function setHeader(){
        
        header("Expires: Mon, 26 Jul 1990 05:00:00 GMT");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");        
    }
    
    public function setConnection() {        
        
        $connection = (Session::has("db_connection")) ? Session::get("db_connection") : Request::input("app");
        
        Session::put('db_connection',$connection);
        
        if(!empty($connection)){
            
            Config::set('database.default', $connection);        
        }        
    }
}
