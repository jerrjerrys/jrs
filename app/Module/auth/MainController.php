<?php

namespace App\Module\auth;

use App\Module\classes\JrsController;
use App\Module\classes\Render;
use App\Module\classes\Action;
use Request;
use Session;
use Datatables;

use App\Module\auth\model\User;
use App\Module\auth\model\Role;
use App\Module\auth\model\Page;
use App\Module\auth\model\RolePage;

class MainController extends JrsController {    
    protected $user, $role, $page, $role_page, $render, $request, $action;

    public function __construct(User $user, Role $role, Page $page, RolePage $role_page) {
        $this->user = $user;
        $this->role = $role;
        $this->page = $page;
        $this->role_page = $role_page;
        $this->request = Request::all();
        $this->layout = "jrs.lte-admin-layout";

        $this->middleware('token.auth', [
            'except' => [
                'anyIndex',
                'getLogin',
                'postLogin',
                'getData',
                'getTable',
                'getRegister',
                'postRegister'
            ]
        ]);
    }    

    public function anyIndex() {                                       
        return redirect("auth/login");
    }   

    public function getLogin() {
        $this->render = new Render(new $this->user, "jrs.login-layout");

        $this->form_widget_rules = [
            'form-method' => 'POST',
            'form-url' => url('auth/login?app=jrs_auth_db'),
            'form-clear' => ['role_id', 'token', 'email', 'image'],
            'form-modify' => [
                ['login', 'submit', ['attr' => ['class' => 'btn btn-info']]],
                ['register', 'submit', ['attr' => [
                            'class' => 'btn btn-warning',
                            'formaction' => url('auth/register'),
                            'formmethod' => 'GET']]],
            ]
        ];

        return $this->render->formWidget($this->form_widget_rules)
                        ->compileWidget();
    }        

    public function postLogin() {
        $this->success_msg = 'Login Success!';

        $this->error_msg = 'Login Failed! Username or Password not match!';

        $this->save_rules = [
            'except' => ['app', 'id', '_token']
        ];

        $this->validate_rules = [
            'only' => ['username', 'password'],
            'modify' => ['username' => ['required']]
        ];

        $this->to = 'auth/user';

        $this->action = new Action(new $this->user, $this->request);

        return $this->action->validate($this->validate_rules)
                        ->auth($this->save_rules)
                        ->redirect($this->to);
    }

    public function getRegister() {
        $this->render = new Render(new $this->user, 'jrs.login-layout');

        $this->form_widget_rules = [
            'form-method' => 'POST',
            'form-url' => url('auth/register'),
            'form-clear' => ['token', 'role_id'],
            'form-modify' => [
                ['register', 'submit', ['attr' => ['class' => 'btn btn-info']]],
                ['back', 'button', ['attr' => [
                            'class' => 'btn btn-warning',
                            'onclick' => "history.go(-1);"]]],
            ]
        ];

        $this->collections = $this->user->all();

        return $this->render->formWidget($this->form_widget_rules)
                        ->compileWidget();
    }

    public function postRegister() {
        $this->request['password'] = (!empty($this->request['password'])) 
                ? bcrypt($this->request['password']) 
                : NULL;

        $this->action = new Action(new $this->user, $this->request);

        $this->save_rules = ['except' => ['app']];

        return $this->action->validate()
                        ->newSave('', $this->save_rules)
                        ->redirect($this->to);
    }

    public function getLogout() {
        Session::flush();
        return redirect('auth');
    }

    public function getUser() {
        $this->render = new Render(new $this->user, $this->layout);

        $this->table_widget_rules = [
            "query" => $this->user->with(['roles']),
            "paginate" => 10,
            "filter" => [],
            "show" => [
                "Photo" => [TRUE, "image"],
                "Username" => [TRUE, "username"],
                "Role" => [TRUE, "roles", "role_name"]
            ],
            "image" => [
                'image' => []
            ],
            "create" => ['auth/create-user'],
            "update" => ['auth/update-user', 'id'],
            "delete" => ['auth/remove-user', 'id']
        ];

        return $this->render->newTableWidget($this->table_widget_rules)
                        ->compileWidget();
    }

