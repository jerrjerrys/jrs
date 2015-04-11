<?php

namespace App\Module\classes;

use Validator,
    Request,
    Session,
    Storage,
    File,
    Auth;
use App\Module\classes\SomeLib;
use App\Module\auth\model\RolePage;

class Action {

    protected $mdl;
    protected $restore = "";
    protected $multi_mdl;
    protected $rules;
    protected $lib;
    protected $errors = '';
    protected $request;
    protected $auth = '';
    protected $save = '';
    protected $remove = '';
    protected $last_input = [];
    protected $modified_request;
    protected $upload = [];
    protected $action = '';

    public function __construct($mdl, $request) {

        $this->mdl = $mdl;
        $this->rules = $this->mdl->rules();
        $this->lib = new SomeLib;
        $this->request = $request;
    }

    public function validate($validate_rules = [], $error_msg = 'Form Validation Failed') {

        if ($this->restore != TRUE) {

            $rules = $this->lib->arrayModified($this->mdl->rules(), $validate_rules);
            $v = Validator::make($this->request, $rules);

            if ($v->fails()) {

                Session::flash('save-error', $error_msg);

                $this->errors = $v->errors();
            }
        }

        return $this;
    }

    /*
     * mengakomodir aksi penyimpanan, baik single input maupun multiple input
     * 
     * @mdl model atau entitas yang diapakai untuk menyimpan
     * @rules array tempat menyimpan pengaturan parameter-parameter yang disimpan atau tidak
     * @success_msg string untuk menyimpan pesan jika berhasil menyimpan
     * @error_msg string untuk menyimpan pesan jika gagal menyimpan
     * 
     */

    public function newSave($mdl, $rules = [], $success_msg = 'Save Success !', $error_msg = 'Save Error !') {

        $this->modified_request = $this->lib->arrayModified($this->request, $rules);
        $this->mdl = (!empty($mdl)) ? new $mdl : $this->mdl;

        if ($this->restore != TRUE) {

            if (empty($this->errors)) {

                if (array_key_exists('multiple', $rules)) {

                    $i = 0;

                    foreach ($this->modified_request[$rules['multiple']] as $multiple_key => $multiple_value) {

                        $this->multi_mdl = new $mdl;

                        $test[$i][] = $this->multi_mdl;
                        $test[$i][] = $this->last_input;

                        foreach ($rules['insert'] as $key => $value) {

                            if ($value[0] == '@') {

                                $value = $this->last_input[substr($value, 1)];
                            } else if ($value == '#multiple') {

                                $value = $multiple_value[0];
                            } else if (array_key_exists($key, $this->upload)) {

                                $value = $this->upload[$key][$i];
                            } else {

                                $value = $this->request[$value][$i];
                            }

                            $test[$i][] = $value;

                            $this->multi_mdl->$key = $value;
                        }

                        $test[$i][] = $this->multi_mdl->save();

                        $i++;
                    }

                    //dd($test);

                    Session::flash('save-success', $success_msg);
                } else {

                    foreach ($this->modified_request as $key => $value) {

                        if (array_key_exists($key, $this->upload)) {

                            $value = $this->upload[$key];
                        }

                        $this->mdl->$key = $value;
                        $this->last_input[$key] = $value;
                    }

                    $this->mdl->save();

                    Session::flash('save-success', $success_msg);

                    $this->last_input['id'] = $this->mdl->id;
                    $this->save = 2;
                }
            }
        }

        return $this;
    }

    /*
     * mengakomodir sebuah custom action yang telah dibuat pada model
     * 
     * @mdl model yang dipakai
     * @function fungsi yang dipakai
     * 
     */

    public function action($mdl, $function, $end = FALSE) {
        $this->mdl = (!empty($mdl)) ? new $mdl : $this->mdl;

        $this->action = $this->mdl->$function($this->request);

        if ($end == FALSE) {
            return $this;
        } else {
            return $this->action;
        }
    }

    public function remove($rule) {
        $value = $rule['where'];

        Session::flash('save-success', "Success Remove");

        $this->mdl = new $rule['model'];
        $this->mdl->where($value[0], $value[1], $value[2])->delete();
        $this->remove = 2;

        return $this;
    }

