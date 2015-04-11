<?php

namespace App\Module\auth\model;

use App\Module\classes\SomeLib;
use App\Module\auth\model\Role;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Session;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

    use Authenticatable,
        CanResetPassword,
        SoftDeletes;

    protected $table = 'users';
    protected $fillable = ['username', 'email', 'password', 'role_id', 'token'];
    protected $dates = ['deleted_at'];
    protected $lib, $data, $role;

    /*
     * User has one roles
     * 
     * @var NULL
     */

    public function roles() {
        return $this->hasOne('App\Module\auth\model\Role', 'id', 'role_id');
    }

    /*
     * function to generate form
     * 
     * @var NULL
     */

    public function attributes() {
        $this->lib = new SomeLib;

        $this->role = new Role;

        $this->data = [
            'model' => $this->role->all(),
            'value' => 'id',
            'display' => ['HAHA ', '#role_name', ' HAHA']
        ];

        return [
            ['id', 'hidden'],
            ['username', 'text'],
            ['password', 'password'],
            ['email', 'text'],
            ['image', 'file'],
            ['role_id', 'choice', [
                    'choices' => $this->lib->makeList($this->data),
                    'empty_value' => '==== Select Role ===',
                    'multiple' => false
                ]
            ],
            ['token', 'text']
        ];
    }

    /*
     * function for validate the data after request
     * 
     * @var NULL
     */

    public function rules() {
        return [
            'username' => ['required', 'unique:users,username'],
            'password' => ['required'],
            'email' => ['required', 'email', 'unique:users,email'],
            'image' => ['required', 'max:5000', 'image'],
            'role_id' => ['required'],
        ];
    }

    public function tokenCheck($token) {
        return $this->where('_token', $token)->get();
    }

    public function updateSession($request) {
        if ($request['id'] == session('id')) {

            $mdl = $this->find($request['id']);

            foreach ($mdl->toArray() as $key => $value) {
                Session::put($key, $value);
            }
        }

        return TRUE;
    }

}
