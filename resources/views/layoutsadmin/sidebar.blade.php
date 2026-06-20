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
                <a class="nav-link" href="/dashboardAdmin">
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
            <li class="nav-item {{ request()->is('penjualan') ? 'active' : '' }}">
                <a class="nav-link" href="/penjualan">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Penjualan Produk</span></a>
            </li>
            <li class="nav-item" {{ request()->is('pengeluaran') ? 'active' : '' }}">
                <a class="nav-link" href="/pengeluaran">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Pengeluaran</span></a>
            </li>
            <li class="nav-item" {{ request()->is('penggilingan') ? 'active' : '' }}">
                <a class="nav-link" href="/penggilingan">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Penggilingan Padi</span></a>
            </li>
            <li class="nav-item {{ request()->is('pembeli') ? 'active' : '' }}">
                <a class="nav-link" href="/pembeli">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Distributor</span></a>
            </li>
            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

    

        </ul>