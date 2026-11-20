@extends('backend.layouts.app')
@section('title', 'Sub-subcategories')

@push('style')
    <link rel="stylesheet" href="{{ asset('assets') }}/js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css">
    <link rel="stylesheet"
        href="{{ asset('assets') }}/js/plugins/datatables-responsive-bs5/css/responsive.bootstrap5.min.css">
@endpush

@section('content')
    <!-- Main content -->
    <div class="container-fluid">
        <div class="row">
            <!-- Add New SubSubCategory Form -->
            <div class="col-lg-6 m-auto" id="addSubsubcategoryField" style="display:none;">
                <div class="block block-rounded">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Add New Sub-Subcategory</h3>
                        <button type="button" id="closeSubSubcategoryForm" class="btn btn-sm btn-danger">
                            <i class="fas fa-x"></i>
                        </button>
                    </div>
                    <div class="block-content block-content-full overflow-x-auto">
                        <form action="{{ route('admin.subsubcategory.store') }}" method="POST">
                            @csrf

                            <div class="mb-4">
                                <label class="form-label" for="category">Select Category</label>
                                <select class="form-control" id="category" name="category" required>
                                    <option value="" disabled {{ old('category') ? '' : 'selected' }}>Select Category</option>
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

                            <div class="mb-4">
                                <label class="form-label" for="subcategory">Select Subcategory</label>
                                <select class="form-control" id="subcategory" name="subcategory" required>
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

                            <div class="mb-4">
                                <label class="form-label" for="subcategory_name">Sub-Subcategory Name</label>
                                <input type="text" class="form-control" id="subcategory_name" name="subcategory_name"
                                    placeholder="Enter sub-subcategory name.." value="{{ old('subcategory_name') }}" required>
                                @error('subcategory_name')
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

            <!-- SubSubcategory List Table -->
            <div class="col-lg-12 m-auto mt-4">
                <div class="block block-rounded">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Sub-subcategory List</h3>
                        <button type="button" id="addSubcategory" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus"></i> Add Sub-Subcategory
                        </button>
                    </div>
                    <div class="block-content block-content-full overflow-x-auto">
                        <table class="table table-bordered table-vcenter" id="categoryTable">
                            <thead>
                                <tr>
                                    <th class="text-center">SL</th>
                                    <th>Sub-Subcategory</th>
                                    <th>Slug</th>
                                    <th>Subcategory</th>
                                    <th>Category</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($subsubcategories as $key => $item)
                                    <tr>
                                        <td class="text-center fs-sm">{{ $key + 1 }}</td>
                                        <td class="fw-semibold fs-sm">{{ $item->name }}</td>
                                        <td class="fw-semibold fs-sm">{{ $item->slug }}</td>
                                        <td class="fs-sm fw-bold text-primary text-center">
                                            {{ $item->subcategory->name ?? 'N/A' }}
                                        </td>
                                        <td class="fs-sm fw-bold text-success text-center">
                                            {{ $item->category->name ?? 'N/A' }}
                                        </td>
                                        <td class="fw-semibold fs-sm">{{ $item->priority }}</td>
                                        <td class="fw-semibold fs-sm">
                                            <div class="form-check form-switch text-center">
                                                <input class="form-check-input" type="checkbox"
                                                    {{ $item->status == '1' ? 'checked' : '' }}
                                                    data-id="{{ $item->id }}"
                                                    onchange="updateSubSubcategoryStatus(this)">
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="border-0 btn btn-sm editSubSubCategoryBtn"
                                                data-id="{{ $item->id }}"
                                                data-category-id="{{ $item->category_id }}"
                                                data-subcategory-id="{{ $item->subcategory_id }}"
                                                data-name="{{ $item->name }}"
                                                data-slug="{{ $item->slug }}"
                                                data-priority="{{ $item->priority }}">
                                                <i class="fa fa-pencil text-secondary fa-xl"></i>
                                            </button>
                                            <button type="button" class="border-0 btn btn-sm"
                                                onclick="deleteSubSubcategory(this)" data-id="{{ $item->id }}">
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

    <!-- Edit SubSubcategory Modal -->
    <div class="modal fade" id="editSubSubCategoryModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Sub-Subcategory</h5>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="edit_subsubcategory_id">

                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select id="edit_category_id" class="form-control">
                            <option value="" disabled>Select Category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <small class="text-danger d-none" id="editCategoryError"></small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Subcategory</label>
                        <select id="edit_subcategory_id" class="form-control">
                            <option value="" disabled>Select Subcategory</option>
                        </select>
                        <small class="text-danger d-none" id="editSubcategoryError"></small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Sub-Subcategory Name</label>
                        <input type="text" id="edit_name" class="form-control">
                        <small class="text-danger d-none" id="nameError"></small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Slug</label>
                        <input type="text" id="edit_slug" class="form-control">
                        <small class="text-danger d-none" id="slugError"></small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Priority</label>
                        <input type="number" id="edit_priority" class="form-control">
                        <small class="text-danger d-none" id="priorityError"></small>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="closeEditModal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="updateSubSubCategoryBtn">Update</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('footer_scripts')
    <!-- DataTables & Plugins -->
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
                            let isSelected = (selectedSubcategoryId && Number(selectedSubcategoryId) === Number(sub.id))
                                ? 'selected'
                                : '';
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

        // Edit Modal: Category change korle Subcategory load hobe
        $('#edit_category_id').on('change', function() {
            loadSubcategoriesByCategory($(this).val(), '#edit_subcategory_id');
        });
    </script>

    <script>
        // Delete SubSubcategory
        function deleteSubSubcategory(button) {
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
                    let url = "{{ route('admin.subsubcategory.destroy', ':id') }}".replace(':id', id);
                    let token = $('meta[name="csrf-token"]').attr('content');

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
                            let msg = xhr.responseJSON?.message || 'An error occurred';
                            showToast("An error occurred: " + msg, "error");
                        }
                    });
                }
            });
        }

        // Update Status
        function updateSubSubcategoryStatus(element) {
            Swal.fire({
                title: "Are you sure?",
                text: "Will update sub-subcategory status",
                icon: "warning",
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                showCancelButton: true,
                confirmButtonText: "Yes, Update it!"
            }).then((result) => {
                if (!result.isConfirmed) {
                    element.checked = !element.checked;
                    return;
                }

                let id = $(element).data('id');
                let url = "{{ route('admin.subsubcategory.status.update', ':id') }}".replace(':id', id);

                $.ajax({
                    url: url,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(res) {
                        showToast(res.message, res.success ? 'success' : 'error');
                    },
                    error: function() {
                        element.checked = !element.checked;
                        showToast('Failed to update status', 'error');
                    }
                });
            });
        }
    </script>

    <script>
        // Open Edit Modal & populate values + dependent subcategories
        $(document).on('click', '.editSubSubCategoryBtn', function() {
            let categoryId = $(this).data('category-id');
            let subcategoryId = $(this).data('subcategory-id');

            $('#edit_subsubcategory_id').val($(this).data('id'));
            $('#edit_category_id').val(categoryId);
            $('#edit_name').val($(this).data('name'));
            $('#edit_slug').val($(this).data('slug'));
            $('#edit_priority').val($(this).data('priority'));

            resetErrors();
            loadSubcategoriesByCategory(categoryId, '#edit_subcategory_id', subcategoryId);
            $('#editSubSubCategoryModal').modal('show');
        });

        // Close Edit Modal
        $('#closeEditModal').on('click', function() {
            $('#editSubSubCategoryModal').modal('hide');
        });

        function resetErrors() {
            $('#editCategoryError, #editSubcategoryError, #nameError, #slugError, #priorityError')
                .addClass('d-none')
                .text('');
            $('#edit_category_id, #edit_subcategory_id, #edit_name, #edit_slug, #edit_priority')
                .removeClass('is-invalid');
        }

        $('#edit_category_id, #edit_subcategory_id, #edit_name, #edit_slug, #edit_priority').on('input change', function() {
            resetErrors();
        });

        // Submit AJAX Update
        $('#updateSubSubCategoryBtn').on('click', function() {
            let id = $('#edit_subsubcategory_id').val();
            resetErrors();

            $.ajax({
                url: "{{ route('admin.subsubcategory.update') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    category_id: $('#edit_category_id').val(),
                    subcategory_id: $('#edit_subcategory_id').val(),
                    name: $('#edit_name').val().trim(),
                    slug: $('#edit_slug').val().trim(),
                    priority: $('#edit_priority').val()
                },
                beforeSend: function() {
                    $('#updateSubSubCategoryBtn')
                        .prop('disabled', true)
                        .text('Updating...');
                },
                success: function(res) {
                    if (res.errors) {
                        if (res.errors.category_id) {
                            $('#editCategoryError').removeClass('d-none').text(res.errors.category_id[0]);
                            $('#edit_category_id').addClass('is-invalid');
                        }
                        if (res.errors.subcategory_id) {
                            $('#editSubcategoryError').removeClass('d-none').text(res.errors.subcategory_id[0]);
                            $('#edit_subcategory_id').addClass('is-invalid');
                        }
                        if (res.errors.name) {
                            $('#nameError').removeClass('d-none').text(res.errors.name[0]);
                            $('#edit_name').addClass('is-invalid');
                        }
                        if (res.errors.slug) {
                            $('#slugError').removeClass('d-none').text(res.errors.slug[0]);
                            $('#edit_slug').addClass('is-invalid');
                        }
                        if (res.errors.priority) {
                            $('#priorityError').removeClass('d-none').text(res.errors.priority[0]);
                            $('#edit_priority').addClass('is-invalid');
                        }
                        return;
                    }

                    if (res.success) {
                        location.reload();
                    }
                },
                error: function() {
                    showToast('Something went wrong while updating.', 'error');
                },
                complete: function() {
                    $('#updateSubSubCategoryBtn')
                        .prop('disabled', false)
                        .text('Update');
                }
            });
        });
    </script>

    <script>
        // Toggle Add Form
        $('#addSubcategory').click(function() {
            $('#addSubsubcategoryField').slideDown(300);
            $(this).hide();
        });

        $('#closeSubSubcategoryForm').click(function() {
            $('#addSubsubcategoryField').slideUp(300);
            $('#addSubcategory').show();
        });
    </script>

    @if ($errors->any())
        <script>
            $(document).ready(function() {
                $('#addSubsubcategoryField').show();
                $('#addSubcategory').hide();
            });
        </script>
    @endif
@endpush