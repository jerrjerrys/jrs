<div class="dropdown">
    <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true">
        Menu
        <span class="caret"></span>
    </button>
    <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
        <li role="presentation"><a role="menuitem" tabindex="-1" href="{!! url('auth/user') !!}">User</a></li>                    
        <li role="presentation"><a role="menuitem" tabindex="-1" href="{!! url('auth/role') !!}">Role</a></li>
        <li role="presentation"><a role="menuitem" tabindex="-1" href="{!! url('auth/page') !!}">Page</a></li>
        <li role="presentation"><a role="menuitem" tabindex="-1" href="{!! url('auth/role-page') !!}">Role Page</a></li>
        <li role="presentation" class="divider"></li>
        <li role="presentation"><a role="menuitem" tabindex="-1" href="{!! url('auth/logout') !!}">Logout</a></li>
    </ul>
</div>