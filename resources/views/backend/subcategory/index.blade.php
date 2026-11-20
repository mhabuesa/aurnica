@extends('backend.layouts.app')
@section('title', 'Subcategories')
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
                            Add New Subcategory
                        </h3>
                        <button type="button" id="closeSubcategoryForm" class="btn btn-sm btn-danger">
                            <i class="fas fa-x"></i>
                        </button>
                    </div>
                    <div class="block-content block-content-full overflow-x-auto">
                        <!-- enctype="multipart/form-data" add kora hoyeche -->
                        <form action="{{ route('admin.subcategory.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-4">
                                <label class="form-label" for="category">Select Category </label>
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
                            <div class="mb-4">
                                <label class="form-label" for="subcategory_name">Sub Category Name</label>
                                <input type="text" class="form-control" id="subcategory_name" name="subcategory_name"
                                    placeholder="Enter subcategory name.." value="{{ old('subcategory_name') }}" required>
                                @error('subcategory_name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-4">
                                        <label class="form-label" for="logo">Sub Category Logo
                                            <small class="text-info">(500x500 px)</small></label>
                                        <input type="file" class="form-control" id="logo" name="logo"
                                            accept="image/*">
                                        @error('logo')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-4 text-center">
                                        <img src="https://placehold.co/500" width="150" id="add_preview"
                                            alt="Logo Preview">
                                    </div>
                                </div>
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
                            Subcategory List
                        </h3>
                        <button type="button" id="addSubcategory" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus"></i> Add Subcategory
                        </button>
                    </div>
                    <div class="block-content block-content-full overflow-x-auto">
                        <table class="table table-bordered table-vcenter" id="categoryTable">
                            <thead>
                                <tr>
                                    <th class="text-center">SL</th>
                                    <th>Logo</th>
                                    <th>Subcategory</th>
                                    <th>Slug</th>
                                    <th>Category</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($subcategories as $key => $subcategory)
                                    <tr>
                                        <td class="text-center fs-sm">{{ $key + 1 }}</td>
                                        <td class="text-center fs-sm">
                                            <img class="rounded" src="{{ $subcategory->logo ? asset($subcategory->logo): 'https://placehold.co/500' }}" alt="" width="50">
                                        </td>
                                        <td class="fw-semibold fs-sm">{{ $subcategory->name }}</td>
                                        <td class="fw-semibold fs-sm">{{ $subcategory->slug }}</td>
                                        <td class="fs-sm fw-bold text-success text-center">
                                            {{ $subcategory->category->name }}</td>
                                        <td class="fw-semibold fs-sm">{{ $subcategory->priority }}</td>
                                        <td class="fw-semibold fs-sm">
                                            <div class="form-check form-switch text-center">
                                                <input class="form-check-input" id="status" type="checkbox"
                                                    {{ $subcategory->status == '1' ? 'checked' : '' }} name="status"
                                                    data-id="{{ $subcategory->id }}"
                                                    data-status="{{ $subcategory->status }}"
                                                    onchange="updateSubcategoryStatus(this)">
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="border-0 btn btn-sm editSubCategoryBtn"
                                                data-id="{{ $subcategory->id }}" data-name="{{ $subcategory->name }}"
                                                data-slug="{{ $subcategory->slug }}"
                                                data-priority="{{ $subcategory->priority }}"
                                                data-logo="{{ asset($subcategory->logo) }}">
                                                <i class="fa fa-pencil text-secondary fa-xl"></i>
                                            </button>
                                            <button type="button" class="border-0 btn btn-sm"
                                                onclick="deleteSubcategory(this)" data-id="{{ $subcategory->id }}">
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

    <div class="modal fade" id="editSubCategoryModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Edit Sub Category</h5>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="edit_subcategory_id">

                    <div class="mb-3">
                        <label>Sub Category Name</label>
                        <input type="text" id="edit_name" class="form-control">
                        <small class="text-danger d-none" id="nameError"></small>
                    </div>

                    <div class="mb-3">
                        <label>Slug</label>
                        <input type="text" id="edit_slug" class="form-control">
                        <small class="text-danger d-none" id="slugError"></small>
                    </div>

                    <div class="mb-3">
                        <label>Logo</label>
                        <input type="file" id="edit_logo" class="form-control" accept="image/*">
                        <small class="text-danger d-none" id="logoError"></small>
                    </div>

                    <div class="mb-3 text-center">
                        <label class="d-block text-start">Preview</label>
                        <img src="https://placehold.co/500" alt="Logo" id="edit_preview" width="150">
                    </div>

                    <div class="mb-3">
                        <label>Priority</label>
                        <input type="number" id="edit_priority" class="form-control">
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
        $('#categoryTable').DataTable();

        // Add form image preview
        $('#logo').on('change', function() {
            if (this.files && this.files[0]) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    $('#add_preview').attr('src', e.target.result);
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    </script>

    <script>
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
                if (result.isConfirmed) {
                    let url = "{{ route('admin.subcategory.destroy', ':id') }}".replace(':id', id);
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
                            let msg = xhr.responseJSON?.message || 'An error occurred';
                            showToast("An error occurred: " + msg, "error");
                        }
                    });
                }
            });
        }

        function updateSubcategoryStatus(element) {
            Swal.fire({
                title: "Are you sure?",
                text: "Will update subcategory status",
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
                let url = "{{ route('admin.subcategory.status.update', ':id') }}".replace(':id', id);

                $.ajax({
                    url: url,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(res) {
                        showToast(res.message, res.success ? 'success' : 'error');
                    }
                });
            });
        }
    </script>

    <script>
        // Edit Sub Category button click
        $(document).on('click', '.editSubCategoryBtn', function() {
            $('#edit_subcategory_id').val($(this).data('id'));
            $('#edit_name').val($(this).data('name'));
            $('#edit_slug').val($(this).data('slug'));
            $('#edit_priority').val($(this).data('priority'));
            $('#edit_logo').val('');

            let logo = $(this).data('logo');

            if (logo) {
                $('#edit_preview').attr('src', logo);
            } else {
                $('#edit_preview').attr('src', 'https://placehold.co/500');
            }

            resetErrors();
            $('#editSubCategoryModal').modal('show');
        });

        // Edit logo preview
        $('#edit_logo').on('change', function() {
            if (this.files && this.files[0]) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    $('#edit_preview').attr('src', e.target.result);
                };
                reader.readAsDataURL(this.files[0]);
            }
        });

        // Cancel modal
        $('#closeEditModal').on('click', function() {
            $('#editSubCategoryModal').modal('hide');
        });

        // Reset errors
        function resetErrors() {
            $('#nameError, #slugError, #logoError').addClass('d-none').text('');
            $('#edit_name, #edit_slug, #edit_logo').removeClass('is-invalid');
        }

        $('#edit_name, #edit_slug, #edit_logo').on('input change', function() {
            resetErrors();
        });

        // Update Sub Category via FormData
        $('#updateSubCategoryBtn').on('click', function() {
            let id = $('#edit_subcategory_id').val();
            resetErrors();

            let formData = new FormData();
            formData.append('_token', "{{ csrf_token() }}");
            formData.append('id', id);
            formData.append('name', $('#edit_name').val().trim());
            formData.append('slug', $('#edit_slug').val().trim());
            formData.append('priority', $('#edit_priority').val());

            let logoFile = $('#edit_logo')[0].files[0];
            if (logoFile) {
                formData.append('logo', logoFile);
            }

            $.ajax({
                url: "{{ route('admin.subcategory.update') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $('#updateSubCategoryBtn')
                        .prop('disabled', true)
                        .text('Updating...');
                },
                success: function(res) {
                    if (res.errors) {
                        if (res.errors.name) {
                            $('#nameError').removeClass('d-none').text(res.errors.name[0]);
                            $('#edit_name').addClass('is-invalid');
                        }
                        if (res.errors.slug) {
                            $('#slugError').removeClass('d-none').text(res.errors.slug[0]);
                            $('#edit_slug').addClass('is-invalid');
                        }
                        if (res.errors.logo) {
                            $('#logoError').removeClass('d-none').text(res.errors.logo[0]);
                            $('#edit_logo').addClass('is-invalid');
                        }
                        return;
                    }

                    if (res.success) {
                        location.reload();
                    }
                },
                error: function(xhr) {
                    let msg = xhr.responseJSON?.message || 'Something went wrong while updating.';
                    showToast(msg, 'error');
                },
                complete: function() {
                    $('#updateSubCategoryBtn')
                        .prop('disabled', false)
                        .text('Update');
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
