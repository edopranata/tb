<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('dashboard.index') }}" class="brand-link">
        <img src="{{ asset('dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">AdminLTE 3</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar px-0">
        <!-- Sidebar user (optional) -->
        <div class="user-panel mt-3 pb-3 px-2 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('dist/img/avatar5.png') }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ auth()->user()->name }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-flat" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-header">{{ strtoupper(env('app_name')) }}</li>
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->
                <x-sidebar.menu-item :title="$title = 'Dashboard'" :href="route('dashboard.index')" :active="request()->routeIs('dashboard.index')">
                    <x-slot name="icon">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                    </x-slot>
                </x-sidebar.menu-item>
                <!-- Management Users Roles and Permissions-->
                <x-sidebar.menu-dropdown :title="$title = 'App Management'" :active="request()->routeIs('pages.management.*')">
                    <x-slot name="icon">
                        <i class="nav-icon fas fa-cog"></i>
                    </x-slot>
                    <x-sidebar.menu-item :title="$title = 'Permission List'" :href="route('pages.management.permissions.index')" :active="request()->routeIs('pages.management.permissions.*')"/>
                    <x-sidebar.menu-item :title="$title = 'Role List'" :href="route('pages.management.roles.index')" :active="request()->routeIs('pages.management.roles.*')"/>
                    <x-sidebar.menu-item :title="$title = 'User List'" :href="route('pages.management.users.index')" :active="request()->routeIs('pages.management.users.*')"/>

                </x-sidebar.menu-dropdown>

                <!-- Master Data Categories, Suppliers, Products Unit -->
                <x-sidebar.menu-dropdown :title="$title = 'Master Data'" :active="request()->routeIs('pages.units.*') || request()->routeIs('pages.categories.*') || request()->routeIs('pages.suppliers.*')">
                    <x-slot name="icon">
                        <i class="nav-icon fas fa-boxes"></i>
                    </x-slot>
                    <x-sidebar.menu-item :title="$title = 'Satuan Produk'" :href="route('pages.units.index')" :active="request()->routeIs('pages.units.*')"/>
                    <x-sidebar.menu-item :title="$title = 'Kategori Produk'" :href="route('pages.categories.index')" :active="request()->routeIs('pages.categories.*')"/>
                    <x-sidebar.menu-item :title="$title = 'Data Pemasok'" :href="route('pages.suppliers.index')" :active="request()->routeIs('pages.suppliers.*')"/>

                </x-sidebar.menu-dropdown>

                <!-- Management Product Add new product, set multi prices -->
                <x-sidebar.menu-dropdown :title="$title = 'Manajemen Produk'" :active="request()->routeIs('pages.products.*') || request()->routeIs('pages.prices.*')">
                    <x-slot name="icon">
                        <i class="nav-icon fas fa-dolly"></i>
                    </x-slot>
                    <x-sidebar.menu-item :title="$title = 'Data Produk'" :href="route('pages.products.index')" :active="request()->routeIs('pages.products.*')"/>
                    <x-sidebar.menu-item :title="$title = 'Multi Harga'" :href="route('pages.prices.index')" :active="request()->routeIs('pages.prices.*')"/>

                </x-sidebar.menu-dropdown>

                <li class="nav-header">TRANSAKSI</li>
                <!-- Product Inventory and product transfer (warehouse stock to store) -->
                <x-sidebar.menu-dropdown :title="$title = 'Inventori'" :active="request()->routeIs('pages.inventories.*') || request()->routeIs('pages.stock.*')">
                    <x-slot name="icon">
                        <i class="nav-icon fas fa-dolly-flatbed"></i>
                    </x-slot>
                    <x-sidebar.menu-item :title="$title = 'Inventori Produk Masuk'" :href="route('pages.inventories.index')" :active="request()->routeIs('pages.inventories.*')"/>
                    <x-sidebar.menu-item :title="$title = 'Transfer Stok'" :href="route('pages.stock.index')" :active="request()->routeIs('pages.stock.index')"/>
                    <x-sidebar.menu-item :title="$title = 'Transfer Gudang ke Toko'" :href="route('pages.stock.transfer.store')" :active="request()->routeIs('pages.stock.transfer.store')"/>
                    <x-sidebar.menu-item :title="$title = 'Transfer Toko ke Gudang'" :href="route('pages.stock.transfer.warehouse')" :active="request()->routeIs('pages.stock.transfer.warehouse')"/>

                </x-sidebar.menu-dropdown>
                <!-- Transaksi Penjualan -->
                <x-sidebar.menu-dropdown :title="$title = 'Transaksi'" :active="request()->routeIs('pages.transaction.*')">
                    <x-slot name="icon">
                        <i class="nav-icon fas fa-money-bill"></i>
                    </x-slot>
                    <x-sidebar.menu-item :title="$title = 'Transaksi Penjualan'" :href="route('pages.transaction.index')" :active="request()->routeIs('pages.transaction.*')"/>
                </x-sidebar.menu-dropdown>

                <!-- Laporan -->
                <li class="nav-header">LAPORAN</li>
                <x-sidebar.menu-dropdown :title="$title = 'Laporan Stock'" :active="request()->routeIs('pages.reporting.*')">
                    <x-slot name="icon">
                        <i class="nav-icon fas fa-paperclip"></i>
                    </x-slot>
                    <x-sidebar.menu-item :title="$title = 'Transfer Produk'" :href="route('pages.reporting.stock.index')" :active="request()->routeIs('pages.reporting.stock.*')"/>
                    <x-sidebar.menu-item :title="$title = 'Inventori Produk Masuk'" :href="route('pages.reporting.inventory.index')" :active="request()->routeIs('pages.reporting.inventory.*')"/>

                </x-sidebar.menu-dropdown>
{{--                <li class="nav-header">LABELS</li>--}}
{{--                <li class="nav-item">--}}
{{--                    <a href="#" class="nav-link">--}}
{{--                        <i class="nav-icon far fa-circle text-danger"></i>--}}
{{--                        <p class="text">Important</p>--}}
{{--                    </a>--}}
{{--                </li>--}}
{{--                <li class="nav-item">--}}
{{--                    <a href="#" class="nav-link">--}}
{{--                        <i class="nav-icon far fa-circle text-warning"></i>--}}
{{--                        <p>Warning</p>--}}
{{--                    </a>--}}
{{--                </li>--}}
{{--                <li class="nav-item">--}}
{{--                    <a href="#" class="nav-link">--}}
{{--                        <i class="nav-icon far fa-circle text-info"></i>--}}
{{--                        <p>Informational</p>--}}
{{--                    </a>--}}
{{--                </li>--}}
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
