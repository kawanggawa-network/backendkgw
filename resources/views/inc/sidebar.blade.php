<ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
    <li class="{{ \Request::is('home') ? 'active' : '' }} nav-item"><a href="{{ route('home') }}"><i class="feather icon-home"></i><span class="menu-title">Dashboard</span></a></li>

    <li class="nav-item has-sub {{ in_array(\Request::route()->getName(), [
        'faq.index',
        'faq.create',
        'faq.edit',
        'page.index',
        'page.create',
        'page.edit',
        'banner.index',
        'banner.create',
        'banner.edit',
    ]) ? 'sidebar-group-active' : '' }}"><a href="#"><i class="feather icon-list"></i><span class="menu-title"> Content</span></a>
    <ul class="menu-content">
            {{-- <li class="{{ in_array(\Request::route()->getName(), [
                    'banner.index',
                    'banner.create',
                    'banner.edit',
                ]) ? 'active' : '' }} nav-item"><a href="{{ route('banner.index') }}"><i class="feather icon-airplay"></i><span class="menu-title">Banner</span></a></li> --}}
            <li class="{{ in_array(\Request::route()->getName(), [
                    'page.index',
                    'page.create',
                    'page.edit',
                ]) ? 'active' : '' }} nav-item"><a href="{{ route('page.index') }}"><i class="feather icon-layers"></i><span class="menu-title">Page </span></a></li>
        </ul>
    </li>

    <li class="nav-item has-sub {{ in_array(\Request::route()->getName(), [
        'category.index',
        'category.create',
        'category.edit',
    ]) ? 'sidebar-group-active' : '' }}"><a href="#"><i class="feather icon-calendar"></i><span class="menu-title"> Program</span></a>
        <ul class="menu-content">
            <li class="{{ in_array(\Request::route()->getName(), [
                    'category.index',
                    'category.create',
                    'category.edit',
                ]) ? 'active' : '' }} nav-item"><a href="{{ route('category.index') }}"><i class="feather icon-layers"></i><span class="menu-title">Category </span></a></li>
        </ul>
    </li>

    <li class="{{ in_array(\Request::route()->getName(), [
        'users.index',
        'users.create',
        'users.edit',
    ]) ? 'active' : '' }} nav-item"><a href="{{ route('users.index') }}"><i class="feather icon-users"></i><span class="menu-title">Users </span></a></li>

    <li class="{{ in_array(\Request::route()->getName(), [
        'file-manager',
    ]) ? 'active' : '' }} nav-item"><a href="{{ route('file-manager') }}"><i class="feather icon-folder"></i><span class="menu-title">File Manager </span></a></li>

</ul>
