<nav class="navbar top-navbar navbar-expand-md navbar-dark">
    <!-- ============================================================== -->
    <!-- Logo -->
    <!-- ============================================================== -->
    <div class="navbar-header">
        <a class="navbar-brand" href="index.html">
            <!-- Logo icon --><b>
                <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                <!-- Dark Logo icon -->
                <img src="../assets/images/logo-icon.png" alt="homepage" class="dark-logo" />
                <!-- Light Logo icon -->
                <img src="../assets/images/logo-light-icon.png" alt="homepage" class="light-logo" />
            </b>
            <!--End Logo icon -->
            <span class="hidden-xs"><span class="font-bold">Inventory</span>WBI</span>
        </a>
    </div>
    <!-- ============================================================== -->
    <!-- End Logo -->
    <!-- ============================================================== -->
    <div class="navbar-collapse">
        <!-- ============================================================== -->
        <!-- toggle and nav items -->
        <!-- ============================================================== -->
        <ul class="navbar-nav me-auto">
            <!-- This is  -->
            <li class="nav-item"> <a class="nav-link nav-toggler d-block d-md-none waves-effect waves-dark"
                    href="javascript:void(0)"><i class="ti-menu"></i></a> </li>
            <li class="nav-item"> <a
                    class="nav-link sidebartoggler d-none d-lg-block d-md-block waves-effect waves-dark"
                    href="javascript:void(0)"><i class="icon-menu"></i></a> </li>
            <!-- ============================================================== -->
            <!-- Search -->
            <!-- ============================================================== -->
            <li class="nav-item">
                <form class="app-search d-none d-md-block d-lg-block">
                    <input type="text" class="form-control" placeholder="Search & enter">
                </form>
            </li>
        </ul>
        <!-- ============================================================== -->
        <!-- User profile and search -->
        <!-- ============================================================== -->
        <ul class="navbar-nav my-lg-0">
            <!-- Notifikasi -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" data-bs-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false"> <i class="ti-email"></i>
                    <div class="notify"> <span class="heartbit"></span> <span class="point"></span> </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end mailbox animated bounceInDown">
                    <ul>
                        <li>
                            <div class="drop-title">Notifications</div>
                        </li>
                        <li>
                            <div class="message-center">
                            </div>
                        </li>
                        <li>
                            <a class="nav-link text-center link" href="javascript:void(0);"> <strong>Check
                                    all notifications</strong> <i class="fa fa-angle-right"></i> </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item dropdown u-pro">
                <a class="nav-link dropdown-toggle waves-effect waves-dark profile-pic" href=""
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    @if(auth()->user()->profile_photo)
                    <img src="{{ asset('storage/profile_pictures/' . auth()->user()->profile_photo) }}" alt="Profile Photo" class="profile-photo rounded-circle" width="30" height="30">
                    @else
                    <img src="{{ asset('assets/images/unknown.jpg') }}" alt="Default Profile Photo" class="profile-photo rounded-circle">
                    
                    @endif <span class="hidden-md-down">{{ Auth::user()->name }} &nbsp;<i
                            class="fa fa-angle-down"></i></span>
                </a>
                <div class="dropdown-menu dropdown-menu-end animated flipInY">
                    <a href="{{ route('profile.edit') }}" class="dropdown-item"><i class="fa-solid fa-user"></i></i>
                        Profile</a>

                    <a href="javascript:void(0)" class="dropdown-item"><i class="ti-email"></i> Inbox</a>
                    <!-- text-->
                    <!-- text-->
                    <div class="dropdown-divider"></div>
                    <!-- text-->
                    <!-- text-->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                            <i class="fa fa-power-off"></i> Logout
                        </a>
                    </form>


                    <!-- text-->
                </div>
            </li>
            <!-- ============================================================== -->
            <!-- End User Profile -->
            <!-- ============================================================== -->
            <li class="nav-item right-side-toggle"> <a class="nav-link  waves-effect waves-light"
                    href="javascript:void(0)"><i class="ti-settings"></i></a></li>
        </ul>
    </div>
</nav>
