<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $perPage = 10;
        $search = trim((string) $request->input('search', ''));
        $status = $request->input('status'); // '0', '1', বা null

        $query = Review::with(['customer', 'product'])->latest('id');

        // status পাঠানো হলে (0 বা 1) সেই অনুযায়ী ফিল্টার
        if ($status !== null && $status !== '') {
            $query->where('status', (int) $status);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('review', 'like', "%{$search}%")
                    ->orWhere('rating', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($cq) use ($search) {
                        $cq->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('product', function ($pq) use ($search) {
                        $pq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $queryParams = ['search' => $search];
        if ($status !== null && $status !== '') {
            $queryParams['status'] = $status;
        }

        $reviews = $query->paginate($perPage)->appends($queryParams);

        if ($request->ajax()) {
            $rowsHtml = view('backend.review.partials.rows', compact('reviews'))->render();
            $paginationHtml = $this->buildCustomPagination($reviews);

            $from = $reviews->firstItem() ?? 0;
            $to = $reviews->lastItem() ?? 0;
            $total = $reviews->total();

            return response()->json([
                'rows_html' => $rowsHtml,
                'pagination_html' => $paginationHtml,
                'summary_text' => "{$from}-{$to} <span class=\"fw-normal text-lowercase\">from</span> {$total}",
            ]);
        }

        return view('backend.review.index', compact('reviews', 'search', 'status'));
    }

    public function destroy($id)
    {
        $review = Review::findOrFail($id);

        try {
            $review->delete();

            return response()->json([
                'success' => true,
                'message' => 'Review deleted successfully.',
            ], 200);
        } catch (\Exception $e) {
            Log::error($e);

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete review.',
            ], 500);
        }
    }

    private function buildCustomPagination($paginator): string
    {
        if ($paginator->lastPage() <= 1) {
            return '';
        }

        $current = $paginator->currentPage();
        $last = $paginator->lastPage();

        $html = '<ul class="custom-pagination mb-0">';

        // First «
        if ($current > 1) {
            $html .= '<li class="page-item"><a href="#" class="page-link ajax-page-link" data-page="1" aria-label="First">&laquo;</a></li>';
            $html .= '<li class="page-item"><a href="#" class="page-link ajax-page-link" data-page="' . ($current - 1) . '" aria-label="Previous">&lsaquo;</a></li>';
        } else {
            $html .= '<li class="page-item disabled"><span class="page-link">&laquo;</span></li>';
            $html .= '<li class="page-item disabled"><span class="page-link">&lsaquo;</span></li>';
        }

        // Page window: শুরুতে 1..5, মাঝখানে গেলে current-1..current+1, শেষে last-3..last
        if ($last <= 7) {
            for ($i = 1; $i <= $last; $i++) {
                $active = $i === $current ? 'active' : '';
                $html .= '<li class="page-item ' . $active . '"><a href="#" class="page-link ajax-page-link" data-page="' . $i . '">' . $i . '</a></li>';
            }
        } else {
            if ($current <= 4) {
                for ($i = 1; $i <= 5; $i++) {
                    $active = $i === $current ? 'active' : '';
                    $html .= '<li class="page-item ' . $active . '"><a href="#" class="page-link ajax-page-link" data-page="' . $i . '">' . $i . '</a></li>';
                }
                $html .= '<li class="page-item dots disabled"><span class="page-link">...</span></li>';
                $html .= '<li class="page-item"><a href="#" class="page-link ajax-page-link" data-page="' . $last . '">' . $last . '</a></li>';
            } elseif ($current >= $last - 3) {
                $html .= '<li class="page-item"><a href="#" class="page-link ajax-page-link" data-page="1">1</a></li>';
                $html .= '<li class="page-item dots disabled"><span class="page-link">...</span></li>';
                for ($i = $last - 4; $i <= $last; $i++) {
                    $active = $i === $current ? 'active' : '';
                    $html .= '<li class="page-item ' . $active . '"><a href="#" class="page-link ajax-page-link" data-page="' . $i . '">' . $i . '</a></li>';
                }
            } else {
                $html .= '<li class="page-item"><a href="#" class="page-link ajax-page-link" data-page="1">1</a></li>';
                $html .= '<li class="page-item dots disabled"><span class="page-link">...</span></li>';
                for ($i = $current - 1; $i <= $current + 1; $i++) {
                    $active = $i === $current ? 'active' : '';
                    $html .= '<li class="page-item ' . $active . '"><a href="#" class="page-link ajax-page-link" data-page="' . $i . '">' . $i . '</a></li>';
                }
                $html .= '<li class="page-item dots disabled"><span class="page-link">...</span></li>';
                $html .= '<li class="page-item"><a href="#" class="page-link ajax-page-link" data-page="' . $last . '">' . $last . '</a></li>';
            }
        }

        // Next › & Last »
        if ($current < $last) {
            $html .= '<li class="page-item"><a href="#" class="page-link ajax-page-link" data-page="' . ($current + 1) . '" aria-label="Next">&rsaquo;</a></li>';
            $html .= '<li class="page-item"><a href="#" class="page-link ajax-page-link" data-page="' . $last . '" aria-label="Last">&raquo;</a></li>';
        } else {
            $html .= '<li class="page-item disabled"><span class="page-link">&rsaquo;</span></li>';
            $html .= '<li class="page-item disabled"><span class="page-link">&raquo;</span></li>';
        }

        $html .= '</ul>';

        return $html;
    }


    public function approve($id)
    {
        $review = Review::findOrFail($id);

        try {
            $review->update([
                'status' => 1, // বা 'approved'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Review approved successfully.',
            ]);
        } catch (\Exception $e) {
            Log::error($e);

            return response()->json([
                'success' => false,
                'message' => 'Failed to approve review.',
            ], 500);
        }
    }
}