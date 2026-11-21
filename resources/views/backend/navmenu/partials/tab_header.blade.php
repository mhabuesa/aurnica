<ul class="nav nav-tabs nav-tabs-alt" role="tablist">
    <li class="nav-item" role="presentation">
        <a href="{{route('admin.navMenu.index')}}" class="nav-link {{ request()->routeIs('admin.navMenu.index') ? 'active' : '' }}">Category & SubCategory</a>
    </li>
    <li class="nav-item" role="presentation">
         <a href="{{route('admin.navMenu.product')}}" class="nav-link {{ request()->routeIs('admin.navMenu.product') ? 'active' : '' }}">Nav Menu Product</a>
    </li>
</ul>
