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
                <i class="menu-icon tf-icons ti ti-users"></i>
                <div data-i18n="Pengguna">Pengguna</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ session('activeMenu') == 'listUsers' ? 'active' : '' }}">
                    <a href="{{ route('admin.users.index') }}" class="menu-link">
                        <div data-i18n="Daftar Pengguna">Daftar Pengguna</div>
                    </a>
                </li>
                <li class="menu-item {{ session('activeMenu') == 'createUser' ? 'active' : '' }}">
                    <a href="{{ route('admin.users.create') }}" class="menu-link">
                        <div data-i18n="Tambah Pengguna">Tambah Pengguna</div>
                    </a>
                </li>
            </ul>
        </li>

        <li class="menu-item {{ session('activeMenu') == 'margas' ? 'active' : '' }} {{ session('activeParentMenu') == 'margas' ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-script"></i>
                <div data-i18n="Marga">Marga</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ session('activeMenu') == 'listMarga' ? 'active' : '' }}">
                    <a href="{{ route('admin.margas.index') }}" class="menu-link">
                        <div data-i18n="Daftar Marga">Daftar Marga</div>
                    </a>
                </li>
            </ul>
        </li>

        <li class="menu-item {{ session('activeMenu') == 'people' ? 'active' : '' }} {{ session('activeParentMenu') == 'people' ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-script"></i>
                <div data-i18n="Orang">Orang</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ session('activeMenu') == 'listMarga' ? 'active' : '' }}">
                    <a href="{{ route('admin.people.index') }}" class="menu-link">
                        <div data-i18n="Daftar Orang">Daftar Orang</div>
                    </a>
                </li>
            </ul>
        </li>

        <li class="menu-item {{ session('activeMenu') == 'marriages' ? 'active' : '' }} {{ session('activeParentMenu') == 'marriages' ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-script"></i>
                <div data-i18n="Pernikahan">Pernikahan</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ session('activeMenu') == 'listMarriages' ? 'active' : '' }}">
                    <a href="{{ route('admin.marriages.index') }}" class="menu-link">
                        <div data-i18n="Daftar Pernikahan">Daftar Pernikahan</div>
                    </a>
                </li>
            </ul>
        </li>

        {{-- <li class="menu-item {{ session('activeMenu') == 'marriages' ? 'active' : '' }} {{ session('activeParentMenu') == 'marriages' ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-script"></i>
                <div data-i18n="Child">Child</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ session('activeMenu') == 'listMarriages' ? 'active' : '' }}">
                    <a href="{{ route('admin.parent-child.create') }}" class="menu-link">
                        <div data-i18n="Tambah Child">Tambah Child</div>
                    </a>
                </li>
            </ul>
        </li> --}}

        <li class="menu-item {{ session('activeMenu') == 'marriages' ? 'active' : '' }} {{ session('activeParentMenu') == 'marriages' ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-script"></i>
                <div data-i18n="Partuturan">Partuturan</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ session('activeMenu') == 'listMarriages' ? 'active' : '' }}">
                    <a href="{{ route('admin.partuturan-rules.index') }}" class="menu-link">
                        <div data-i18n="Partuturan Rule">Partuturan Rule</div>
                    </a>
                </li>
                <li class="menu-item {{ session('activeMenu') == 'listMarriages' ? 'active' : '' }}">
                    <a href="{{ route('admin.partuturan-categories.index') }}" class="menu-link">
                        <div data-i18n="Partuturan Category">Partuturan Category</div>
                    </a>
                </li>
                <li class="menu-item {{ session('activeMenu') == 'listMarriages' ? 'active' : '' }}">
                    <a href="{{ route('admin.partuturan-terms.index') }}" class="menu-link">
                        <div data-i18n="Partuturan Term">Partuturan Term</div>
                    </a>
                </li>
                <li class="menu-item {{ session('activeMenu') == 'listMarriages' ? 'active' : '' }}">
                    <a href="{{ route('admin.relationship-patterns.index') }}" class="menu-link">
                        <div data-i18n="Relationship Pattern">Relationship Pattern</div>
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</aside>