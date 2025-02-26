<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo ">
        <a href="{{ route('admin.dashboard') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img 
                    src="{{ asset('images/logo/logo.png') }}" 
                    style="width: 72%;"
                    alt="Tarombo" 
                />
            </span>
            <span class="app-brand-text demo menu-text fw-bold">Tarombo</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
            <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <li class="menu-item {{ session('activeMenu') == 'dashboard' ? 'active' : '' }}">
            <a href="{{ route('admin.dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-smart-home"></i>
                <div data-i18n="Dashboard">Dashboard</div>
            </a>
        </li>
        <li class="menu-item {{ session('activeMenu') == 'users' ? 'active' : '' }} {{ session('activeParentMenu') == 'users' ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-script"></i>
                <div data-i18n="Users">Users</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ session('activeMenu') == 'listUsers' ? 'active' : '' }}">
                    <a href="{{ route('admin.users.index') }}" class="menu-link">
                        <div data-i18n="Daftar Users">Daftar Users</div>
                    </a>
                </li>
            </ul>
        </li>
        <li class="menu-item {{ session('activeMenu') == 'users' ? 'active' : '' }} {{ session('activeParentMenu') == 'users' ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-script"></i>
                <div data-i18n="Keluarga">Keluarga</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ session('activeMenu') == 'listUsers' ? 'active' : '' }}">
                    <a href="{{ route('admin.family_members.index') }}" class="menu-link">
                        <div data-i18n="Daftar Keluarga">Daftar Keluarga</div>
                    </a>
                </li>
            </ul>
        </li>

        <li class="menu-item {{ session('activeMenu') == 'users' ? 'active' : '' }} {{ session('activeParentMenu') == 'users' ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-script"></i>
                <div data-i18n="Pernikahan">Pernikahan</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ session('activeMenu') == 'listUsers' ? 'active' : '' }}">
                    <a href="{{ route('admin.marriages.index') }}" class="menu-link">
                        <div data-i18n="Daftar Pernikahan">Daftar Pernikahan</div>
                    </a>
                </li>
            </ul>
        </li>

        <li class="menu-item {{ session('activeMenu') == 'users' ? 'active' : '' }} {{ session('activeParentMenu') == 'users' ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-script"></i>
                <div data-i18n="Hubungan ">Hubungan </div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ session('activeMenu') == 'listUsers' ? 'active' : '' }}">
                    <a href="{{ route('admin.relations.index') }}" class="menu-link">
                        <div data-i18n="Daftar Hubungan ">Daftar Hubungan </div>
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</aside>