    public function getCreateUser() {
        $this->render = new Render(new $this->user, $this->layout);

        $this->form_widget_rules = [
            'form-method' => 'POST',
            'form-url' => url('auth/create-user'),
            'form-clear' => ['token'],
            'form-modify' => [                
                ['submit', 'submit', ['attr' => ['class' => 'btn btn-info']]],
                ['back', 'button', ['attr' => [
                            'class' => 'btn btn-warning',
                            'onclick' => "history.go(-1);"]]]
            ]
        ];

        return $this->render->formWidget($this->form_widget_rules)
                        ->compileWidget();
    }

    public function postCreateUser() {
        $this->request['password'] = (!empty($this->request['password'])) ? bcrypt($this->request['password']) : NULL;

        $this->action = new Action(new $this->user, $this->request);

        $this->save_rules = ['except' => ['app']];

        $this->restore_rules = [
            "model" => $this->user,
            "where" => ["username", "=", $this->request['username']]
        ];

        $this->upload_rules = [
            "image" => [
                "multiple" => FALSE,
                "folder" => "assets/img/profile_pictures"
            ]
        ];                

        return $this->action->restore($this->restore_rules)
                        ->validate($this->validate_rules)
                        ->upload($this->upload_rules)
                        ->newSave('', $this->save_rules)
                        ->redirect($this->to);
    }

    public function getUpdateUser($id) {
        $this->render = new Render(new $this->user, $this->layout);

        $this->form_widget_rules = [
            'form-method' => 'POST',
            'form-url' => url('auth/update-user'),
            'form-clear' => ['token'],
            'form-modify' => [
                ['submit', 'submit', ['attr' => ['class' => 'btn btn-info']]],
                ['back', 'button', ['attr' => [
                            'class' => 'btn btn-warning',
                            'onclick' => "history.go(-1);"]]]
            ],
            'form-model' => $this->user->find($id)
        ];

        return $this->render->formWidget($this->form_widget_rules)
                        ->compileWidget();
    }

    public function postUpdateUser() {
        $this->request['password'] = (!empty($this->request['password'])) ? bcrypt($this->request['password']) : NULL;

        $this->action = new Action($this->user->find($this->request['id']), $this->request);

        $this->save_rules = ['except' => ['app']];

        $this->validate_rules = [
            'modify' => [
                'username' => ['required', 'unique:users,username,' . $this->request['id']],
                'email' => ['required', 'unique:users,email,' . $this->request['id']],
            ]
        ];
        
        $this->upload_rules = [
            "image" => [
                "multiple" => FALSE,
                "folder" => "assets/img/profile_pictures"
            ]
        ];

        return $this->action->validate($this->validate_rules)
                        ->upload($this->upload_rules)
                        ->newSave('', $this->save_rules)
                        ->action($this->user, 'updateSession')
                        ->redirect($this->to);
    }

    public function getRole() {
        $this->render = new Render(new $this->role, $this->layout);

        $this->table_widget_rules = [
            "query" => $this->role->with(['pages']),
            "paginate" => 10,
            "filter" => [],
            "show" => [
                "Role Name" => [TRUE, "role_name"],
                "Pages" => [TRUE, "pages", "page_name", TRUE],
                "Users" => [TRUE, "users", "username", TRUE],
                "User Image" => [TRUE, "users", "image", TRUE]
            ],
            "image" => ["image" => []],
            "create" => ['auth/create-role'],
            "update" => ['auth/update-role', 'id'],
            "delete" => ['auth/remove-role', 'id']
        ];

        return $this->render->newTableWidget($this->table_widget_rules)
                        ->compileWidget();
    }

    public function getCreateRole() {
        $this->render = new Render(new $this->role, $this->layout);

        $this->form_widget_rules = [
            'form-method' => 'POST',
            'form-url' => url('auth/create-role'),
            'form-modify' => [                
                ['submit', 'submit', ['attr' => ['class' => 'btn btn-info']]],
                ['back', 'button', ['attr' => [
                            'class' => 'btn btn-warning',
                            'onclick' => "history.go(-1);"]]]
            ]
        ];

        return $this->render->formWidget($this->form_widget_rules)
                        ->compileWidget();
    }

