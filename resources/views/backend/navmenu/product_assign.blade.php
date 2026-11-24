@extends('backend.layouts.app')
@section('title', 'Product Assignment')

@push('style')
    <link rel="stylesheet" href="{{ asset('assets') }}/js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css">
    <link rel="stylesheet"
        href="{{ asset('assets') }}/js/plugins/datatables-responsive-bs5/css/responsive.bootstrap5.min.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/js/plugins/select2/css/select2.min.css">
    <style>
        .product-checkbox-list {
            max-height: 360px;
            overflow-y: auto;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row d-flex justify-content-center">
            <!-- Left: Product Assignment Form -->
            <div class="col-lg-4">
                <div class="block block-rounded">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Product Assignment</h3>
                    </div>
                    <div class="block-content block-content-full overflow-x-auto">
                        <form action="{{ route('admin.navMenu.product.store', $navmenu->id) }}" method="POST">
                            @csrf

                            <div class="mb-4 text-center">
                                <h3 class="mb-0">{{ $navmenu->name ?? 'Menu' }}</h3>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="filter_category">Select Category</label>
                                <select name="category_id" id="filter_category" class="form-select w-100">
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3" id="subcategoryWrapper">
                                <label class="form-label" for="filter_subcategory">Select Subcategory</label>
                                <select name="subcategory_id" id="filter_subcategory" class="form-select w-100">
                                    <option value="">Select Subcategory</option>
                                </select>
                            </div>

                            {{-- Filtered Product Checkboxes --}}
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label class="form-label mb-0">Select Product</label>
                                    <small class="text-muted" id="productCountText">
                                        {{ $products->count() }} products
                                    </small>
                                </div>

                                <div id="productCheckboxContainer"
                                    class="product-checkbox-list border rounded p-2 bg-light">
                                    @include('backend.navmenu.partials.product_checkboxes', [
                                        'products' => $products,
                                        'assignedProductIds' => $assignedProductIds,
                                    ])
                                </div>

                                @error('products')
                                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                                @enderror
                                @error('products.*')
                                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="mb-0 d-flex justify-content-between">
                                <button type="button" id="resetFilterBtn" class="btn btn-info mt-3">
                                    <i class="fas fa-rotate-left"></i> Reset
                                </button>
                                <button type="submit" class="btn btn-primary mt-3">Save Products</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right: Assigned Product List -->
            <div class="col-lg-8">
                <div class="block block-rounded">
                    <div class="block-header block-header-default">
                         <h3 class="block-title">Assigned Product List</h3>
                        <a href="{{route('admin.navMenu.navItem')}}" class="btn btn-sm btn-primary">
                            <i class="fas fa-arrow-left"></i> Back to NavMenu
                        </a>
                    </div>
                    <div class="block-content block-content-full overflow-x-auto">
                        <table class="table table-bordered table-striped table-vcenter" id="productsTable">
                            <thead>
                                <tr>
                                    <th class="text-center">SL</th>
                                    <th>Product</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($navProducts as $key => $navProduct)
                                    <tr>
                                        <td class="text-center fs-sm">{{ $key + 1 }}</td>
                                        <td class="fw-semibold fs-sm">{{ $navProduct->product?->name ?? 'N/A' }}</td>
                                        <td class="text-center fs-xl">
                                            <button type="button" class="border-0 btn btn-sm"
                                                onclick="deleteAssignedProduct(this)" data-id="{{ $navProduct->id }}">
                                                <i class="fa fa-trash text-danger fa-xl"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('footer_scripts')
    <script src="{{ asset('assets') }}/js/plugins/datatables/dataTables.min.js"></script>
    <script src="{{ asset('assets') }}/js/plugins/datatables-bs5/js/dataTables.bootstrap5.min.js"></script>
    <script src="{{ asset('assets') }}/js/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="{{ asset('assets') }}/js/plugins/datatables-responsive-bs5/js/responsive.bootstrap5.min.js"></script>
    <script src="{{ asset('assets') }}/js/plugins/select2/js/select2.full.min.js"></script>

    <script>
        $('#productsTable').DataTable();

        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        const filterUrl = "{{ route('admin.navMenu.product.filter', $navmenu->id) }}";

        // Category ও Subcategory উভয়কেই full width দিন
        $('#filter_category, #filter_subcategory').select2({
            width: '100%'
        });

        function loadProducts(categoryId, subcategoryId = '') {
            $('#productCheckboxContainer').html(
                '<div class="text-center text-muted py-3">Loading products...</div>'
            );

            $.ajax({
                url: filterUrl,
                type: 'GET',
                dataType: 'json',
                data: {
                    category_id: categoryId,
                    subcategory_id: subcategoryId
                },
                success: function(res) {
                    $('#productCheckboxContainer').html(res.html);

                    if (typeof res.count !== 'undefined') {
                        $('#productCountText').text(res.count + ' products');
                    }
                },
                error: function(xhr) {
                    console.error('Filter AJAX Error:', xhr.status, xhr.responseText);
                    const errMsg = xhr.responseJSON?.message || 'Failed to load products.';
                    $('#productCheckboxContainer').html(
                        `<div class="text-center text-danger py-3">${errMsg}</div>`
                    );
                }
            });
        }

        function loadSubcategories(categoryId) {
            const $subSelect = $('#filter_subcategory');

            // শুরুতে অপশন খালি করে Loading দেখান
            $subSelect.empty()
                .append('<option value="">Loading...</option>')
                .prop('disabled', true)
                .trigger('change.select2');

            if (!categoryId) {
                $subSelect.empty()
                    .append('<option value="">Select Subcategory</option>')
                    .prop('disabled', false)
                    .trigger('change.select2');
                return;
            }

            $.ajax({
                url: filterUrl,
                type: 'GET',
                dataType: 'json',
                data: {
                    category_id: categoryId,
                    subcategory_id: ''
                },
                success: function(res) {
                    $subSelect.empty().append('<option value="">Select Subcategory</option>');

                    if (Array.isArray(res.subcategories) && res.subcategories.length > 0) {
                        res.subcategories.forEach(function(sub) {
                            $subSelect.append(
                                $('<option>', {
                                    value: sub.id,
                                    text: sub.name
                                })
                            );
                        });
                    }

                    $subSelect.prop('disabled', false).trigger('change.select2');
                },
                error: function() {
                    $subSelect.empty()
                        .append('<option value="">Select Subcategory</option>')
                        .prop('disabled', false)
                        .trigger('change.select2');
                }
            });
        }

        // Category change -> loading দেখাবে, subcategory আনবে এবং category-র product লোড করবে
        $('#filter_category').on('change', function() {
            const categoryId = $(this).val();
            loadSubcategories(categoryId);
            loadProducts(categoryId, '');
        });

        // Subcategory change -> শুধু ওই subcategory-র product লোড করবে (selected মান ঠিক থাকবে)
        $('#filter_subcategory').on('change', function() {
            const categoryId = $('#filter_category').val();
            const subcategoryId = $(this).val();
            loadProducts(categoryId, subcategoryId);
        });

        function deleteAssignedProduct(button) {
            const id = $(button).data('id');

            Swal.fire({
                title: "Are you sure?",
                text: "This product will be removed from the menu!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, remove it!"
            }).then((result) => {
                if (!result.isConfirmed) return;

                let url = "{{ route('admin.navMenu.product.destroy', ':id') }}".replace(':id', id);

                $.ajax({
                    url: url,
                    type: 'DELETE',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(data) {
                        if (data.success) {
                            showToast(data.message, "success");
                            $(button).closest('tr').remove();
                        } else {
                            showToast(data.message || 'Could not remove product', "error");
                        }
                    },
                    error: function(xhr) {
                        let msg = xhr.responseJSON?.message || 'Something went wrong';
                        showToast(msg, "error");
                    }
                });
            });
        }

        $('#resetFilterBtn').on('click', function() {
            $('#filter_category').val('').trigger('change.select2');
            $('#filter_subcategory')
                .empty()
                .append('<option value="">Select Subcategory</option>')
                .val('')
                .prop('disabled', false)
                .trigger('change.select2');
            loadProducts('', '');
        });
    </script>
@endpush
