@extends('backend.layouts.app')
@section('title', 'Selected Categories')
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
            <div class="col-lg-6 m-auto" id="addSubcategoryField" style="display:none;">
                <div class="block block-rounded">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">
                            Select New Category
                        </h3>
                        <button type="button" id="closeSubcategoryForm" class="btn btn-sm btn-danger">
                            <i class="fas fa-x"></i>
                        </button>
                    </div>
                    <div class="block-content block-content-full overflow-x-auto">
                        <form action="{{ route('admin.selectedCategory.store') }}" method="POST">
                            @csrf
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
                            <div class="mb-0 text-end">
                                <button type="submit" class="btn btn-primary mt-4">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-12 m-auto mt-4">
                <div class="block block-rounded">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">
                            Selected Category List
                        </h3>
                        <button type="button" id="addSubcategory" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus"></i> Add Selected Category
                        </button>
                    </div>
                    <div class="block-content block-content-full overflow-x-auto">
                        <table class="table table-bordered table-vcenter" id="categoryTable">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Logo</th>
                                    <th>Category</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($selectedCategories as $key => $selected)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td class="fw-semibold fs-sm">
                                            <img class="rounded"
                                                src="{{ !empty($selected->category?->logo) ? asset($selected->category->logo) : asset('images/default.png') }}"
                                                width="50" alt="{{ $selected->category?->name ?? 'Category' }}">
                                        </td>
                                        <td class="fw-bold text-success">
                                            {{ $selected->category?->name ?? 'N/A' }}
                                        </td>
                                        <td class="fw-semibold fs-sm">{{ $selected->priority }}</td>
                                        <td class="fw-semibold fs-sm">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input status-toggle" type="checkbox"
                                                    {{ $selected->status == '1' ? 'checked' : '' }}
                                                    data-id="{{ $selected->id }}"
                                                    onchange="updateSubcategoryStatus(this)">
                                            </div>
                                        </td>
                                        <td>
                                            <button type="button" class="border-0 btn btn-sm editSubCategoryBtn"
                                                data-id="{{ $selected->id }}"
                                                data-priority="{{ $selected->priority }}"
                                                title="Edit Priority">
                                                <i class="fa fa-pencil text-secondary fa-xl"></i>
                                            </button>
                                            <button type="button" class="border-0 btn btn-sm"
                                                onclick="deleteSubcategory(this)"
                                                data-id="{{ $selected->id }}"
                                                title="Delete">
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

    <!-- Edit Priority Modal -->
    <div class="modal fade" id="editSubCategoryModal" tabindex="-1" data-bs-backdrop="static">
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
                    <button type="button" class="btn btn-primary" id="updateSubCategoryBtn">Update</button>
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
        const categoryDataTable = $('#categoryTable').DataTable();
        const csrfToken = $('meta[name="csrf-token"]').attr('content');
    </script>

    <script>
        // Delete Selected Category
        function deleteSubcategory(button) {
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

                let url = "{{ route('admin.selectedCategory.destroy', ':id') }}".replace(':id', id);

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
                            categoryDataTable.row($(button).closest('tr')).remove().draw(false);
                        } else {
                            showToast(data.message || 'Could not delete', "error");
                        }
                    },
                    error: function(xhr) {
                        let msg = xhr.responseJSON?.message || 'An error occurred';
                        showToast("An error occurred: " + msg, "error");
                    }
                });
            });
        }

        // Update Status
        function updateSubcategoryStatus(element) {
            Swal.fire({
                title: "Are you sure?",
                text: "Will update status",
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
                let url = "{{ route('admin.selectedCategory.status.update', ':id') }}".replace(':id', id);

                $.ajax({
                    url: url,
                    type: 'POST',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(res) {
                        if (res.success) {
                            showToast(res.message, 'success');
                        } else {
                            element.checked = !element.checked;
                            showToast(res.message || 'Failed to update status', 'error');
                        }
                    },
                    error: function(xhr) {
                        element.checked = !element.checked;
                        let msg = xhr.responseJSON?.message || 'An error occurred while updating status';
                        showToast(msg, 'error');
                    }
                });
            });
        }
    </script>

    <script>
        function resetPriorityError() {
            $('#priorityError').addClass('d-none').text('');
            $('#edit_priority').removeClass('is-invalid');
        }

        // Open Edit Priority Modal
        $(document).on('click', '.editSubCategoryBtn', function() {
            $('#edit_category_id').val($(this).data('id'));
            $('#edit_priority').val($(this).data('priority'));
            resetPriorityError();
            $('#editSubCategoryModal').modal('show');
        });

        $('#edit_priority').on('input', function() {
            resetPriorityError();
        });

        // Close Modal
        $('#closeEditModal').on('click', function() {
            resetPriorityError();
            $('#editSubCategoryModal').modal('hide');
        });

        // Update Priority via AJAX
        $('#updateSubCategoryBtn').on('click', function() {
            let id = $('#edit_category_id').val();
            let priority = $('#edit_priority').val();
            resetPriorityError();

            $.ajax({
                url: "{{ route('admin.selectedCategory.updateAjax') }}",
                type: "POST",
                dataType: "json",
                data: {
                    _token: csrfToken,
                    id: id,
                    priority: priority
                },
                beforeSend: function() {
                    $('#updateSubCategoryBtn').prop('disabled', true).text('Updating...');
                },
                success: function(res) {
                    if (res.errors) {
                        if (res.errors.priority) {
                            $('#priorityError').removeClass('d-none').text(res.errors.priority[0]);
                            $('#edit_priority').addClass('is-invalid');
                        }
                        $('#updateSubCategoryBtn').prop('disabled', false).text('Update');
                        return;
                    }

                    if (res.success) {
                        $('#editSubCategoryModal').modal('hide');
                        location.reload();
                    }
                },
                error: function(xhr) {
                    let msg = xhr.responseJSON?.message || 'Something went wrong while updating priority.';
                    showToast(msg, 'error');
                    $('#updateSubCategoryBtn').prop('disabled', false).text('Update');
                }
            });
        });
    </script>

    <script>
        $('#addSubcategory').click(function() {
            $('#addSubcategoryField').slideDown(300);
            $(this).hide();
        });

        $('#closeSubcategoryForm').click(function() {
            $('#addSubcategoryField').slideUp(300);
            $('#addSubcategory').show();
        });
    </script>

    @if ($errors->any())
        <script>
            $(document).ready(function() {
                $('#addSubcategoryField').show();
                $('#addSubcategory').hide();
            });
        </script>
    @endif
@endpush