    public function auth($rules = [], $success_msg = 'Login Success!', $error_msg = 'Login Failed! Username or Password not match!') {
        if (array_key_exists('app', $this->request)) {

            $db = $this->request['app'];
        }

        $this->request = $this->lib->arrayModified($this->request, $rules);

        if (!Auth::attempt($this->request)) {

            Session::flash('save-error', $error_msg);

            $this->auth = 1;
        } else {

            $user_data = Auth::user();

            Session::put('db_connection', $db);
            
            Session::put('login_token', $user_data->_token);
            
            Session::put('join_at', $user_data->created_at->diffForHumans());

            foreach ($user_data->toArray() as $key => $value) {
                
                Session::put($key, $value);
            }

            Session::flash('save-success', $success_msg);

            $this->auth = 2;
        }

        return $this;
    }

    public function redirect($to = '') {
        //dd($this->last_input);

        $redirect_success = "";
        $redirect_failed = redirect()->back();

        if (empty($to)) {

            $redirect_success = redirect()->back();
        } else {

            $redirect_success = redirect($to);
        }

        if (!empty($this->errors)) {

            $redirect = $redirect_failed->withInput()->withErrors($this->errors);
        } else {

            if (!empty($this->auth)) {

                if ($this->auth == 2 /* TRUE */) {

                    $redirect = $redirect_success;
                } else {

                    $redirect = $redirect_failed->withInput();
                }
            } else {

                $redirect = $redirect_success;
            }
        }

        return $redirect;
    }

    public function restore($rules) {

        $find = $rules["model"]->withTrashed()->where($rules["where"][0], $rules["where"][1], $rules["where"][2])->first();

        if (!empty($find)) {

            $rules["model"]->withTrashed()->where($rules["where"][0], $rules["where"][1], $rules["where"][2])->restore();

            $this->restore = TRUE;

            Session::flash('save-success', "Restore Success");
        }

        return $this;
    }

    public function upload($rules) {

        if ($this->restore != TRUE) {

            foreach ($rules as $key => $value) {

                //dd($key);

                if (empty($this->errors)) {

                    if ($value['multiple'] != TRUE) {

                        $file = Request::file($key);
                        $extension = $file->getClientOriginalExtension();

                        if (!Storage::exists($value['folder'])) {

                            Storage::makeDirectory($value['folder']);
                        }

                        $new_name = $file->getFilename() . '.' . $extension;
                        $complete_path = $value['folder'] . '/' . $new_name;

                        Storage::put($complete_path, File::get($file));

                        $this->upload[$key] = $complete_path;

                        Session::flash('save-success', 'Upload Success !');
                    }
                }
            }
        }

        //dd($this->upload);

        return $this;
    }

    public function save($to = '', $rules = [], $auth = false, $success_msg = 'Save Success !', $error_msg = 'Save Error !') {
        $db = "";
        $redirect_success = "";
        $redirect_failed = redirect()->back();

        if (array_key_exists('app', $this->request)) {
            $db = $this->request['app'];
        }

        $this->request = $this->lib->arrayModified($this->request, $rules);

        if (empty($to)) {
            $redirect_success = redirect()->back();
        } else {
            $redirect_success = redirect($to);
        }

        if (!empty($this->errors)) {
            Session::flash('save-error', $error_msg);
            return $redirect_failed->withInput()->withErrors($this->errors);
        } else {
            if ($auth == true) {
                if (!Auth::attempt($this->request)) {
                    Session::flash('save-error', $error_msg);
                    return $redirect_failed->withInput();
                } else {
                    $user_data = Auth::user();
                    Session::put('db_connection', $db);
                    Session::put('login_token', $user_data->_token);
                    Session::put('username', $user_data->username);
                    Session::flash('save-success', $success_msg);
                    return $redirect_success;
                }
            } else {
                foreach ($this->request as $key => $value) {
                    $this->mdl->$key = $value;
                }

                $this->mdl->save();

                Session::flash('save-success', $success_msg);
                return $redirect_success;
            }
        }
    }

}
