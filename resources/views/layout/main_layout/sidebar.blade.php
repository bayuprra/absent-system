@php
    $role = session()->get('data')->nama_role ?? 'gu';
    $nama = session()->get('data')->namaKaryawan ?? 'Admin';
@endphp
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('AdminLTE/dist/img/avatar.png') }} " class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ $nama }}</a>
            </div>
        </div>

        <div class="user-panel mx-auto">
            @php
                use Carbon\Carbon;
            @endphp
            <div class="info">
                <a href="#"
                    class="d-block">{{ Carbon::parse(now())->locale('id_ID')->isoFormat('dddd, D MMMM YYYY | H:mm') }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                @if ($role == 'Admin')
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link">
                            <i class=" nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Dashboard
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('karyawan') }}" class="nav-link">
                            <i class="nav-icon fas fa-user-alt"></i>
                            <p>
                                Karyawan
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('divisi') }}" class="nav-link">
                            <i class="nav-icon fas fa-building"></i>
                            <p>
                                Divisi
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('absentData') }}" class="nav-link">
                            <i class="nav-icon fas fa-portrait"></i>
                            <p>
                                Absent
                            </p>
                        </a>
                    </li>
                @endif
                @if ($role == 'karyawan')
                    <li class="nav-item">
                        <a href="{{ route('userAbsent') }}" class="nav-link">
                            <i class="nav-icon fas fa-user-alt"></i>
                            <p>
                                Absensi
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('userAbsentData') }}" class="nav-link">
                            <i class="nav-icon fas fa-history"></i>
                            <p>
                                Riwayat Absensi
                            </p>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
