<div class="col-md-5 col-xl-3">
    <div class="d-md-none push">
        <button type="button" class="btn w-100 btn-primary" data-toggle="class-toggle" data-target="#one-inbox-side-nav"
            data-class="d-none">
            Review Menu
        </button>
    </div>

    <div id="one-inbox-side-nav" class="d-none d-md-block push">
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Reviews</h3>
            </div>
            <div class="block-content">
                <ul class="nav nav-pills flex-column push">
                    {{-- Pending (status = 0) --}}
                    <li class="nav-item my-1">
                        <a class="nav-link d-flex justify-content-between align-items-center {{ request()->routeIs('admin.review.index') && (string) request('status') === '0' ? 'active' : '' }}"
                            href="{{ route('admin.review.index', ['status' => 0]) }}">
                            <span class="fs-sm">
                                <i class="fa fa-fw fa-inbox me-1 opacity-50"></i> Pending
                            </span>
                            <span class="badge rounded-pill bg-black-50">{{ $reviewCounts['pending'] ?? 0 }}</span>
                        </a>
                    </li>

                    {{-- Approved (status = 1) --}}
                    <li class="nav-item my-1">
                        <a class="nav-link d-flex justify-content-between align-items-center {{ request()->routeIs('admin.review.index') && (string) request('status') === '1' ? 'active' : '' }}"
                            href="{{ route('admin.review.index', ['status' => 1]) }}">
                            <span class="fs-sm">
                                <i class="fa fa-fw fa-check-circle me-1 opacity-50"></i> Approved
                            </span>
                            <span class="badge rounded-pill bg-black-50">{{ $reviewCounts['approved'] ?? 0 }}</span>
                        </a>
                    </li>

                    <li class="nav-item my-1">
                        <a class="nav-link d-flex justify-content-between align-items-center {{ request()->routeIs('admin.review.new') && !request()->has('status') ? 'active' : '' }}"
                            href="{{ route('admin.review.new') }}">
                            <span class="fs-sm">
                                <i class="fa fa-fw fa-pencil me-1 opacity-50"></i> New Review
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
