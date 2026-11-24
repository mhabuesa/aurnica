@extends('frontend.layouts.app')
@section('title', 'Home Page')
@push('header_script')
    <style>
        .banner-image {
            display: block;
            width: 100%;
            max-height: 450px;
            object-fit: cover;
        }

        .banner-carousel-container {
            position: relative;
        }

        .u-slick__arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .u-slick__arrow:hover {
            background: #ffffff;
            color: #000;
        }

        .u-slick__arrow-media-left {
            left: 20px;
        }

        .u-slick__arrow-media-right {
            right: 20px;
        }

        .u-slick__pagination {
            z-index: 5;
            position: absolute;
            bottom: 15px;
            left: 0;
            right: 0;
            display: flex;
            justify-content: center;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .u-slick__pagination li {
            margin: 0 4px;
        }

        .u-slick__pagination li span {
            display: block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            text-indent: -9999px;
            cursor: pointer;
        }

        .u-slick__pagination li.slick-active span {
            width: 35px;
            border-radius: 5px;
        }

        /* New Promo Section Styles */
        .home-promo-section {
            padding-top: 20px;
        }

        .promo-card-main {
            background-color: #fdfbf7;
            border: 1px solid #efebe4;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.02);
            transition: all 0.3s ease;
            height: 100%;
        }

        .promo-card-main:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.05);
            background-color: #fffcf5;
        }

        @media (max-width: 576px) {
            .promo-card-main {
                padding: 8px !important;
            }

            .promo-card-main .promo-icon-wrapper img {
                width: 34px !important;
                height: 34px !important;
            }

            .promo-card-main .promo-content h6 {
                font-size: 12px;
                margin-bottom: 1px;
            }

            .promo-card-main .promo-content p {
                font-size: 10px;
                line-height: 1.1;
            }
        }

        .promo-card-main .promo-content h6 {
            color: #2c3e50;
            margin-bottom: 2px;
        }

        .promo-card-main .promo-content p {
            color: #7f8c8d;
            line-height: 1.2;
        }

        .promo-card-mini {
            background-color: #fdfbf7;
            border: 1px solid #efebe4;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
            transition: all 0.3s ease;
            height: 100%;
        }

        .promo-card-mini:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.05);
            background-color: #fffcf5;
        }

        .promo-card-mini .promo-content h6 {
            color: #2c3e50;
            margin-bottom: 2px;
        }

        .promo-card-mini .promo-content p {
            color: #7f8c8d;
            line-height: 1.1;
        }


        /* Category Section */
        /* Container & Wrapper */
        .home-category-slider-wrapper {
            background: linear-gradient(180deg, #fff7f8 0%, #fffdfd 100%);
            border-radius: 22px;
            padding: 24px;
            box-shadow: 0 8px 24px rgba(225, 29, 72, 0.04);
        }

        /* Header */
        .home-category-header h3 {
            color: #111827;
            font-size: 22px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 0;
        }

        .home-category-header {
            margin-bottom: 15px;
        }

        .title-bar {
            display: inline-block;
            width: 4px;
            height: 22px;
            background-color: #e11d48;
            border-radius: 4px;
        }

        .view-all-btn {
            background: #ffffff;
            color: #e11d48;
            border: 1px solid #ffe4e6;
            font-size: 13px;
            font-weight: 600;
            padding: 7px 16px;
            border-radius: 999px;
            text-decoration: none;
            transition: all 0.25s ease;
            box-shadow: 0 2px 8px rgba(225, 29, 72, 0.06);
        }

        .view-all-btn:hover {
            background: #e11d48;
            color: #ffffff;
            border-color: #e11d48;
            text-decoration: none;
        }

        /* Desktop Grid: 6 columns x 2 rows = 12 items, no scroll */
        .category-desktop-grid {
            display: grid;
            grid-template-columns: repeat(6, minmax(0, 1fr));
            gap: 16px;
        }

        .category-mobile-wrapper {
            display: none;
        }

        /* Category Card */
        .category-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: #ffffff;
            border: 1px solid #f7e6e9;
            border-radius: 18px;
            padding: 18px 10px;
            height: 175px;
            text-decoration: none;
            transition: all 0.25s ease;
        }

        .category-card:hover {
            transform: translateY(-3px);
            border-color: #fecdd3;
            box-shadow: 0 8px 18px rgba(225, 29, 72, 0.07);
            text-decoration: none;
        }

        /* Circular Image Background */
        .category-img-circle-wrapper {
            width: 86px;
            height: 86px;
            border-radius: 50%;
            background-color: #fff6f7;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
            padding: 10px;
            transition: transform 0.25s ease;
        }

        .category-img-circle-wrapper img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .category-card:hover .category-img-circle-wrapper {
            transform: scale(1.05);
        }

        /* Category Name */
        .category-name {
            font-size: 13px;
            font-weight: 600;
            color: #1f2937;
            text-align: center;
            line-height: 1.25;
        }

        /* Mobile View: Hide desktop grid, show 3x2 grid per slide */
        @media (max-width: 767.98px) {
            .category-desktop-grid {
                display: none;
            }

            .category-mobile-wrapper {
                display: block;
            }

            .home-category-slider-wrapper {
                padding: 18px 12px 18px;
                border-radius: 20px;
            }

            .home-category-header {
                margin-bottom: 20px !important;
            }

            .home-category-header h3 {
                font-size: 18px;
            }

            .title-bar {
                height: 18px;
            }

            .view-all-btn {
                font-size: 12px;
                padding: 6px 14px;
            }

            /* 3 columns x 2 rows = 6 items per slide */
            .category-mobile-grid {
                display: grid;
                grid-template-columns: repeat(3, minmax(0, 1fr));
                grid-template-rows: repeat(2, auto);
                gap: 10px;
                padding: 2px;
            }

            .category-mobile-grid .category-card {
                height: 128px;
                padding: 10px 6px;
                border-radius: 16px;
            }

            .category-mobile-grid .category-img-circle-wrapper {
                width: 56px;
                height: 56px;
                padding: 6px;
                margin-bottom: 8px;
            }

            .category-mobile-grid .category-name {
                font-size: 11px;
                font-weight: 600;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }

            /* Dots styling */
            .category-carousel-mobile .slick-dots,
            .category-carousel-mobile .u-slick__pagination {
                position: static !important;
                display: flex !important;
                align-items: center;
                justify-content: center;
                gap: 6px;
                margin-top: 14px !important;
                padding: 0;
                list-style: none;
            }

            .category-carousel-mobile .slick-dots li,
            .category-carousel-mobile .u-slick__pagination li {
                width: auto !important;
                height: auto !important;
                margin: 0 !important;
            }

            .category-carousel-mobile .slick-dots li button,
            .category-carousel-mobile .u-slick__pagination li span {
                display: block !important;
                width: 8px !important;
                height: 8px !important;
                padding: 0 !important;
                border: 0 !important;
                border-radius: 999px !important;
                background-color: #d1d5db !important;
                text-indent: -9999px !important;
                overflow: hidden;
                transition: all 0.25s ease;
            }

            .category-carousel-mobile .slick-dots li.slick-active button,
            .category-carousel-mobile .u-slick__pagination li.slick-active span {
                width: 22px !important;
                background-color: #e11d48 !important;
            }

            .category-carousel-mobile {
                overflow: hidden;
            }

            .category-carousel-mobile .slick-list {
                overflow: hidden !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            .category-carousel-mobile .slick-track {
                display: flex !important;
                align-items: stretch;
            }

            .category-carousel-mobile .slick-slide {
                float: none !important;
                height: auto !important;
                box-sizing: border-box;
            }

            .category-carousel-mobile .slick-slide>div {
                width: 100%;
                min-width: 0;
                padding: 2px;
                box-sizing: border-box;
            }

            .category-mobile-grid {
                display: grid;
                grid-template-columns: repeat(3, minmax(0, 1fr));
                grid-template-rows: repeat(2, auto);
                gap: 10px;
                width: 100%;
                min-width: 0;
            }
        }




        /* Mobile responsive coupon copy button */
        .copy-coupon-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 5px 10px;
            font-size: 12px;
            line-height: 1.2;
            border-radius: 6px;
            flex-shrink: 0;
        }

        @media (max-width: 767.98px) {
            .copy-coupon-btn {
                padding: 5px 7px !important;
                margin-left: 6px !important;
                font-size: 12px;
                line-height: 1;
                border-radius: 5px;
            }

            .copy-coupon-btn i {
                font-size: 13px;
            }
        }



        /* Brand Carousel Fix */
        .brand-carousel {
            padding-bottom: 10px;
        }

        /* Hide Brand Carousel Arrows */
        .brand-carousel .slick-arrow,
        .brand-carousel .u-slick__arrow {
            display: none !important;
        }
    </style>
