@forelse ($reviews as $key => $review)
    @php
        $sl = ($reviews->currentPage() - 1) * $reviews->perPage() + $key + 1;
        $productName = $review->product?->name ?? 'N/A';
        $customerName = $review->customer?->name ?? ($review->name ?? 'Guest');
        $formattedDate = $review->created_at?->format('d-M-Y') ?? 'N/A';
    @endphp
    <tr id="review-row-{{ $review->id }}">
        <td class="text-center" style="width: 60px;">
            {{ $sl }}
        </td>
        <td class="d-none d-sm-table-cell fw-semibold" style="width: 160px;">
            {{ $customerName }}
        </td>
        <td>
            <a href="javascript:void(0)" class="fw-semibold open-review-modal" data-bs-toggle="modal"
                data-bs-target="#one-inbox-message" data-id="{{ $review->id }}" data-product="{{ e($productName) }}"
                data-customer="{{ e($customerName) }}" data-rating="{{ (int) $review->rating }}"
                data-date="{{ e($formattedDate) }}" data-review="{{ e($review->review) }}">
                {{ Str::limit($review->review, 20) }}
            </a>
            <div class="text-muted mt-1">
                <div class="text-warning text-ls-n2 font-size-16">
                    @for ($i = 1; $i <= 5; $i++)
                        <small class="{{ $i <= $review->rating ? 'fas fa-star' : 'far fa-star text-muted' }}"></small>
                    @endfor
                </div>
            </div>
        </td>
        <td class="d-none d-xl-table-cell text-muted" style="width: 120px;">
            <em>{{ $formattedDate }}</em>
        </td>
        <td class="d-none d-xl-table-cell text-muted" style="width: 120px;">
            @if ($review->status == 0)
                <button type="button" class="btn btn-alt-secondary approve-review-btn" data-id="{{ $review->id }}"
                    title="Approve Review">
                    <i class="fa fa-fw text-info fa-check"></i>
                </button>
            @endif

            <button type="button" class="btn btn-alt-secondary" onclick="deleteReview(this)"
                data-id="{{ $review->id }}" title="Delete Review">
                <i class="fa fa-fw fa-trash text-danger"></i>
            </button>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="text-center py-4 text-muted">No Data Found</td>
    </tr>
@endforelse
