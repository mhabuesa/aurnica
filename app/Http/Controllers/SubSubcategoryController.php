<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Subcategory;
use App\Models\SubSubcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SubSubcategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('priority', 'asc')->get();
        $subsubcategories = SubSubcategory::with(['category', 'subcategory'])
            ->orderBy('priority', 'asc')
            ->get();

        // Validation error hole ager selected category er subcategories load rakhar jonno
        $subcategories = collect();
        if (old('category')) {
            $subcategories = Subcategory::where('category_id', old('category'))
                ->orderBy('name', 'asc')
                ->get();
        }

        return view('backend.subsubcategory.index', compact('categories', 'subcategories', 'subsubcategories'));
    }

    // AJAX: Category select korle oi category-r subcategories return korbe
    public function getSubcategories($category_id)
    {
        $subcategories = Subcategory::where('category_id', $category_id)
            ->orderBy('name', 'asc')
            ->get(['id', 'name']);

        return response()->json($subcategories);
    }

    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|exists:categories,id',
            'subcategory' => 'required|exists:subcategories,id',
            'subcategory_name' => 'required|string|max:255|unique:subsubcategories,name',
        ]);

        $slug = Str::slug($request->subcategory_name);
        $priority = (SubSubcategory::max('priority') ?? 0) + 1;

        SubSubcategory::create([
            'category_id' => $request->category,
            'subcategory_id' => $request->subcategory,
            'name' => $request->subcategory_name,
            'slug' => $slug,
            'priority' => $priority,
            'status' => 1,
        ]);

        return redirect()->route('admin.subsubcategory.index')
            ->with('success', 'Sub-subcategory added successfully.');
    }

    public function updateAjax(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:subsubcategories,id',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:subcategories,id',
            'name' => 'required|string|max:255|unique:subsubcategories,name,' . $request->id,
            'slug' => 'required|string|max:255|unique:subsubcategories,slug,' . $request->id,
            'priority' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ]);
        }

        $subsubcategory = SubSubcategory::findOrFail($request->id);
        $subsubcategory->update([
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'name' => $request->name,
            'slug' => $request->slug,
            'priority' => $request->priority,
        ]);

        Session::flash('success', 'Sub-subcategory updated successfully.');

        return response()->json([
            'success' => true,
        ]);
    }

    public function destroy(string $id)
    {
        $subsubcategory = SubSubcategory::findOrFail($id);

        try {
            $subsubcategory->delete();
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Sub-subcategory deleted successfully.',
        ], 200);
    }

    public function updateStatus($id)
    {
        $subsubcategory = SubSubcategory::findOrFail($id);

        try {
            $subsubcategory->update([
                'status' => $subsubcategory->status == '1' ? '0' : '1',
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Sub-subcategory status updated successfully.',
        ], 200);
    }
}