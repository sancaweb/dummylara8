<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index.php" class="brand-link">
        <img src="{{ asset('v1/images/e-logo.jpg') }}" alt="{{ config('app.name') }}"
            class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">{{ config('app.name') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img id="userImageSide" src="{{ auth()->user()->takeImage() }}" class="rounded elevation-2"
                    style="width: 2.1rem;height: 2.1rem;object-fit:cover;object-position:center;" alt="User Image">
            </div>
            <div class="info">
                <a id="userNameSide" href="{{ route('profile') }}"
                    class="d-block">{{ auth()->user()->name }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="{{ route('root') }}" class="nav-link {{ $page == 'dasbhoard' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i> &nbsp;
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                <li class="nav-header">DATA MASTER</li>

                <li class="nav-item">
                    <a href="{{ route('page') }}" class="nav-link {{ $page == 'page' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-alt"></i> &nbsp;
                        <p>
                            Pages
                        </p>
                    </a>
                </li>
                <li class="nav-header">MENU UTAMA</li>
                @can('post read')
                    <li
                        class="nav-item {{ $page == 'post' || $page == 'editPost' || $page == 'postCats' || $page == 'postTags' ? 'menu-is-opening menu-open' : '' }}">
                        <a href="#" class="nav-link">
                            <i class="fas fa-newspaper nav-icon"></i>
                            <p>
                                Posts
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview"
                            {{ $page == 'post' || $page == 'editPost' || $page == 'catTags' ? 'style="display:block;"' : 'style="display:none;"' }}>
                            <li class="nav-item">
                                <a href="{{ route('post') }}"
                                    class="nav-link {{ $page == 'post' || $page == 'editPost' ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Posts</p>
                                </a>
                            </li>

                            @canany(['category read', 'tag read'])
                                <li class="nav-item">
                                    <a href="{{ route('category') }}"
                                        class="nav-link {{ $page == 'catTags' ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Categories & Tags</p>
                                    </a>
                                </li>
                            @endcanany



                        </ul>
                    </li>
                @endcan



                @role('super admin|admin')
                    <li class="nav-header">System Settings</li>

                    <li class="nav-item">
                        <a href="{{ route('user') }}" class="nav-link {{ $page == 'user' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users-cog"></i> &nbsp;
                            <p>
                                Users
                            </p>
                        </a>
                    </li>

                    @role('super admin')
                        <li
                            class="nav-item {{ $page == 'rolePermission' || $page == 'assignPermission' ? 'menu-is-opening menu-open' : '' }}">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-user-shield"></i>
                                <p>
                                    Roles & Permissions
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview"
                                {{ $page == 'rolePermission' || $page == 'assignPermission' ? 'style="display:block;"' : 'style="display:none;"' }}>
                                <li class="nav-item">
                                    <a href="{{ route('rolepermission') }}"
                                        class="nav-link {{ $page == 'rolePermission' ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Data</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('assignPermission.assign') }}"
                                        class="nav-link {{ $page == 'assignPermission' ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Assign Permissions</p>
                                    </a>
                                </li>


                            </ul>
                        </li>



                        <li class="nav-item">
                            <a href="{{ route('activity') }}" class="nav-link {{ $page == 'activity' ? 'active' : '' }}">
                                <i class="nav-icon fas fa-clipboard-list"></i> &nbsp;
                                <p>
                                    Activity Log
                                </p>
                            </a>
                        </li>
                    @endrole
                @endrole


                <li class="nav-item">
                    <a href="{{ route('activity') }}" class="nav-link" data-toggle="modal"
                        data-target="#logoutModal">
                        <i class="nav-icon fas fa-sign-out-alt"></i> &nbsp;
                        <p>
                            Logout
                        </p>
                    </a>
                </li>


            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
