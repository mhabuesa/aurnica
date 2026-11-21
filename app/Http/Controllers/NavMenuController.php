<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\NavCategory;
use App\Models\Subcategory;
use Illuminate\Http\Request;

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
        $navData = NavCategory::orderBy('priority', 'asc')->get();
        return view('backend.navmenu.index', compact('categories', 'subcategories','navData'));
    }

    public function destroy(string $id)
    {
        $data = NavCategory::findOrFail($id);

        try {
            $data->delete();
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Deleted successfully.',
        ], 200);
    }

    public function updateStatus($id)
    {
        $data = NavCategory::findOrFail($id);

        try {
            $data->update([
                'status' => $data->status == '1' ? '0' : '1',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully.',
        ], 200);
    }
}