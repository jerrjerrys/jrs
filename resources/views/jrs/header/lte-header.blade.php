<header class="main-header">
    <!-- Logo -->
    <a href="index2.html" class="logo"><b>Admin</b>LTE</a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                @include('jrs.header.lte-messages')
                @include('jrs.header.lte-profile-settings')
            </ul>
        </div>
    </nav>
</header>