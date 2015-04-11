<?php

namespace App\Module\auth\model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model {

    use SoftDeletes;

    protected $table = 'pages';
    protected $fillable = ['page_name','url'];
    protected $dates = ['deleted_at'];
    
    public function roles_pages() {        
        return $this->hasMany('App\Module\auth\model\RolePage','page_id','id');
    }
    
    public function roles(){
        return $this->belongsToMany('App\Module\auth\model\Role', 'roles_pages', 'page_id')
            ->whereNull('roles_pages.deleted_at');
    }
    
    public function attributes(){
        return [            
            ['id','hidden'],
            ['page_name', 'text'],
            ['url', 'text']
        ];
    }
    
    public function rules(){
        return [
            'page_name' => ['required','unique:pages,page_name'],            
            'url' => ['required','unique:pages,url']
        ];
    }
}
