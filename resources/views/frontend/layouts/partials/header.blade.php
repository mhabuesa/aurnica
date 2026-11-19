<header id="header" class="u-header u-header-left-aligned-nav mb-0">
    <div class="u-header__section">
        <!-- Logo-Search-header-icons -->
        <div class="bg-">
            <div class="container">
                <div class="row min-height-64 align-items-center position-relative">
                    <!-- Logo-offcanvas-menu -->
                    <div class="col-auto">
                        <!-- Nav -->
                        <nav class="navbar navbar-expand u-header__navbar py-0 max-width-200 min-width-200">
                            <!-- Logo -->
                            <a class="order-1 order-xl-0 navbar-brand u-header__navbar-brand u-header__navbar-brand-center mx-auto"
                                href="{{ route('index') }}" aria-label="Electro">
                                <img src="{{ asset(setting()->header_logo) }}" alt=""
                                    style="width: 90px; margin: auto;">
                            </a>
                            <!-- End Logo -->

                            <!-- Fullscreen Toggle Button -->
                            <button id="sidebarHeaderInvokerMenu" type="button"
                                class="navbar-toggler d-block d-xl-none btn u-hamburger mr-3 mr-xl-0"
                                aria-controls="sidebarHeader" aria-haspopup="true" aria-expanded="false"
                                data-unfold-event="click" data-unfold-hide-on-scroll="false"
                                data-unfold-target="#sidebarHeader1" data-unfold-type="css-animation"
                                data-unfold-animation-in="fadeInLeft" data-unfold-animation-out="fadeOutLeft"
                                data-unfold-duration="500">
                                <span id="hamburgerTriggerMenu" class="u-hamburger__box">
                                    <span class="u-hamburger__inner"></span>
                                </span>
                            </button>
                            <!-- End Fullscreen Toggle Button -->
                        </nav>
                        <!-- End Nav -->

                        <!-- ========== HEADER SIDEBAR ========== -->
                        @include('frontend.layouts.partials.mobile_menu')
                        <!-- ========== END HEADER SIDEBAR ========== -->
                    </div>
                    <!-- End Logo-offcanvas-menu -->
                    <!-- Search Bar -->
                    <div class="col d-none d-xl-block">
                        <form class="js-focus-state" action="{{ route('search.product') }}" method="GET">
                            <label class="sr-only" for="searchproduct">Search</label>
                            <div class="input-group">
                                <input type="text"
                                    class="form-control py-2 pl-5 font-size-15 border-right-0 height-42 rounded-left-pill border-primary"
                                    name="q" id="searchproduct-item" placeholder="Search for Products"
                                    aria-label="Search for Products" aria-describedby="searchProduct1" required
                                    value="{{ request()->input('q') }}">
                                <div class="input-group-append">

                                    <!-- End Select -->
                                    <button class="btn btn-dark height-42 py-2 px-3 rounded-right-pill"
                                        type="submit" id="searchProduct1">
                                        <span class="ec ec-search font-size-20"></span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- End Search Bar -->
                    <!-- Header Icons -->
                    <div class="col col-xl-auto text-right text-xl-left pl-0 pl-xl-3 position-static">
                        <div class="d-inline-flex">
                            <ul class="d-flex list-unstyled mb-0 align-items-center">
                                <!-- Search -->
                                <li class="col d-xl-none px-2 px-sm-3 position-static">
                                    <a id="searchClassicInvoker"
                                        class="font-size-22 text-gray-90 text-lh-1 btn-text-secondary"
                                        href="javascript:void(0)" role="button" title="Search">
                                        <span class="ec ec-search"></span>
                                    </a>
                                </li>
                                <!-- End Search -->

                                <li class="col px-2 px-sm-3">
                                    <a href="{{ route('cart') }}" class="text-gray-90 position-relative d-flex"
                                        data-toggle="tooltip" data-placement="top" title="Cart">
                                        <i class="font-size-22 ec ec-shopping-bag"></i>
                                        <span id="cartCount"
                                            class="width-22 height-22 bg-dark position-absolute d-flex align-items-center justify-content-center rounded-circle left-12 top-8 font-weight-bold font-size-12 text-white">
                                            {{ $cartCount }}
                                        </span>
                                    </a>
                                </li>

                                @if (auth()->guard('customer')->check())
                                    <li class="col d-xl-none pl-3 px-sm-3"><a
                                            href="{{ route('customer.dashboard') }}" class="text-gray-90"
                                            data-toggle="tooltip" data-placement="top" title="My Account"><i
                                                class="font-size-22 ec ec-user"></i></a></li>
                                @else
                                    <li class="col d-xl-none pl-3 px-sm-3"><a href="{{ route('customer.login') }}"
                                            class="text-gray-90" data-toggle="tooltip" data-placement="top"
                                            title="Login"><i class="font-size-22 ec ec-user"></i></a></li>
                                @endif

                                @if (auth()->guard('customer')->check())
                                    <li class="col d-none d-xl-block px-2 px-sm-3">
                                        <a id="sidebarNavToggler" href="{{ route('customer.dashboard') }}"
                                            class="u-header-topbar__nav-link target-of-invoker-has-unfolds text-black d-flex">
                                            <i class="font-size-22 ec ec-user mr-2"></i>
                                            Profile
                                        </a>
                                    </li>
                                @else
                                    <li class="col d-none d-xl-block px-2 px-sm-3">
                                        <a id="sidebarNavToggler" href="javascript:;" role="button"
                                            class="u-header-topbar__nav-link target-of-invoker-has-unfolds text-black"
                                            aria-controls="sidebarContent" aria-haspopup="true"
                                            aria-expanded="false" data-unfold-event="click"
                                            data-unfold-hide-on-scroll="false" data-unfold-target="#sidebarContent"
                                            data-unfold-type="css-animation" data-unfold-animation-in="fadeInRight"
                                            data-unfold-animation-out="fadeOutRight" data-unfold-duration="500">
                                            <i class="font-size-22 ec ec-user"></i>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                    <!-- End Header Icons -->
                </div>
            </div>
        </div>
        <!-- End Logo-Search-header-icons -->

        <!-- Vertical-and-secondary-menu (STUCK ON SCROLL) -->
        <div id="stickyNavBar" class="box-shadow-1 d-none d-xl-block bg-primary">
            <div class="container">
                <div class="row">
                    <!-- Vertical Menu -->
                    <div class="col-md-auto d-none d-xl-block align-self-center">
                        <div class="max-width-200 min-width-200">
                            <!-- Basics Accordion -->
                            <div id="basicsAccordion">
                                <!-- Card -->
                                <div class="card border-0 position-relative">
                                    <div class="card-header card-collapse border-0" id="basicsHeadingOne">
                                        <button type="button"
                                            class="btn-link btn-block d-flex card-btn py-3 text-lh-1 pl-3 pr-4 shadow-none btn-primary text-white bg-primary-btn border-0 font-weight-bold justify-content-center align-items-center"
                                            data-toggle="collapse" data-target="#basicsCollapseOne"
                                            aria-expanded="false" aria-controls="basicsCollapseOne">
                                            <span class="mr-2 text-light">
                                                <i class="fas fa-bars"></i>
                                            </span>
                                            <span class="text-light">All Categories</span>
                                        </button>
                                    </div>

                                    <!-- All Categories Mega Panel -->
                                    <div id="basicsCollapseOne" class="collapse all-cat-mega-panel"
                                        aria-labelledby="basicsHeadingOne" data-parent="#basicsAccordion">
                                        <div class="card-body p-4 bg-white shadow-lg border rounded">
                                            <div class="row">
                                                @foreach ($categories as $category)
                                                    <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4 d-flex">
                                                        <div
                                                            class="all-cat-card w-100 p-3 border rounded bg-white d-flex flex-column justify-content-between">
                                                            <div>
                                                                <!-- Category Title -->
                                                                <h6 class="all-cat-title font-weight-bold mb-3">
                                                                    <a href="{{ route('category', $category->slug) }}"
                                                                        class="text-dark text-decoration-none">
                                                                        {{ $category->name }}
                                                                    </a>
                                                                </h6>

                                                                <!-- Category Image -->
                                                                <div class="all-cat-img-box text-center mb-3">
                                                                    <a
                                                                        href="{{ route('category', $category->slug) }}">
                                                                        <img src="{{ $category->logo ? asset($category->logo) : asset('images/default-category.png') }}"
                                                                            alt="{{ $category->name }}"
                                                                            class="img-fluid all-cat-img">
                                                                    </a>
                                                                </div>

                                                                <!-- Subcategories List (Top 4) -->
                                                                <ul class="list-unstyled all-cat-sublist mb-2">
                                                                    @foreach ($category->subcategories->take(4) as $subcategory)
                                                                        <li class="mb-2">
                                                                            <a href="{{ route('subcategory', $subcategory->slug) }}"
                                                                                class="all-cat-sublink text-muted text-decoration-none">
                                                                                {{ $subcategory->name }}
                                                                            </a>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>

                                                            <!-- View All Link -->
                                                            <div class="mt-1">
                                                                <a href="{{ route('category', $category->slug) }}"
                                                                    class="all-cat-view-all font-weight-bold text-decoration-none">
                                                                    View All
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Card -->
                            </div>
                            <!-- End Basics Accordion -->
                        </div>
                    </div>
                    <!-- End Vertical Menu -->
                    <!-- Secondary Menu -->
                    <div class="col secondary-menu py-0">
                        <!-- Nav -->
                        <nav
                            class="js-mega-menu navbar navbar-expand-md u-header__navbar u-header__navbar--no-space">
                            <!-- Navigation -->
                            <div id="navBar" class="collapse navbar-collapse u-header__navbar-collapse">
                                <ul class="navbar-nav u-header__navbar-nav">
                                    <!-- Pages -->
                                    <li class="nav-item hs-has-mega-menu u-header__nav-item">
                                        <a class="nav-link u-header__nav-link text-warning font-weight-bold"
                                            href="{{ route('supper.deals') }}">Supper Deals</a>
                                    </li>
                                    <!-- End Pages -->

                                    <!-- Featured Brands -->
                                    <li class="nav-item u-header__nav-item">
                                        <a class="nav-link u-header__nav-link" href="{{ route('brands') }}"
                                            aria-haspopup="true" aria-expanded="false"
                                            aria-labelledby="pagesSubMenu">Brands</a>
                                    </li>
                                    <!-- End Featured Brands -->

                                    <!-- Blog Styles -->
                                    <li class="nav-item u-header__nav-item">
                                        <a class="nav-link u-header__nav-link" href="{{ route('blog') }}"
                                            aria-haspopup="true" aria-expanded="false"
                                            aria-labelledby="blogSubMenu">Blog </a>
                                    </li>
                                    <!-- End Blog Styles -->
                                </ul>
                            </div>
                            <!-- End Navigation -->
                        </nav>
                        <!-- End Nav -->
                    </div>
                    <!-- End Secondary Menu -->
                </div>
            </div>
        </div>
        <!-- End Vertical-and-secondary-menu -->
    </div>
</header>