    public function postCreateRole() {
        //dd($this->request);
        $this->action = new Action(new $this->role, $this->request);

        $this->before_save_rules = ['except' => ['_token', 'page_id']];

        $this->save_rules = [
            'multiple' => 'page_id',
            'insert' => [
                'role_id' => '@id',
                'page_id' => '#multiple'
            ]
        ];

        $this->validate_rules = [
            'modify' => ['page_id' => ['required']]
        ];

        return $this->action->validate($this->validate_rules)
                        ->newSave('', $this->before_save_rules)
                        ->newSave($this->role_page, $this->save_rules)
                        ->redirect($this->to);
    }

    public function getUpdateRole($id) {
        $this->render = new Render(new $this->role, $this->layout);

        $this->form_widget_rules = [
            'form-method' => 'POST',
            'form-url' => url('auth/update-role'),
            'form-modify' => [
                ['submit', 'submit', ['attr' => ['class' => 'btn btn-info']]],
                ['back', 'button', ['attr' => [
                            'class' => 'btn btn-warning',
                            'onclick' => "history.go(-1);"]]]
            ],
            'form-model' => $this->role->find($id)
        ];

        $this->table_widget_rules = [
            "query" => $this->role_page->with(['roles', 'pages'])->where('role_id', $id),
            "paginate" => 10,
            "filter" => [],
            "show" => [
                "Role Name" => [TRUE, "roles", "role_name"],
                "Page Name" => [TRUE, "pages", "page_name"]
            ],
            "image" => [],
            "delete" => ['auth/remove-role-page', 'id']
        ];

        return $this->render->formWidget($this->form_widget_rules)
                        ->newTableWidget($this->table_widget_rules)
                        ->compileWidget();
    }

    public function postUpdateRole() {
        $this->action = new Action($this->role->find($this->request['id']), $this->request);

        $this->before_save_rules = ['except' => ['_token', 'page_id']];

        $this->save_rules = [
            'delete' => ['role_id', $this->request['id']],
            'multiple' => 'page_id',
            'insert' => [
                'role_id' => $this->request['id'],
                'page_id' => '#multiple'
            ]
        ];

        $this->validate_rules = [
            'modify' => [
                'role_name' => ['required', 'unique:roles,role_name,' . $this->request['id']]
            ]
        ];

        $this->remove_rules = [
            'model' => $this->role_page,
            'where' => [ 'role_id', '=', $this->request['id']]
        ];

        return $this->action->validate($this->validate_rules)
                        ->newSave('', $this->before_save_rules)
                        ->remove($this->remove_rules)
                        ->newSave($this->role_page, $this->save_rules)
                        ->redirect($this->to);
    }

    public function getPage() {
        $this->render = new Render(new $this->page, $this->layout);

        $this->table_widget_rules = [
            "query" => $this->page->with(['roles']),
            "paginate" => 10,
            "filter" => [],
            "show" => [
                "Page" => [TRUE, "page_name"],
                "Assigned Roles" => [TRUE, "roles", "role_name", TRUE]
            ],
            "image" => [],
            "create" => ['auth/create-page'],
            "update" => ['auth/update-page', 'id'],
            "delete" => ['auth/remove-page', 'id']
        ];

        return $this->render->newTableWidget($this->table_widget_rules)
                        ->compileWidget();
    }

    public function getCreatePage() {
        $this->render = new Render(new $this->page, $this->layout);

        $this->form_widget_rules = [
            'form-method' => 'POST',
            'form-url' => url('auth/create-page'),
            'form-modify' => [
                ['submit', 'submit', ['attr' => ['class' => 'btn btn-info']]],
                ['back', 'button', ['attr' => [
                            'class' => 'btn btn-warning',
                            'onclick' => "history.go(-1);"]]]
            ]
        ];

        return $this->render->formWidget($this->form_widget_rules)
                        ->compileWidget();
    }

    public function postCreatePage() {
        $this->action = new Action(new $this->page, $this->request);

        $this->save_rules = ['except' => ['_token']];

        return $this->action->validate()
                        ->newSave('', $this->save_rules)
                        ->redirect($this->to);
    }

    public function getUpdatePage($id) {
        $this->render = new Render(new $this->page, $this->layout);

        $this->form_widget_rules = [
            'form-method' => 'POST',
            'form-url' => url('auth/update-page'),
            'form-modify' => [
                ['submit', 'submit', ['attr' => ['class' => 'btn btn-info']]],
                ['back', 'button', ['attr' => [
                            'class' => 'btn btn-warning',
                            'onclick' => "history.go(-1);"]]]
            ],
            'form-model' => $this->page->find($id)
        ];

        return $this->render->formWidget($this->form_widget_rules)
                        ->compileWidget();
    }

