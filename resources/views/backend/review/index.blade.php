@extends('backend.layouts.app')
@section('title', 'Product Reviews')

@push('style')
    <style>
        .review-search-box {
            max-width: 260px;
        }

        .custom-pagination {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            list-style: none;
            padding: 0;
        }

        .custom-pagination .page-item .page-link {
            min-width: 34px;
            height: 34px;
            padding: 0 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            border-radius: 6px;
            background: transparent;
            color: #374151;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .custom-pagination .page-item:not(.disabled):not(.dots) .page-link:hover {
            background: #f3f4f6;
            color: #111827;
        }

        .custom-pagination .page-item.active .page-link {
            background: #eef2ff;
            color: #1d4ed8;
            font-weight: 600;
        }

        .custom-pagination .page-item.disabled .page-link {
            color: #9ca3af;
            pointer-events: none;
            background: transparent;
        }

        .custom-pagination .page-item.dots .page-link {
            color: #6b7280;
            letter-spacing: 1px;
        }

        .review-detail-card {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 18px;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            @include('backend.review.partials.sidebar')

            <div class="col-md-7 col-xl-9">
                <div class="block block-rounded">
                    <div class="block-header block-header-default flex-wrap gap-2">
                        <h3 class="block-title mb-0" id="reviewSummaryText">
                            {{ $reviews->firstItem() ?? 0 }}-{{ $reviews->lastItem() ?? 0 }}
                            <span class="fw-normal text-lowercase">from</span> {{ $reviews->total() }}
                        </h3>

                        <div class="d-flex align-items-center gap-2 ms-auto">
                            <div class="input-group input-group-sm review-search-box">
                                <span class="input-group-text bg-white">
                                    <i class="fa fa-search text-muted"></i>
                                </span>
                                <input type="text" id="reviewSearchInput" class="form-control"
                                    value="{{ $search ?? '' }}" placeholder="Search review, customer, product...">
                            </div>

                            <div class="block-options">
                                <button type="button" class="btn-block-option" data-toggle="block-option"
                                    data-action="fullscreen_toggle"><i class="si si-size-fullscreen"></i></button>
                            </div>
                        </div>
                    </div>

                    <div class="block-content py-0">
                        <div class="table-responsive">
                            <table class="js-table-checkable table table-hover table-vcenter fs-sm mb-0">
                                <tbody id="reviewsTableBody">
                                    @include('backend.review.partials.rows', ['reviews' => $reviews])
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-end align-items-center py-3" id="reviewsPaginationWrapper">
                            @php
                                $lastPage = $reviews->lastPage();
                                $currentPage = $reviews->currentPage();
                            @endphp
                            @if ($lastPage > 1)
                                <ul class="custom-pagination mb-0">
                                    <li class="page-item {{ $currentPage <= 1 ? 'disabled' : '' }}">
                                        <a href="#" class="page-link ajax-page-link" data-page="1">&laquo;</a>
                                    </li>
                                    <li class="page-item {{ $currentPage <= 1 ? 'disabled' : '' }}">
                                        <a href="#" class="page-link ajax-page-link"
                                            data-page="{{ max(1, $currentPage - 1) }}">&lsaquo;</a>
                                    </li>

                                    @for ($i = 1; $i <= $lastPage; $i++)
                                        @if ($lastPage <= 7 || $i <= 5 || $i == $lastPage || abs($i - $currentPage) <= 1)
                                            <li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
                                                <a href="#" class="page-link ajax-page-link"
                                                    data-page="{{ $i }}">{{ $i }}</a>
                                            </li>
                                        @elseif ($i == 6 && $currentPage <= 4)
                                            <li class="page-item dots disabled"><span class="page-link">...</span></li>
                                        @endif
                                    @endfor

                                    <li class="page-item {{ $currentPage >= $lastPage ? 'disabled' : '' }}">
                                        <a href="#" class="page-link ajax-page-link"
                                            data-page="{{ min($lastPage, $currentPage + 1) }}">&rsaquo;</a>
                                    </li>
                                    <li class="page-item {{ $currentPage >= $lastPage ? 'disabled' : '' }}">
                                        <a href="#" class="page-link ajax-page-link"
                                            data-page="{{ $lastPage }}">&raquo;</a>
                                    </li>
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Review Details Modal --}}
        <div class="modal fade" id="one-inbox-message" tabindex="-1" role="dialog" aria-labelledby="one-inbox-message"
            aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-popout" role="document">
                <div class="modal-content">
                    <div class="block block-rounded block-transparent mb-0">
                        <div class="block-header block-header-default">
                            <h3 class="block-title">Details Review</h3>
                            <div class="block-options">
                                <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                                    <i class="fa fa-fw fa-times"></i>
                                </button>
                            </div>
                        </div>

                        <div class="block-content block-content-full">
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <div class="text-muted fs-xs text-uppercase fw-semibold">Product Name</div>
                                    <div class="fw-bold fs-6 text-dark mt-1" id="modalProductName">-</div>
                                </div>
                                <div class="col-md-6">
                                    <div class="text-muted fs-xs text-uppercase fw-semibold">Customer Name</div>
                                    <div class="fw-bold fs-6 text-dark mt-1" id="modalCustomerName">-</div>
                                </div>
                                <div class="col-md-6">
                                    <div class="text-muted fs-xs text-uppercase fw-semibold">Date</div>
                                    <div class="fw-semibold text-secondary mt-1" id="modalReviewDate">-</div>
                                </div>
                                <div class="col-md-6">
                                    <div class="text-muted fs-xs text-uppercase fw-semibold">Rating</div>
                                    <div class="mt-1" id="modalReviewRating"></div>
                                </div>
                            </div>

                            <div class="review-detail-card">
                                <div class="text-muted fs-xs text-uppercase fw-semibold mb-2">Review</div>
                                <p class="mb-0 fs-sm text-dark" style="line-height: 1.7; white-space: pre-line;"
                                    id="modalReviewText">-</p>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center bg-body-light">
                            <div class="block-content block-content-full py-3 text-start d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-success" id="modalApproveBtn" data-id="">
                                    <i class="fa fa-check me-1"></i> Approve
                                </button>

                                <button type="button" class="btn btn-sm btn-alt-secondary" id="modalDeleteBtn"
                                    data-id="">
                                    <i class="fa fa-fw fa-trash text-danger me-1"></i> Delete
                                </button>
                            </div>

                            <div class="block-content block-content-full py-3 text-end">
                                <button type="button" class="btn btn-sm btn-secondary"
                                    data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('footer_scripts')
    <script>
        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        const indexUrl = "{{ route('admin.review.index') }}";
        const approveUrlTemplate = "{{ route('admin.review.approve', ':id') }}";
        const deleteUrlTemplate = "{{ route('admin.review.destroy', ':id') }}";

        let currentPage = {{ $reviews->currentPage() }};
        let currentStatus = @json($status ?? '');
        let searchTimer = null;

        function notifyUser(message, type = 'success') {
            if (typeof showToast === 'function') {
                showToast(message, type);
            } else if (typeof Swal !== 'undefined') {
                const titleMap = {
                    success: 'Success!',
                    error: 'Error!',
                    warning: 'Warning!',
                    info: 'Info'
                };
                Swal.fire(titleMap[type] || 'Notice', message, type);
            } else {
                alert(message);
            }
        }

        function fetchReviews(page = 1, search = '') {
            currentPage = page;

            $('#reviewsTableBody').html(
                '<tr><td colspan="5" class="text-center py-4 text-muted">Loading reviews...</td></tr>'
            );

            const requestData = {
                page: page,
                search: search
            };

            if (currentStatus !== '' && currentStatus !== null) {
                requestData.status = currentStatus;
            }

            $.ajax({
                url: indexUrl,
                type: 'GET',
                dataType: 'json',
                data: requestData,
                success: function(res) {
                    $('#reviewsTableBody').html(res.rows_html);
                    $('#reviewsPaginationWrapper').html(res.pagination_html);

                    if (res.summary_text) {
                        $('#reviewSummaryText').html(res.summary_text);
                    }
                },
                error: function(xhr) {
                    console.error('Fetch Reviews Error:', xhr.status, xhr.responseText);
                    $('#reviewsTableBody').html(
                        '<tr><td colspan="5" class="text-center py-4 text-danger">Failed to load reviews.</td></tr>'
                    );
                }
            });
        }

        // Common Approve Function (Table & Modal)
        function approveReview(id, $triggerBtn = null) {
            if (!id) return;

            const url = approveUrlTemplate.replace(':id', id);

            if ($triggerBtn && $triggerBtn.length) {
                $triggerBtn.prop('disabled', true);
            }

            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(res) {
                    if (res.success) {
                        notifyUser(res.message || 'Review approved successfully.', 'success');
                        $('#one-inbox-message').modal('hide');
                        fetchReviews(currentPage, $('#reviewSearchInput').val());
                    } else {
                        if ($triggerBtn && $triggerBtn.length) {
                            $triggerBtn.prop('disabled', false);
                        }
                        notifyUser(res.message || 'Could not approve review.', 'error');
                    }
                },
                error: function(xhr) {
                    if ($triggerBtn && $triggerBtn.length) {
                        $triggerBtn.prop('disabled', false);
                    }
                    const msg = xhr.responseJSON?.message || 'Something went wrong while approving.';
                    notifyUser(msg, 'error');
                }
            });
        }

        // Live Search with debounce
        $('#reviewSearchInput').on('keyup', function() {
            clearTimeout(searchTimer);
            const query = $(this).val();

            searchTimer = setTimeout(function() {
                fetchReviews(1, query);
            }, 300);
        });

        // AJAX Pagination click
        $(document).on('click', '.ajax-page-link', function(e) {
            e.preventDefault();
            const page = $(this).data('page');
            const search = $('#reviewSearchInput').val();

            if (!page) return;
            fetchReviews(page, search);
        });

        // Populate Details Modal when clicking short review
        $(document).on('click', '.open-review-modal', function() {
            const $el = $(this);
            const id = $el.data('id');
            const product = $el.data('product') || 'N/A';
            const customer = $el.data('customer') || 'N/A';
            const date = $el.data('date') || 'N/A';
            const review = $el.data('review') || '';
            const rating = parseInt($el.data('rating'), 10) || 0;

            $('#modalApproveBtn').attr('data-id', id).data('id', id);
            $('#modalDeleteBtn').attr('data-id', id).data('id', id);

            $('#modalProductName').text(product);
            $('#modalCustomerName').text(customer);
            $('#modalReviewDate').text(date);
            $('#modalReviewText').text(review);

            let starsHtml = '<span class="text-warning fs-6">';
            for (let i = 1; i <= 5; i++) {
                starsHtml += i <= rating ?
                    '<i class="fas fa-star me-1"></i>' :
                    '<i class="far fa-star text-muted me-1"></i>';
            }
            starsHtml += `</span> <span class="fw-semibold text-muted ms-1">(${rating}/5)</span>`;
            $('#modalReviewRating').html(starsHtml);
        });

        // Approve Review from Table button
        $(document).on('click', '.approve-review-btn', function() {
            const id = $(this).data('id');
            approveReview(id, $(this));
        });

        // Approve Review from Modal button
        $('#modalApproveBtn').on('click', function() {
            const id = $(this).data('id');
            approveReview(id, $(this));
        });

        // Delete Review (from Table button or Modal button)
        function deleteReview(button) {
            const id = $(button).data('id');
            if (!id) return;

            const url = deleteUrlTemplate.replace(':id', id);

            Swal.fire({
                title: "Are you sure?",
                text: "This review will be permanently deleted!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "Cancel"
            }).then((result) => {
                if (!result.isConfirmed) return;

                $.ajax({
                    url: url,
                    type: 'DELETE',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(data) {
                        if (data.success) {
                            notifyUser(data.message || 'Review deleted successfully.', 'success');
                            $('#one-inbox-message').modal('hide');
                            fetchReviews(currentPage, $('#reviewSearchInput').val());
                        } else {
                            notifyUser(data.message || 'Failed to delete review.', 'error');
                        }
                    },
                    error: function(xhr) {
                        const msg = xhr.responseJSON?.message || 'Something went wrong while deleting.';
                        notifyUser(msg, 'error');
                    }
                });
            });
        }

        $('#modalDeleteBtn').on('click', function() {
            deleteReview(this);
        });
    </script>
@endpush
