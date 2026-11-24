<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\NavCategory;
use App\Models\NavMenu;
use App\Models\NavProduct;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class NavMenuController extends Controller
{
    public function index()
    {
        $categories = Category::where('status', '1')->orderBy('priority', 'asc')->get();

        $subcategories = collect();
        if (old('category')) {
            $subcategories = Subcategory::where('category_id', old('category'))
                ->orderBy('priority', 'asc')
                ->get();
        }

        $navData = NavCategory::with(['category', 'subcategory'])
            ->orderBy('priority', 'asc')
            ->get();

        return view('backend.navmenu.index', compact('categories', 'subcategories', 'navData'));
    }

    public function category_store(Request $request)
    {
        $rules = [
            'type' => 'required|in:category,subcategory',
            'category' => 'required|exists:categories,id',
            'subcategory' => 'nullable|required_if:type,subcategory|exists:subcategories,id',
        ];

        $request->validate($rules);

        $maxPriority = NavCategory::max('priority') ?? 0;
        $priority = $maxPriority + 1;

        NavCategory::create([
            'type' => $request->type,
            'category_id' => $request->category,
            'subcategory_id' => $request->type === 'subcategory' ? $request->subcategory : null,
            'priority' => $priority,
            'status' => '1',
        ]);

        return redirect()->route('admin.navMenu.index')->with('success', 'Nav item added successfully.');
    }

    public function category_destroy(string $id)
    {
        $data = NavCategory::findOrFail($id);

        try {
            $data->delete();
        } catch (\Exception $e) {
            Log::error($e);

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete item.',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Deleted successfully.',
        ], 200);
    }

    public function category_status_update($id)
    {
        $data = NavCategory::findOrFail($id);

        try {
            $data->update([
                'status' => $data->status == '1' ? '0' : '1',
            ]);
        } catch (\Exception $e) {
            Log::error($e);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update status.',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully.',
        ], 200);
    }

    public function category_update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:nav_categories,id',
            'priority' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ]);
        }

        try {
            $navCategory = NavCategory::findOrFail($request->id);
            $navCategory->update([
                'priority' => $request->priority,
            ]);

            Session::flash('success', 'Priority updated successfully.');

            return response()->json([
                'success' => true,
            ]);
        } catch (\Exception $e) {
            Log::error($e);

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while updating.',
            ], 500);
        }
    }


    // navItem Method
    public function navItem()
    {
        $navMenus = NavMenu::orderBy('priority', 'asc')->get();
        return view('backend.navmenu.navItem', compact([
            'navMenus'
        ]));
    }

    public function navItem_store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $maxPriority = NavMenu::max('priority') ?? 0;
        $priority = $maxPriority + 1;


        NavMenu::create([
            'name' => $request->name,
            'priority' => $priority,
        ]);

        return redirect()->route('admin.navMenu.navItem')->with('success', 'Nav Menu Created SUccessful');
    }


    public function navItemUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:nav_menus,id',
            'name' => 'required|string|max:255',
            'priority' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ]);
        }

        try {
            $navMenu = NavMenu::findOrFail($request->id);
            $navMenu->update([
                'name' => $request->name,
                'priority' => $request->priority,
            ]);

            Session::flash('success', 'Menu updated successfully.');

            return response()->json([
                'success' => true,
            ]);
        } catch (\Exception $e) {
            Log::error($e);

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while updating.',
            ], 500);
        }
    }


    public function navItem_destroy(string $id)
    {
        $data = NavMenu::findOrFail($id);

        try {
            $data->delete();
        } catch (\Exception $e) {
            Log::error($e);

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete item.',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Deleted successfully.',
        ], 200);
    }

    public function navItem_status_update($id)
    {
        $data = NavMenu::findOrFail($id);

        try {
            $data->update([
                'status' => $data->status == '1' ? '0' : '1',
            ]);
        } catch (\Exception $e) {
            Log::error($e);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update status.',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully.',
        ], 200);
    }

    public function product_assign($id)
    {
        $navmenu = NavMenu::findOrFail($id);

        // পেজ লোডে সব প্রোডাক্ট দেখাতে (প্রয়োজনে ->where('status', '1') বা 'active' দিন)
        $products = Product::latest('id')->get();

        // আপনার nav_products টেবিলের কলাম nav_menu_id হলে সেটি ব্যবহার করুন
        $navProducts = NavProduct::with('product')
            ->where('navMenu_id', $navmenu->id)
            ->latest('id')
            ->get();

        $assignedProductIds = $navProducts->pluck('product_id')->map(fn($pid) => (int) $pid);

        $categories = Category::where('status', '1')
            ->orderBy('priority', 'asc')
            ->get();

        return view('backend.navmenu.product_assign', compact(
            'products',
            'navmenu',
            'navProducts',
            'assignedProductIds',
            'categories'
        ));
    }

    public function filterProducts(Request $request, $id)
    {
        $navmenu = NavMenu::findOrFail($id);

        $categoryId = $request->input('category_id');
        $subcategoryId = $request->input('subcategory_id');

        $subcategories = collect();
        if (!empty($categoryId)) {
            $subcategories = Subcategory::where('category_id', $categoryId)
                ->orderBy('priority', 'asc')
                ->get(['id', 'name']);
        }

        $query = Product::query();

        if (!empty($subcategoryId)) {
            $query->where('subcategory_id', $subcategoryId);
        } elseif (!empty($categoryId)) {
            $query->where('category_id', $categoryId);
        }

        $products = $query->latest('id')->get();

        $assignedProductIds = NavProduct::where('navMenu_id', $navmenu->id)
            ->pluck('product_id')
            ->map(fn($pid) => (int) $pid);

        $html = view('backend.navmenu.partials.product_checkboxes', compact(
            'products',
            'assignedProductIds'
        ))->render();

        return response()->json([
            'html' => $html,
            'count' => $products->count(),
            'subcategories' => $subcategories,
        ]);
    }


    public function product_store(Request $request, $id)
    {
        $navmenu = NavMenu::findOrFail($id);

        $validated = $request->validate([
            'products' => 'nullable|array',
            'products.*' => 'integer|exists:products,id',
        ]);

        $selectedIds = collect($validated['products'] ?? [])
            ->map(fn($pid) => (int) $pid)
            ->unique();

        NavProduct::where('navMenu_id', $navmenu->id)
            ->whereNotIn('product_id', $selectedIds)
            ->delete();

        $existingIds = NavProduct::where('navMenu_id', $navmenu->id)
            ->pluck('product_id')
            ->map(fn($pid) => (int) $pid);

        foreach ($selectedIds as $productId) {
            if (!$existingIds->contains($productId)) {
                NavProduct::create([
                    'navMenu_id' => $navmenu->id,
                    'product_id' => $productId,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Products assigned successfully.');
    }

    public function product_destroy($id)
    {
        $navProduct = NavProduct::findOrFail($id);

        try {
            $navProduct->delete();
        } catch (\Exception $e) {
            Log::error($e);

            return response()->json([
                'success' => false,
                'message' => 'Failed to remove product.',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Product removed successfully.',
        ], 200);
    }
}