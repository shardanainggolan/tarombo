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
        <li class="menu-item {{ session('activeMenu') == 'dashboards' ? 'active' : '' }}">
            <a href="{{ route('admin.margas.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-smart-home"></i>
                <div data-i18n="Marga">Marga</div>
            </a>
        </li>
        <li class="menu-item {{ session('activeMenu') == 'dashboards' ? 'active' : '' }}">
            <a href="{{ route('admin.families.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-smart-home"></i>
                <div data-i18n="Families">Families</div>
            </a>
        </li>
        <li class="menu-item {{ session('activeMenu') == 'dashboards' ? 'active' : '' }}">
            <a href="{{ route('admin.sapaan_terms.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-smart-home"></i>
                <div data-i18n="Sapaan Terms">Sapaan Terms</div>
            </a>
        </li>
        <li class="menu-item {{ session('activeMenu') == 'dashboards' ? 'active' : '' }}">
            <a href="{{ route('admin.sapaan_rules.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-smart-home"></i>
                <div data-i18n="Sapaan Rules">Sapaan Rules</div>
            </a>
        </li>
        <li class="menu-item {{ session('activeMenu') == 'dashboards' ? 'active' : '' }}">
            <a href="{{ route('admin.users.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-smart-home"></i>
                <div data-i18n="Users">Users</div>
            </a>
        </li>
    </ul>
</aside>