@endpush
@section('content')
    <!-- Banner Section -->
    @if (isset($mainBanners) && $mainBanners->count() > 0)
        <div class="container mb-3">
            <div class="col-xl-12 px-0">
                <div class="banner-carousel-container overflow-hidden position-relative rounded">

                    <div class="js-slick-carousel u-slick" data-autoplay="true" data-speed="7000" data-arrows="true"
                        data-dots="true" data-slides-scroll="1" data-infinite="true"
                        data-arrow-left-classes="ec ec-arrow-left u-slick__arrow u-slick__arrow-media-left font-size-20"
                        data-arrow-right-classes="ec ec-arrow-right u-slick__arrow u-slick__arrow-media-right font-size-20"
                        data-pagi-classes="text-center u-slick__pagination u-slick__pagination--long">

                        @foreach ($mainBanners as $banner)
                            <div class="js-slide">
                                <a href="{{ $banner->url ?? '#' }}">
                                    <img src="{{ asset($banner->image) }}" alt="Banner"
                                        class="img-fluid w-100 banner-image" loading="eager" fetchpriority="high"
                                        decoding="async">
                                </a>
                            </div>
                        @endforeach

                    </div>

                </div>
            </div>
        </div>
    @endif

    <!-- Promotional Sections -->
    <div class="container">
        <!-- Main Offer Cards -->
        <div class="home-main-offers row g-2 g-md-3 justify-content-center">
            {{-- First 3 offers --}}
            @foreach ($offers->take(3) as $promo)
                <div class="col-6 col-md-6 col-xl-3">
                    @if ($promo->type == 'product')
                        <a href="{{ route('offers', $promo->slug) }}"
                            class="promo-card-main d-flex align-items-center p-3 text-decoration-none">
                            <div class="promo-icon-wrapper mr-3">
                                @if ($promo->image)
                                    <img src="{{ asset($promo->image) }}" alt="{{ $promo->type }}"
                                        class="img-fluid rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                @endif
                            </div>
                            <div class="promo-content">
                                <h6 class="mb-1 text-gray-90 font-weight-bold">
                                    {{ $promo->title ?? 'Special Offer' }}
                                </h6>
                                <p class="mb-0 text-gray-50">
                                    {{ $promo->subtitle ?? 'Discover our exclusive deals' }}
                                </p>
                            </div>
                        </a>
                    @else
                        <div class="promo-card-main d-flex align-items-center p-3">
                            <a href="javascript:void(0)" class="d-flex align-items-center text-decoration-none flex-grow-1"
                                style="min-width: 0;">

                                @if ($promo->image)
                                    <div class="promo-icon-wrapper mr-3">
                                        <img src="{{ asset($promo->image) }}" alt="{{ $promo->type }}"
                                            class="img-fluid rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                    </div>
                                @endif

                                <div class="promo-content" style="min-width: 0;">
                                    <h6 class="mb-1 text-gray-90 font-weight-bold">
                                        {{ $promo->title ?? 'Special Offer' }}
                                    </h6>

                                    <p class="mb-0 text-gray-50">
                                        {{ $promo->subtitle ?? 'Discover our exclusive deals' }}
                                    </p>
                                </div>
                            </a>

                            {{-- Copy Coupon Button --}}
                            <button type="button" class="btn btn-light border ml-2 copy-coupon-btn"
                                data-coupon="{{ $promo->coupon->coupon_code ?? '' }}" title="Copy coupon code"
                                aria-label="Copy coupon code" style="flex-shrink: 0;">

                                <i class="far fa-copy"></i>
                            </button>
                        </div>
                    @endif

                </div>
            @endforeach
        </div>

        @if ($offers->count() > 3)
            {{-- Mini Offer Cards: Remaining offers after the first 4 --}}
            <div class="home-mini-offers row g-3 mb-2 justify-content-center">
                @foreach ($offers->skip(3) as $promo)
                    @if ($promo->type == 'product')
                        <div class="col-xl-3 col-md-4 col-6 mt-2">
                            <a href="{{ route('offers', $promo->slug) }}"
                                class="promo-card-mini d-flex flex-column align-items-center text-center p-3 text-decoration-none">
                                @if ($promo->image)
                                    <div class="mini-icon mb-2">
                                        <img src="{{ asset($promo->image) }}" alt="{{ $promo->title ?? 'Gift' }}"
                                            class="img-fluid rounded-circle"
                                            style="width: 40px; height: 40px; object-fit: cover;">
                                    </div>
                                @endif
                                <div class="promo-content">
                                    <h6 class="mb-1 text-gray-90 font-weight-bold">
                                        {{ $promo->title ?? 'Special Offer' }}
                                    </h6>
                                    <p class="mb-0 text-gray-50">
                                        {{ $promo->subtitle ?? 'Discover our exclusive deals' }}
                                    </p>
                                </div>
                            </a>
                        </div>
                    @else
                        <div class="col-xl-3 col-md-4 col-6 mt-2">
                            <div class="promo-card-mini d-flex flex-column align-items-center text-center p-3">

                                <a href="javascript:void(0)"
                                    class="d-flex flex-column align-items-center text-decoration-none w-100">
                                    @if ($promo->image)
                                        <div class="mini-icon mb-2">
                                            <img src="{{ asset($promo->image) }}" alt="{{ $promo->title ?? 'Gift' }}"
                                                class="img-fluid rounded-circle"
                                                style="width: 40px; height: 40px; object-fit: cover;">
                                        </div>
                                    @endif
                                    <div class="promo-content">
                                        <h6 class="mb-1 text-gray-90 font-weight-bold">
                                            {{ $promo->title ?? 'Special Offer' }}
                                        </h6>

                                        <p class="mb-0 text-gray-50">
                                            {{ $promo->subtitle ?? 'Discover our exclusive deals' }}
                                        </p>
                                    </div>
                                </a>

                                {{-- Copy Coupon Button --}}
                                <button type="button" class="btn btn-light border copy-coupon-btn mt-2"
                                    data-coupon="{{ $promo->coupon->coupon_code ?? '' }}" title="Copy coupon code"
                                    aria-label="Copy coupon code">

                                    <i class="far fa-copy"></i>
                                </button>

                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif

    </div>


    <div class="container my-4">
        <div class="home-category-slider-wrapper">
            <!-- Header -->
            <div class="home-category-header d-flex justify-content-between align-items-center mb-3 mb-md-4">
                <h3 class="section-title mb-0">
                    <span class="title-bar"></span> Shop by Category
                </h3>
                <a href="{{ route('products') }}" class="view-all-btn">
                    View all &rarr;
                </a>
            </div>

            <!-- Desktop & Tablet (No Slider: 2 rows x 6 cols = 12 categories) -->
            <div class="category-desktop-grid">
                @foreach ($categories->take(12) as $category)
                    <a href="{{ route('category', $category->slug) }}" class="category-card">
                        <div class="category-img-circle-wrapper">
                            @if ($category->logo)
                                <img src="{{ asset($category->logo) }}" alt="{{ $category->name }}" loading="lazy">
                            @else
                                <img src="{{ asset('frontend/assets/img/placeholder-category.png') }}"
                                    alt="{{ $category->name }}" loading="lazy">
                            @endif
                        </div>
                        <span class="category-name">{{ $category->name }}</span>
                    </a>
                @endforeach
            </div>

            <!-- Mobile Only (2 Screens x 6 Categories = 3 cols x 2 rows per screen) -->
            <div class="category-mobile-wrapper">
                <div class="js-slick-carousel u-slick category-carousel-mobile" data-slides-show="1"
                    data-slides-scroll="1" data-arrows="false" data-dots="true" data-autoplay="false"
                    data-infinite="false">
                    @foreach ($categories->take(12)->chunk(6) as $chunk)
                        <div class="js-slide">
                            <div class="category-mobile-grid">
                                @foreach ($chunk as $category)
                                    <a href="{{ route('category', $category->slug) }}" class="category-card">
                                        <div class="category-img-circle-wrapper">
                                            @if ($category->logo)
                                                <img src="{{ asset($category->logo) }}" alt="{{ $category->name }}"
                                                    loading="lazy">
                                            @else
                                                <img src="{{ asset('frontend/assets/img/placeholder-category.png') }}"
                                                    alt="{{ $category->name }}" loading="lazy">
                                            @endif
                                        </div>
                                        <span class="category-name">{{ $category->name }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>


    <div class="container">
        <!-- Full banner -->
        @if ($topBanner)
            <div class="mb-4">
                <a href="{{ $topBanner->url }}" class="d-block text-gray-90">
                    <img src="{{ asset($topBanner->image) }}" alt="Banner" class="img-fluid w-100 banner-image"
                        loading="eager" fetchpriority="high" decoding="async">
                </a>
            </div>
        @endif
        <!-- End Full banner -->

        <!-- End Banner -->
        <!-- Todays Deal products -->
        @if ($bestProducts->count() > 0)
            <div class="mb-1 home-category-slider">

                {{-- Section Header --}}
                <div
                    class="home-category-header d-flex justify-content-between border-bottom border-color-1 flex-lg-nowrap flex-wrap border-md-down-top-0 border-md-down-bottom-0">
                    <h3 class="section-title section-title__full mb-0 font-size-22">Best Selling</h3>
                </div>

                {{-- Product Slider --}}
                <div class="js-slick-carousel u-slick overflow-hidden u-slick-overflow-visble px-1"
                    data-pagi-classes="text-center right-0 bottom-1 left-0 u-slick__pagination u-slick__pagination--long mb-0 z-index-n1 mt-4"
                    data-slides-show="7" data-slides-scroll="1" data-arrows="true" data-dots="false"
                    data-autoplay="true" data-speed="600" data-infinite="true"
                    data-responsive='[
                { "breakpoint": 1400, "settings": { "slidesToShow": 5 } },
                { "breakpoint": 1200, "settings": { "slidesToShow": 4 } },
                { "breakpoint": 992, "settings": { "slidesToShow": 3 } },
                { "breakpoint": 768, "settings": { "slidesToShow": 2 } },
                { "breakpoint": 554, "settings": { "slidesToShow": 2 } }
            ]'>

                    @foreach ($bestProducts as $item)
                        @php
                            $product = $item->product ?? $item; // Product object safety check
                        @endphp

                        @if ($product)
                            <div class="js-slide px-1">
                                <div class="product-item remove-divider">
                                    <div class="product-item__outer">
                                        <div class="product-item__inner p-2 p-md-3 border rounded bg-light">
                                            <div class="product-card-custom">

                                                {{-- Product Image --}}
                                                <div class="product-image-box rounded">
                                                    <a href="{{ route('product', $product->slug) }}">
                                                        <img class="img-fluid" src="{{ asset($product->image) }}"
                                                            alt="{{ $product->name }}" loading="lazy">
                                                    </a>
                                                </div>

                                                {{-- Product Name --}}
                                                <h5 class="product-title-custom">
                                                    <a href="{{ route('product', $product->slug) }}"
                                                        class="text-primary font-weight-bold">
                                                        {{ $product->name }}
                                                    </a>
                                                </h5>

                                                {{-- Product Price --}}
                                                <div class="product-price-custom text-black">
                                                    ৳ {{ productPrice($product->id) }}
                                                </div>

                                                {{-- Add to Cart Button --}}
                                                <div class="product-cart-custom">
                                                    <button type="button"
                                                        class="btn btn-primary btn-sm text-white w-100 border-primary rounded transition-3d-hover py-2 ajax-add-to-cart"
                                                        data-product-id="{{ $product->id }}">
                                                        <i class="ec ec-add-to-cart"></i> Add to Cart
                                                    </button>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach

                </div>
            </div>
        @endif
        <!-- End Todays Deal products -->

        <!-- Todays Deal banner -->
        @if ($middleBanner)
            <div class="mb-4">
                <a href="{{ $middleBanner->url }}" class="d-block text-gray-90">
                    <img src="{{ asset($middleBanner->image) }}" alt="Banner" class="img-fluid w-100 banner-image"
                        loading="eager" fetchpriority="high" decoding="async">
                </a>
            </div>
        @endif
        <!-- End Todays Deal banner -->
    </div>

    <div class="container">
        <!-- Selected Category Products -->
        @foreach ($selectedCategories as $selectedCategory)
            @php
                $category = $selectedCategory->category;
                $products = $category?->activeProducts ?? collect();
            @endphp

            @if ($category && $products->isNotEmpty())
                <div class="mb-1 home-category-slider">
                    <div
                        class="home-category-header d-flex justify-content-between border-bottom border-color-1 flex-lg-nowrap flex-wrap border-md-down-top-0 border-md-down-bottom-0">
                        <h3 class="section-title section-title__full mb-0 font-size-22">
                            {{ $category->name }}
                        </h3>

                        @if (!empty($category->slug))
                            <a class="d-block text-gray-16" href="{{ route('category', $category->slug) }}">
                                View All
                                <i class="ec ec-arrow-right-categproes"></i>
                            </a>
                        @endif
                    </div>

                    <div class="js-slick-carousel u-slick overflow-hidden u-slick-overflow-visble px-1"
                        data-pagi-classes="text-center right-0 bottom-1 left-0 u-slick__pagination u-slick__pagination--long mb-0 z-index-n1 mt-4"
                        data-slides-show="7" data-slides-scroll="1" data-arrows="true" data-dots="false"
                        data-autoplay="true" data-speed="600" data-infinite="true"
                        data-responsive='[{
                    "breakpoint": 1400,
                    "settings": {"slidesToShow": 5}
                }, {
                    "breakpoint": 1200,
                    "settings": {"slidesToShow": 3}
                }, {
                    "breakpoint": 992,
                    "settings": {"slidesToShow": 3}
                }, {
                    "breakpoint": 768,
                    "settings": {"slidesToShow": 2}
                }, {
                    "breakpoint": 554,
                    "settings": {"slidesToShow": 2}
                }]'>
                        @foreach ($products as $product)
                            <div class="js-slide px-1">
                                <div class="product-item remove-divider">
                                    <div class="product-item__outer">
                                        <div class="product-item__inner p-2 p-md-3 border rounded bg-light">
                                            <div class="product-card-custom">

                                                {{-- Product Image --}}
                                                <div class="product-image-box rounded">
                                                    <a
                                                        href="{{ !empty($product->slug) ? route('product', $product->slug) : '#' }}">
                                                        <img class="img-fluid"
                                                            src="{{ !empty($product->image) ? asset($product->image) : asset('images/default.png') }}"
                                                            alt="{{ $product->name }}" loading="lazy">
                                                    </a>
                                                </div>

                                                {{-- Product Name --}}
                                                <h5 class="product-title-custom">
                                                    <a href="{{ !empty($product->slug) ? route('product', $product->slug) : '#' }}"
                                                        class="text-primary font-weight-bold">
                                                        {{ $product->name }}
                                                    </a>
                                                </h5>

                                                {{-- Product Price --}}
                                                <div class="product-price-custom text-black">
                                                    ৳
                                                    {{ function_exists('productPrice') ? productPrice($product->id) : $product->price ?? 0 }}
                                                </div>

                                                {{-- Add to Cart Button --}}
                                                <div class="product-cart-custom">
                                                    <button type="button"
                                                        class="btn btn-primary btn-sm text-white w-100 border-primary rounded transition-3d-hover py-2 ajax-add-to-cart"
                                                        data-product-id="{{ $product->id }}">
                                                        <i class="ec ec-add-to-cart"></i> Add to Cart
                                                    </button>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
            @endif
        @endforeach
        <!-- End Selected Category Products -->
    </div>


    <div class="container mb-4">
        @if ($latestProducts->count() > 0)
            <div class="mb-1 home-category-slider">

                {{-- Section Header --}}
                <div
                    class="home-category-header d-flex justify-content-between border-bottom border-color-1 flex-lg-nowrap flex-wrap border-md-down-top-0 border-md-down-bottom-0">
                    <h3 class="section-title section-title__full mb-0 pb-2 font-size-22">New Products</h3>
                    <a class="d-block text-gray-16" href="{{ route('products') }}">View
                        <i class="ec ec-arrow-right-categproes"></i></a>
                </div>

                {{-- Product Slider --}}
                <div class="js-slick-carousel u-slick overflow-hidden u-slick-overflow-visble px-1"
                    data-pagi-classes="text-center right-0 bottom-1 left-0 u-slick__pagination u-slick__pagination--long mb-0 z-index-n1 mt-4"
                    data-slides-show="7" data-slides-scroll="1" data-arrows="true" data-dots="false"
                    data-autoplay="true" data-speed="600" data-infinite="true"
                    data-responsive='[
                { "breakpoint": 1400, "settings": { "slidesToShow": 5 } },
                { "breakpoint": 1200, "settings": { "slidesToShow": 4 } },
                { "breakpoint": 992, "settings": { "slidesToShow": 3 } },
                { "breakpoint": 768, "settings": { "slidesToShow": 2 } },
                { "breakpoint": 554, "settings": { "slidesToShow": 2 } }
            ]'>

                    @foreach ($latestProducts as $product)
                        @if ($product)
                            <div class="js-slide px-1">
                                <div class="product-item remove-divider">
                                    <div class="product-item__outer">
                                        <div class="product-item__inner p-2 p-md-3 border rounded bg-light">
                                            <div class="product-card-custom">

                                                {{-- Product Image --}}
                                                <div class="product-image-box rounded">
                                                    <a href="{{ route('product', $product->slug) }}">
                                                        <img class="img-fluid" src="{{ asset($product->image) }}"
                                                            alt="{{ $product->name }}" loading="lazy">
                                                    </a>
                                                </div>

                                                {{-- Product Name --}}
                                                <h5 class="product-title-custom">
                                                    <a href="{{ route('product', $product->slug) }}"
                                                        class="text-primary font-weight-bold">
                                                        {{ $product->name }}
                                                    </a>
                                                </h5>

                                                {{-- Product Price --}}
                                                <div class="product-price-custom text-black">
                                                    ৳ {{ productPrice($product->id) }}
                                                </div>

                                                {{-- Add to Cart Button --}}
                                                <div class="product-cart-custom">
                                                    <button type="button"
                                                        class="btn btn-primary btn-sm text-white w-100 border-primary rounded transition-3d-hover py-2 ajax-add-to-cart"
                                                        data-product-id="{{ $product->id }}">
                                                        <i class="ec ec-add-to-cart"></i> Add to Cart
                                                    </button>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach

                </div>
            </div>
        @endif
    </div>


    <div class="container mt-3">

        <!-- Brand Carousel -->
        <div class="mb-3">
            <!-- Carousel Wrapper -->
            <div class="js-slick-carousel u-slick my-1 brand-carousel" data-slides-show="5" data-slides-scroll="1"
                data-arrows="false" data-dots="false" data-autoplay="true" data-speed="600" data-infinite="true"
                data-responsive='[{
                    "breakpoint": 992,
                    "settings": {
                        "slidesToShow": 2
                    }
                }, {
                    "breakpoint": 768,
                    "settings": {
                        "slidesToShow": 2
                    }
                }, {
                    "breakpoint": 554,
                    "settings": {
                        "slidesToShow": 2
                    }
                }]'>
                @foreach ($brands as $brand)
                    <div class="js-slide px-2">
                        <!-- Brand Card Box with Border & Padding -->
                        <div class="border rounded bg-white d-flex justify-content-center align-items-center"
                            style="height: 95px;">
                            <a href="{{ route('brand', $brand->slug) }}"
                                class="d-flex justify-content-center align-items-center w-100 h-100">
                                <img class="img-fluid m-auto" style="max-height: 60px; object-fit: contain;"
                                    src="{{ $brand->logo }}" alt="Brand Image">
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <!-- End Brand Carousel -->

        <!-- Recently viewed -->
        @if ($recentProducts->count())
            <div class="mb-6 home-category-slider">
                <div
                    class="home-category-header d-flex justify-content-between border-bottom border-color-1 flex-lg-nowrap flex-wrap border-md-down-top-0 border-md-down-bottom-0">
                    <h3 class="section-title section-title__full mb-0 pb-2 font-size-22">সম্প্রতি দেখা পণ্যসমূহ</h3>
                </div>
                <div class="js-slick-carousel u-slick overflow-hidden u-slick-overflow-visble px-1"
                    data-pagi-classes="text-center right-0 bottom-1 left-0 u-slick__pagination u-slick__pagination--long mb-0 z-index-n1 mt-4"
                    data-slides-show="7" data-slides-scroll="1" data-arrows="true" data-dots="false"
                    data-autoplay="true" data-speed="600" data-infinite="true"
                    data-responsive='[{
                          "breakpoint": 1400,
                          "settings": {
                            "slidesToShow": 5
                          }
                        }, {
                            "breakpoint": 1200,
                            "settings": {
                              "slidesToShow": 3
                            }
                        }, {
                          "breakpoint": 992,
                          "settings": {
                            "slidesToShow": 3
                          }
                        }, {
                          "breakpoint": 768,
                          "settings": {
                            "slidesToShow": 2
                          }
                        }, {
                          "breakpoint": 554,
                          "settings": {
                            "slidesToShow": 2
                          }
                        }]'>

                    @foreach ($recentProducts as $product)
                        <div class="js-slide px-1">
                            <div class="product-item remove-divider">
                                <div class="product-item__outer">
                                    <div class="product-item__inner p-2 p-md-3 border rounded bg-light">
                                        <div class="product-card-custom">

                                            {{-- Product Image --}}
                                            <div class="product-image-box rounded">
                                                <a href="{{ route('product', $product->slug) }}">
                                                    <img class="img-fluid" src="{{ asset($product->image) }}"
                                                        alt="{{ $product->name }}" loading="lazy">
                                                </a>
                                            </div>

                                            {{-- Product Name --}}
                                            <h5 class="product-title-custom">
                                                <a href="{{ route('product', $product->slug) }}"
                                                    class="text-primary font-weight-bold">
                                                    {{ $product->name }}
                                                </a>
                                            </h5>

                                            {{-- Product Price --}}
                                            <div class="product-price-custom text-black">
                                                ৳ {{ productPrice($product->id) }}
                                            </div>

                                            {{-- Add to Cart Button --}}
                                            <div class="product-cart-custom">
                                                <button type="button"
                                                    class="btn btn-primary btn-sm text-white w-100 border-primary rounded transition-3d-hover py-2 ajax-add-to-cart"
                                                    data-product-id="{{ $product->id }}">
                                                    <i class="ec ec-add-to-cart"></i> Add to Cart
                                                </button>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        @endif
        <!-- End Recently viewed -->
    </div>

    <!-- Bottom banner -->
    <div class="container">
        @if ($bottomBanner)
            <div class="mb-4">
                <a href="{{ $bottomBanner->url }}" class="d-block text-gray-90">
                    <img src="{{ asset($bottomBanner->image) }}" alt="Banner" class="img-fluid w-100 banner-image"
                        loading="eager" fetchpriority="high" decoding="async">
                </a>
            </div>
        @endif
    </div>
    <!-- End Bottom banner -->
