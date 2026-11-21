@extends('backend.layouts.app')
@section('title', 'App Setting')
@push('style')
    <link rel="stylesheet" href="{{ asset('assets') }}/js/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/css/selectize.default.min.css"
        integrity="sha512-pTaEn+6gF1IeWv3W1+7X7eM60TFu/agjgoHmYhAfLEU8Phuf6JKiiE8YmsNC0aCgQv4192s4Vai8YZ6VNM6vyQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        #main-container .content {
            padding-top: 0 !important
        }
    </style>
@endpush
@section('content')
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-lg-6 m-auto" id="addCategoryField" style="display:none;">
                <div class="block block-rounded">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">
                            Add New Data
                        </h3>
                        <button id="closeCategoryForm" class="btn btn-sm btn-danger">
                            <i class="fas fa-x"></i>
                        </button>
                    </div>
                    <div class="block-content block-content-full overflow-x-auto">
                        <form action="{{ route('admin.navMenu.category.store') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="mb-4">
                                <label class="form-label" for="type">Select Type</label>
                                <select name="type" class="form-control" id="type">
                                    <option value="category" {{ old('type', 'category') == 'category' ? 'selected' : '' }}>
                                        Category</option>
                                    <option value="subcategory" {{ old('type') == 'subcategory' ? 'selected' : '' }}>
                                        Subcategory</option>
                                </select>
                                @error('type')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="category">Select Category</label>
                                <select class="form-control" id="category" name="category" required>
                                    <option value="" disabled selected>Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="mb-4" id="subcategoryField" style="display: none;">
                                <label class="form-label" for="subcategory">Select Subcategory</label>
                                <select class="form-control" id="subcategory" name="subcategory">
                                    <option value="" disabled selected>Select Subcategory</option>
                                    @foreach ($subcategories as $subcategory)
                                        <option value="{{ $subcategory->id }}"
                                            {{ old('subcategory') == $subcategory->id ? 'selected' : '' }}>
                                            {{ $subcategory->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('subcategory')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="mb-0 text-end">
                                <button type="submit" class="btn btn-primary mt-4">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <form id="productForm" action="{{ route('admin.settings.app.core.update') }}" method="post">
                @csrf
                <div class="col-lg-10 m-auto mt-2">
                    <div class="block block-rounded">
                        @include('backend.navmenu.partials.tab_header')
                        <div class="block-header block-header-default">
                            <h3 class="block-title">
                                Data List
                            </h3>
                            <button id="addCategory" class="btn btn-sm btn-primary" type="button"><i
                                    class="fas fa-plus"></i> Add Data</button>
                        </div>
                        <div class="block-content block-content-full overflow-x-auto">
                            <table class="table table-bordered table-striped table-vcenter">
                                <thead>
                                    <tr>
                                        <th class="text-center">SL</th>
                                        <th>Type</th>
                                        <th>Name</th>
                                        <th>Priority</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($navData as $key => $data)
                                        <tr>
                                            <td class="text-center fs-sm">{{ $key + 1 }}</td>
                                            <td class="fw-semibold fs-sm">{{ $data->Type }}</td>
                                            @if ($data->Type == 'category')
                                                <td class="fw-semibold fs-sm">{{ $data->category->name }}</td>
                                            @else
                                                <td class="fw-semibold fs-sm">{{ $data->subcategory->name }}</td>
                                            @endif
                                            <td class="fw-semibold fs-sm">
                                                {{ $data->priority }}
                                            </td>
                                            <td class="fw-semibold fs-sm">
                                                <div class="form-check form-switch text-center">
                                                    <input class="form-check-input" id="status" type="checkbox"
                                                        {{ $data->status == '1' ? 'checked' : '' }} name="status"
                                                        data-id="{{ $data->id }}" data-status="{{ $data->status }}"
                                                        onchange="updateCategoryStatus(this)">
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="border-0 btn btn-sm editCategoryBtn"
                                                    data-id="{{ $data->id }}" data-name="{{ $data->name }}"
                                                    data-slug="{{ $data->slug }}" data-priority="{{ $data->priority }}"
                                                    data-logo="{{ asset($data->logo) }}">
                                                    <i class="fa fa-pencil text-secondary fa-xl"></i>
                                                </button>
                                                <button type="button" class="border-0 btn btn-sm"
                                                    onclick="deleteCategory(this)" data-id="{{ $data->id }}"><i
                                                        class="fa fa-trash text-danger fa-xl"></i></button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">No Data Found</td>
                                        </tr>
                                    @endforelse

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
@push('footer_scripts')
    <!-- DataTables  & Plugins -->
    <script src="{{ asset('assets') }}/js/plugins/datatables/dataTables.min.js"></script>
    <script src="{{ asset('assets') }}/js/plugins/datatables-bs5/js/dataTables.bootstrap5.min.js"></script>
    <script src="{{ asset('assets') }}/js/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="{{ asset('assets') }}/js/plugins/datatables-responsive-bs5/js/responsive.bootstrap5.min.js"></script>
    <script src="{{ asset('assets') }}/js/plugins/datatables-buttons/dataTables.buttons.min.js"></script>
    <script src="{{ asset('assets') }}/js/plugins/datatables-buttons-bs5/js/buttons.bootstrap5.min.js"></script>
    <script src="{{ asset('assets') }}/js/plugins/datatables-buttons-jszip/jszip.min.js"></script>
    <script src="{{ asset('assets') }}/js/plugins/datatables-buttons-pdfmake/pdfmake.min.js"></script>
    <script src="{{ asset('assets') }}/js/plugins/datatables-buttons-pdfmake/vfs_fonts.js"></script>
    <script src="{{ asset('assets') }}/js/plugins/datatables-buttons/buttons.print.min.js"></script>
    <script src="{{ asset('assets') }}/js/plugins/datatables-buttons/buttons.html5.min.js"></script>
    <script src="{{ asset('assets') }}/js/pages/be_tables_datatables.min.js"></script>

    <script>
        function toggleSubcategoryField() {
            const type = $('#type').val();
            const $subWrapper = $('#subcategoryField');
            const $subSelect = $('#subcategory');

            if (type === 'subcategory') {
                $subWrapper.slideDown(200);
                $subSelect.prop('required', true);

                const categoryId = $('#category').val();
                if (categoryId) {
                    loadSubcategoriesByCategory(categoryId, '#subcategory', "{{ old('subcategory') }}");
                }
            } else {
                $subWrapper.slideUp(200);
                $subSelect.prop('required', false).val('');
            }
        }

        $(document).ready(function() {
            // Initial state on page load (handles default 'category' and validation old('type'))
            toggleSubcategoryField();

            // Toggle on type change
            $('#type').on('change', function() {
                toggleSubcategoryField();
            });
        });
    </script>

    <script>
        $('#categoryTable').DataTable();

        // Reusable function: Category ID diye Subcategory dropdown load kora
        function loadSubcategoriesByCategory(categoryId, targetSelectId, selectedSubcategoryId = null) {
            const $select = $(targetSelectId);
            $select.html('<option value="" disabled selected>Loading...</option>');

            if (!categoryId) {
                $select.html('<option value="" disabled selected>Select Subcategory</option>');
                return;
            }

            let url = "{{ route('admin.subsubcategory.get.subcategories', ':id') }}".replace(':id', categoryId);

            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function(subcategories) {
                    $select.html('<option value="" disabled selected>Select Subcategory</option>');

                    if (subcategories.length > 0) {
                        $.each(subcategories, function(key, sub) {
                            let isSelected = (selectedSubcategoryId && Number(selectedSubcategoryId) ===
                                    Number(sub.id)) ?
                                'selected' :
                                '';
                            $select.append(
                                `<option value="${sub.id}" ${isSelected}>${sub.name}</option>`
                            );
                        });
                    } else {
                        $select.html('<option value="" disabled selected>No subcategory found</option>');
                    }
                },
                error: function() {
                    $select.html('<option value="" disabled selected>Failed to load subcategories</option>');
                    showToast('Could not load subcategories', 'error');
                }
            });
        }

        // Add Form: Category change korle Subcategory load hobe
        $('#category').on('change', function() {
            loadSubcategoriesByCategory($(this).val(), '#subcategory');
        });
    </script>

    <!-- Page specific script -->
    <script>
        function deleteCategory(button) {
            const id = $(button).data('id');
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    let url = "{{ route('admin.navMenu.category.destroy', ':id') }}";
                    url = url.replace(':id', id);
                    let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': token
                        },
                        success: function(data) {
                            if (data.success) {
                                showToast(data.message, "success");
                                $(button).closest('tr').remove();
                            } else {
                                showToast(data.message, "error");
                            }
                        },
                        error: function(xhr) {
                            showToast("An error occurred: " + xhr.responseJSON.message, "error");
                        }
                    });
                }
            });
        }

        function updateCategoryStatus(element) {
            Swal.fire({
                title: "Are you sure?",
                text: "Will update category status",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, update it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    updateCategoryStatusAjax(element);
                } else {
                    element.checked = !element.checked;
                }
            })
        }

        function updateCategoryStatusAjax(element) {
            const id = $(element).data('id');
            let url = "{{ route('admin.navMenu.category.status.update', ':id') }}";
            url = url.replace(':id', id);

            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    if (data.success) {
                        showToast(data.message, "success");
                    } else {
                        showToast(data.message, "error");
                    }
                },
                error: function(xhr, status, error) {
                    console.log('xhr.responseText, status, error', xhr.responseText, status, error);
                    showToast('Something went wrong', "error");
                }
            });
        }
    </script>



    <script>
        // Add button → show form and hide button
        $('#addCategory').click(function() {
            $('#addCategoryField').slideDown(300);
            $(this).hide();
        });

        // X button → hide form and show button
        $('#closeCategoryForm').click(function() {
            $('#addCategoryField').slideUp(300);
            $('#addCategory').show();
        });
    </script>
    @if ($errors->any())
        <script>
            $(document).ready(function() {
                $('#addCategoryField').show();
                $('#addCategory').hide();
            });
        </script>
    @endif
@endpush
