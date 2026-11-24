@extends('backend.layouts.app')
@section('title', 'Nav Menu Settings')

@push('style')
    <link rel="stylesheet" href="{{ asset('assets') }}/js/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/css/selectize.default.min.css"
        integrity="sha512-pTaEn+6gF1IeWv3W1+7X7eM60TFu/agjgoHmYhAfLEU8Phuf6JKiiE8YmsNC0aCgQv4192s4Vai8YZ6VNM6vyQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        #main-container .content {
            padding-top: 0 !important;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid mt-4">
        <div class="row">
            {{-- Add New Data Form --}}
            <div class="col-lg-6 m-auto" id="addCategoryField" style="display:none;">
                <div class="block block-rounded">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Add New Data</h3>
                        <button type="button" id="closeCategoryForm" class="btn btn-sm btn-danger">
                            <i class="fas fa-x"></i>
                        </button>
                    </div>
                    <div class="block-content block-content-full overflow-x-auto">
                        <form action="{{ route('admin.navMenu.category.store') }}" method="POST">
                            @csrf

                            <div class="mb-4">
                                <label class="form-label" for="type">Select Type</label>
                                <select name="type" class="form-control" id="type">
                                    <option value="category" {{ old('type', 'category') == 'category' ? 'selected' : '' }}>
                                        Category
                                    </option>
                                    <option value="subcategory" {{ old('type') == 'subcategory' ? 'selected' : '' }}>
                                        Subcategory
                                    </option>
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
                                            {{ old('category') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
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

            {{-- Data List Table --}}
            <div class="col-lg-10 m-auto mt-2">
                <div class="block block-rounded">
                    @include('backend.navmenu.partials.tab_header')
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Data List</h3>
                        <button id="addCategory" class="btn btn-sm btn-primary" type="button">
                            <i class="fas fa-plus"></i> Add Data
                        </button>
                    </div>
                    <div class="block-content block-content-full overflow-x-auto">
                        <table class="table table-bordered table-striped table-vcenter" id="navMenuTable">
                            <thead>
                                <tr>
                                    <th class="text-center">SL</th>
                                    <th>Type</th>
                                    <th>Name</th>
                                    <th>Priority</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($navData as $key => $data)
                                    <tr>
                                        <td class="text-center fs-sm">{{ $key + 1 }}</td>
                                        <td class="fw-semibold fs-sm text-capitalize">{{ $data->type }}</td>
                                        <td class="fw-semibold fs-sm text-capitalize">
                                            @if ($data->type === 'category')
                                                {{ $data->category?->name ?? 'N/A' }}
                                            @else
                                                {{ $data->subcategory?->name ?? 'N/A' }}
                                            @endif
                                        </td>
                                        <td class="fw-semibold fs-sm">{{ $data->priority }}</td>
                                        <td class="fw-semibold fs-sm">
                                            <div class="form-check form-switch text-center">
                                                <input class="form-check-input status-toggle" type="checkbox"
                                                    {{ $data->status == '1' ? 'checked' : '' }}
                                                    data-id="{{ $data->id }}"
                                                    onchange="updateCategoryStatus(this)">
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="border-0 btn btn-sm editCategoryBtn"
                                                data-id="{{ $data->id }}"
                                                data-priority="{{ $data->priority }}"
                                                title="Edit Priority">
                                                <i class="fa fa-pencil text-secondary fa-xl"></i>
                                            </button>
                                            <button type="button" class="border-0 btn btn-sm"
                                                onclick="deleteCategory(this)"
                                                data-id="{{ $data->id }}"
                                                title="Delete">
                                                <i class="fa fa-trash text-danger fa-xl"></i>
                                            </button>
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
        </div>
    </div>

    {{-- Edit Priority Modal --}}
    <div class="modal fade" id="editCategoryModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Edit Priority</h5>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="edit_category_id">

                    <div class="mb-3">
                        <label class="form-label" for="edit_priority">Priority</label>
                        <input type="number" id="edit_priority" class="form-control" required>
                        <small class="text-danger d-none" id="priorityError"></small>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="closeEditModal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="updateCategoryBtn">Update</button>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('footer_scripts')
    <script>
        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        // Toggle subcategory dropdown based on type
        function toggleSubcategoryField() {
            const type = $('#type').val();
            const $subWrapper = $('#subcategoryField');
            const $subSelect = $('#subcategory');

            if (type === 'subcategory') {
                $subWrapper.slideDown(200);
                $subSelect.prop('required', true);

                const categoryId = $('#category').val();
                if (categoryId && typeof loadSubcategoriesByCategory === 'function') {
                    loadSubcategoriesByCategory(categoryId, '#subcategory', "{{ old('subcategory') }}");
                }
            } else {
                $subWrapper.slideUp(200);
                $subSelect.prop('required', false).val('');
            }
        }

        $(document).ready(function() {
            toggleSubcategoryField();

            $('#type').on('change', function() {
                toggleSubcategoryField();
            });

            $('#category').on('change', function() {
                if ($('#type').val() === 'subcategory' && typeof loadSubcategoriesByCategory === 'function') {
                    loadSubcategoriesByCategory($(this).val(), '#subcategory');
                }
            });
        });

        // Show / Hide Add Form
        $('#addCategory').on('click', function() {
            $('#addCategoryField').slideDown(300);
            $(this).hide();
        });

        $('#closeCategoryForm').on('click', function() {
            $('#addCategoryField').slideUp(300);
            $('#addCategory').show();
        });

        // Delete Item
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
                if (!result.isConfirmed) return;

                let url = "{{ route('admin.navMenu.category.destroy', ':id') }}".replace(':id', id);

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
                            showToast(data.message || 'Could not delete', "error");
                        }
                    },
                    error: function(xhr) {
                        let msg = xhr.responseJSON?.message || 'Something went wrong';
                        showToast("An error occurred: " + msg, "error");
                    }
                });
            });
        }

        // Update Status
        function updateCategoryStatus(element) {
            Swal.fire({
                title: "Are you sure?",
                text: "Will update status",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, update it!"
            }).then((result) => {
                if (!result.isConfirmed) {
                    element.checked = !element.checked;
                    return;
                }

                updateCategoryStatusAjax(element);
            });
        }

        function updateCategoryStatusAjax(element) {
            const id = $(element).data('id');
            let url = "{{ route('admin.navMenu.category.status.update', ':id') }}".replace(':id', id);

            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(data) {
                    if (data.success) {
                        showToast(data.message, "success");
                    } else {
                        element.checked = !element.checked;
                        showToast(data.message || 'Failed to update status', "error");
                    }
                },
                error: function(xhr) {
                    element.checked = !element.checked;
                    let msg = xhr.responseJSON?.message || 'Something went wrong';
                    showToast(msg, "error");
                }
            });
        }

        // Edit Modal Open & Priority Update
        function resetPriorityError() {
            $('#priorityError').addClass('d-none').text('');
            $('#edit_priority').removeClass('is-invalid');
        }

        $(document).on('click', '.editCategoryBtn', function() {
            $('#edit_category_id').val($(this).data('id'));
            $('#edit_priority').val($(this).data('priority'));
            resetPriorityError();
            $('#editCategoryModal').modal('show');
        });

        $(document).on('input', '#edit_priority', function() {
            resetPriorityError();
        });

        $(document).on('click', '#closeEditModal', function() {
            resetPriorityError();
            $('#editCategoryModal').modal('hide');
        });

        $(document).on('click', '#updateCategoryBtn', function() {
            let id = $('#edit_category_id').val();
            let priority = $('#edit_priority').val();
            resetPriorityError();

            $.ajax({
                url: "{{ route('admin.navMenu.category.update') }}",
                type: "POST",
                dataType: "json",
                data: {
                    _token: csrfToken,
                    id: id,
                    priority: priority
                },
                beforeSend: function() {
                    $('#updateCategoryBtn').prop('disabled', true).text('Updating...');
                },
                success: function(res) {
                    if (res.errors) {
                        if (res.errors.priority) {
                            $('#priorityError').removeClass('d-none').text(res.errors.priority[0]);
                            $('#edit_priority').addClass('is-invalid');
                        }
                        $('#updateCategoryBtn').prop('disabled', false).text('Update');
                        return;
                    }

                    if (res.success) {
                        $('#editCategoryModal').modal('hide');
                        location.reload();
                    }
                },
                error: function(xhr) {
                    let msg = xhr.responseJSON?.message || 'Something went wrong while updating.';
                    showToast(msg, 'error');
                    $('#updateCategoryBtn').prop('disabled', false).text('Update');
                }
            });
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