@endsection

@push('footer_script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.copy-coupon-btn').forEach(function(button) {
                button.addEventListener('click', async function() {
                    const couponCode = this.dataset.coupon;

                    if (!couponCode) {
                        showCouponToast('Coupon code is not available!', 'error');
                        return;
                    }

                    try {
                        await navigator.clipboard.writeText(couponCode);

                        showCouponToast('Coupon code copied successfully!', 'success');
                    } catch (error) {
                        // Fallback for browsers where Clipboard API is unavailable.
                        const textarea = document.createElement('textarea');
                        textarea.value = couponCode;
                        textarea.style.position = 'fixed';
                        textarea.style.opacity = '0';

                        document.body.appendChild(textarea);
                        textarea.select();

                        const copied = document.execCommand('copy');
                        textarea.remove();

                        if (copied) {
                            showCouponToast('Coupon code copied successfully!', 'success');
                        } else {
                            showCouponToast('Unable to copy coupon code.', 'error');
                        }
                    }
                });
            });

            function showCouponToast(message, type) {
                const toast = document.createElement('div');

                toast.textContent = message;
                toast.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 99999;
                padding: 12px 20px;
                border-radius: 8px;
                color: #fff;
                background: ${type === 'success' ? '#198754' : '#dc3545'};
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                font-size: 14px;
                max-width: calc(100vw - 40px);
            `;

                document.body.appendChild(toast);

                setTimeout(function() {
                    toast.remove();
                }, 3000);
            }
        });
    </script>
@endpush
