<?php namespace App\Module\classes;

use App\Http\Controllers\Controller;

abstract class JrsController extends Controller {    
    protected $success_msg = '';
    protected $error_msg = '';
    protected $save_rules = [];
    protected $before_save_rules = [];
    protected $after_save_rules = [];
    protected $remove_rules = [];
    protected $validate_rules = [];
    protected $restore_rules = [];
    protected $to = '';
    protected $form_widget_rules = [];
    protected $collections = [];
    protected $table_widget_rules = [];
    protected $upload_rules = [];
    protected $layout = '';
}

