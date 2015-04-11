<?php

namespace App\Module\auth\model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Module\auth\model\Page;
use App\Module\classes\SomeLib;

class Role extends Model {

    use SoftDeletes;

    protected $table = 'roles';
    protected $fillable = ['role_name'];
    protected $dates = ['deleted_at'];
    protected $page, $data, $lib;    
    
    public function users() {        
        return $this->hasMany('App\Module\auth\model\User','role_id','id');
    }
    
    public function roles_pages() {        
        return $this->hasMany('App\Module\auth\model\RolePage','role_id','id');        
    }
    
    public function pages(){
        return $this->belongsToMany('App\Module\auth\model\Page', 'roles_pages', 'role_id')
            ->whereNull('roles_pages.deleted_at');
    }
    
    public function attributes(){
        $this->page = new Page;
        
        $this->lib = new SomeLib;
        
        $this->data = [
            "model" => $this->page->all(),
            "value" => "id",
            "display" => ['','#page_name','']
        ];
        
        return [            
            ['id','hidden'],
            ['role_name', 'text'],            
            ['page_id','choice', [
                'choices' => $this->lib->makeList($this->data),
                'expanded' => false,
                'multiple' => true
            ]]
        ];
    }
    
    public function rules(){
        return [
            'role_name' => ['required','unique:roles,role_name'],            
        ];
    }
}
