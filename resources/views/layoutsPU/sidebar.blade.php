<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">UD<sup>Sumber Pangan</sup></div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item {{ request()->is('dashboard') ? 'active' : '' }}">
                <a class="nav-link" href="/dashboardPU">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Manajemen Usaha Penggilingan Padi
            </div>

            <!-- Nav Item - Tables -->
            <li class="nav-item {{ request()->is('pgabahs') ? 'active' : '' }}">
                <a class="nav-link" href="/pgabahs">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Pembelian Padi</span></a>
            </li>
            <li class="nav-item" {{ request()->is('penggajian') ? 'active' : '' }}">
                <a class="nav-link" href="/penggajian">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Penggajian</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages"
                    aria-expanded="true" aria-controls="collapsePages">
                    <i class="fas fa-fw fa-folder"></i>
                    <span>Laporan</span>
                </a>
                <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Laporan:</h6>
                        <a class="collapse-item" href="/produk">Laporan Stok Produk</a>
                        <a class="collapse-item" href="/laporan/penjualan">Laporan Penjualan</a>
                        <a class="collapse-item" href="/laporan/pengeluaran">Laporan Pengeluaran</a>
                        <a class="collapse-item" href="/laporan">Laporan Keseluruhan</a>
                        <div class="collapse-divider"></div>
                    </div>
                </div>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

    

        </ul>