<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{!! asset($profile_image) !!}" class="img-circle" alt="User Image" />
            </div>
            <div class="pull-left info">
                <p>{!! $profile_name !!}</p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <!-- search form 
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
                <span class="input-group-btn">
                    <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button>
                </span>
            </div>
        </form>
        /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">            
            <li class="active treeview">
                <a href="#">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{!! url('auth/user') !!}"><i class="fa fa-circle-o"></i> User</a></li>
                    <li><a href="{!! url('auth/role') !!}"><i class="fa fa-circle-o"></i> Role</a></li>
                    <li><a href="{!! url('auth/page') !!}"><i class="fa fa-circle-o"></i> Page</a></li>
                    <li><a href="{!! url('auth/role-page') !!}"><i class="fa fa-circle-o"></i> Role Page</a></li>                    
                </ul>
            </li>                        
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>