    public function postUpdatePage() {
        $this->action = new Action($this->page->find($this->request['id']), $this->request);

        $this->save_rules = ['except' => ['_token']];

        $this->validate_rules = [
            'modify' => [
                'page_name' => ['required', 'unique:pages,page_name,' . $this->request['id']]
            ]
        ];

        return $this->action->validate($this->validate_rules)
                        ->newSave('', $this->save_rules)
                        ->redirect($this->to);
    }

    public function getRolePage() {
        $this->render = new Render(new $this->role_page, $this->layout);

        $this->table_widget_rules = [
            "query" => $this->role_page->with(['roles', 'pages']),
            "paginate" => 10,
            "filter" => [],
            "show" => [
                "Roles" => [TRUE, "roles", "role_name"],
                "Pages" => [TRUE, "pages", "page_name"]
            ],
            "image" => [],
            "delete" => ['auth/remove-role-page', 'id']
        ];

        return $this->render->newTableWidget($this->table_widget_rules)
                        ->compileWidget();
    }

    public function getCreateRolePage() {
        $this->render = new Render(new $this->role_page, $this->layout);

        $this->form_widget_rules = [
            'form-method' => 'POST',
            'form-url' => url('auth/create-role-page'),
            'form-clear' => [],
            'form-modify' => [
                ['submit', 'submit', ['attr' => ['class' => 'btn btn-info']]],
                ['back', 'button', ['attr' => [
                            'class' => 'btn btn-warning',
                            'onclick' => "history.go(-1);"]]]
            ]
        ];

        return $this->render->formWidget($this->form_widget_rules)
                        ->compileWidget();
    }

    public function postCreateRolePage() {
        $this->action = new Action(new $this->role_page, $this->request);

        $this->save_rules = ['except' => ['_token']];

        return $this->action->validate()
                        ->newSave('', $this->save_rules)
                        ->redirect($this->to);
    }

    public function getUpdateRolePage($id) {
        $this->render = new Render(new $this->role_page, $this->layout);

        $this->form_widget_rules = [
            'form-method' => 'POST',
            'form-url' => url('auth/update-role-page'),
            'form-clear' => [],
            'form-modify' => [
                ['submit', 'submit', ['attr' => ['class' => 'btn btn-info']]],
                ['back', 'button', ['attr' => [
                            'class' => 'btn btn-warning',
                            'onclick' => "history.go(-1);"]]]
            ],
            'form-model' => $this->role_page->find($id)
        ];

        return $this->render->formWidget($this->form_widget_rules)
                        ->compileWidget();
    }

    public function postUpdateRolePage() {
        $this->action = new Action($this->role_page->find($this->request['id']), $this->request);

        $this->save_rules = ['except' => ['_token']];

        return $this->action->validate()
                        ->newSave('', $this->save_rules)
                        ->redirect($this->to);
    }

    public function getRemoveUser($id) {
        $this->action = new Action($this->user->find($id), $this->request);

        $this->remove_rules = [
            'model' => $this->user,
            'where' => [ 'id', '=', $id]
        ];

        return $this->action->remove($this->remove_rules)->redirect($this->to);
    }

    public function getRemoveRole($id) {
        $this->action = new Action($this->role->find($id), $this->request);

        $this->remove_rules = [
            'model' => $this->role,
            'where' => [ 'id', '=', $id]
        ];

        return $this->action->remove($this->remove_rules)->redirect($this->to);
    }

    public function getRemovePage($id) {
        $this->action = new Action($this->page->find($id), $this->request);

        $this->remove_rules = [
            'model' => $this->page,
            'where' => [ 'id', '=', $id]
        ];

        return $this->action->remove($this->remove_rules)->redirect($this->to);
    }

    public function getRemoveRolePage($id) {
        $this->action = new Action($this->role_page->find($id), $this->request);

        $this->remove_rules = [
            'model' => $this->role_page,
            'where' => [ 'id', '=', $id]
        ];

        return $this->action->remove($this->remove_rules)->redirect($this->to);
    }

}
