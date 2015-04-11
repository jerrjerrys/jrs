<?php

namespace App\Module\auth\model;

use App\Module\classes\SomeLib;
use App\Module\auth\model\Role;
use App\Module\auth\model\Page;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RolePage extends Model {

    use SoftDeletes;

    protected $table = 'roles_pages';
    protected $fillable = ['role_id','page_id'];
    protected $dates = ['deleted_at'];
    protected $lib, $role, $page, $role_data, $page_data;
    
    public function pages() {                
        return $this->hasOne('App\Module\auth\model\Page','id','page_id');
    }
    
    public function roles() {        
        return $this->hasOne('App\Module\auth\model\Role','id','role_id');
    }
    
    public function attributes(){
        $this->lib = new SomeLib;
        
        $this->role = new Role;
        
        $this->page = new Page;
        
        $this->role_data = [
            'model' => $this->role->all(),
            'value' => 'id',
            'display' => ['','#role_name','']
        ];
        
        $this->page_data = [
            'model' => $this->page->all(),
            'value' => 'id',
            'display' => ['','#page_name','']
        ];
        
        return [            
            ['id','hidden'],
            ['role_id', 'choice', [
                    'choices' => $this->lib->makeList($this->role_data),
                    'empty_value' => '==== Select Role ===',
                    'multiple' => false
                ]
            ],
            ['page_id', 'choice', [
                    'choices' => $this->lib->makeList($this->page_data),
                    'empty_value' => '==== Select Page ===',
                    'multiple' => false
                ]
            ],
        ];
    }
    
    public function rules(){
        return [
            'role_id' => ['required'],
            'page_id' => ['required'],
        ];
    }
    
    public function showByRoleId($id){
        return $this->where('role_id',$id)->get();
    }
}
