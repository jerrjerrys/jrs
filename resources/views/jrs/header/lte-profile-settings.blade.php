<!-- User Account: style can be found in dropdown.less -->
<li class="dropdown user user-menu">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <img src="{!! asset($profile_image) !!}" class="user-image" alt="User Image"/>
        <span class="hidden-xs">{!! $profile_name !!}</span>
    </a>
    <ul class="dropdown-menu">
        <!-- User image -->
        <li class="user-header">
            <img src="{!! asset($profile_image) !!}" class="img-circle" alt="User Image" />
            <p>
                {!! $profile_name !!}
                <small>Member since {!! $profile_join_at !!}</small>
            </p>
        </li>
        <!-- Menu Body 
        <li class="user-body">
            <div class="col-xs-4 text-center">
                <a href="#">Followers</a>
            </div>
            <div class="col-xs-4 text-center">
                <a href="#">Sales</a>
            </div>
            <div class="col-xs-4 text-center">
                <a href="#">Friends</a>
            </div>
        </li> -->
        <!-- Menu Footer-->
        <li class="user-footer">
            <div class="pull-left">
                <a href="{!! url($profile_page_url) !!}" class="btn btn-default btn-flat">Profile</a>
            </div>
            <div class="pull-right">
                <a href="{!! url($profile_logout_url) !!}" class="btn btn-default btn-flat">Sign out</a>
            </div>
        </li>
    </ul>
</li>