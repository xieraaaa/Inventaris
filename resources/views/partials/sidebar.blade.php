    <!-- ============================================================== -->
    <!-- Left Sidebar - style you can find in sidebar.scss  -->
    <!-- ============================================================== -->
    <aside class="left-sidebar">
        <!-- Sidebar scroll-->
        <div class="scroll-sidebar">
            <!-- Sidebar navigation-->
            <nav class="sidebar-nav">
                <ul id="sidebarnav">
                    <li class="user-pro"> <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                        @if(auth()->user()->profile_photo)
                        <img src="{{ asset('storage/profile_pictures/' . auth()->user()->profile_photo) }}" alt="Profile Photo" class="profile-photo rounded-circle" width="30" height="30">
                        @else
                        <img src="{{ asset('assets/images/unknown.jpg') }}" alt="Default Profile Photo" class="profile-photo rounded-circle">
                        @endif <span class="hide-menu">Prof. {{ Str::limit(Auth::user()->name, 7, '') }}</span></a>
                        <ul aria-expanded="false" class="collapse">
                            <li><a href="{{route('profile.edit')}}"><i class="ti-user"></i>Profile</a></li>
                            <li><a href="javascript:void(0)"><i class="ti-email"></i>Inbox</a></li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    <i class="fa fa-power-off"></i>Logout
                                </a>
                            </form>
                        </ul>
                    </li>
                    
                    <li><a class="waves-effect waves-dark" href="{{ route('dashboard') }}"><i class="icon-speedometer"></i><span class="hide-menu">Dashboard</span></a>
                    <li><a class="waves-effect waves-dark" href="{{ route('riwayat') }}"><i class="fa-solid fa-clock-rotate-left"></i><span class="hide-menu">Riwayat</span></a>

                    <!-- Letakkan page-page yang hanya bisa diakses oleh Admin dibawah -->
                    
                    @if ($admin)    

                    <li><a class="waves-effect waves-dark" href="{{ route('barang') }}"><i class="fa-solid fa-box-open"></i><span class="hide-menu">Barang</span></a>
                    <li><a class="waves-effect waves-dark" href="{{route('minjam')}}"><i class="fa-solid fa-handshake"></i><span class="hide-menu">Peminjaman</span></a>
                    </li>
                    <li> <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="fa-solid fa-crown"></i><span class="hide-menu">Master</span></a>
                        <ul aria-expanded="false" class="collapse">
                            <li><a href="{{ route('kategori') }}">Kategori</a></li>
                            <li><a href="{{ route('merek') }}">Merek</a></li>
                            <li><a href="{{ route('unit') }}">Unit</a></li>
                        </ul>
                    </li>

                    @endif
            </nav>
        </div>
    </aside>