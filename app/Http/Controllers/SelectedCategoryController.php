<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\SelectedCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class SelectedCategoryController extends Controller
{
    public function index()
    {
        $categories = Category::where('status', '1')->orderBy('priority', 'asc')->get();
        $selectedCategories = SelectedCategory::orderBy('priority', 'asc')->get();
        return view('backend.selectedCategory.index', compact(
            'categories',
            'selectedCategories',
        ));
    }


    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|unique:selected_categories,category_id',
        ]);

        $maxPriority = SelectedCategory::max('priority') ?? 0;
        $priority = $maxPriority + 1;

        SelectedCategory::create([
            'category_id' => $request->category,
            'priority' => $priority,
        ]);


        return redirect()->route('admin.selectedCategory.index')->with('success', 'Category Selected successfully.');
    }


    public function destroy(string $id)
    {
        $selectedCategory = SelectedCategory::findOrFail($id);

        try {
            $selectedCategory->delete();
        } catch (\Exception $e) {
            Log::error($e);
            return error($e->getMessage());
        }

        return response()->json(['success' => true, 'message' => 'Selected Category Deleted Successfully'], 200);
    }

    public function updateStatus($id)
    {
        $selectedCategory = SelectedCategory::find($id);
        try {
            // Update Category status
            $selectedCategory->update([
                'status' => $selectedCategory->status == '1' ? '0' : '1',
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            return error($e->getMessage());
        }

        return response()->json(['success' => true, 'message' => 'Status Updated Successfully'], 200);
    }

    public function updateAjax(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'priority' => 'required|integer',
        ]);

        // Return the validation errors
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $selectedCategory = SelectedCategory::findOrFail($request->id);

        // Update the subcategory
        $selectedCategory->update([
            'priority' => $request->priority,
        ]);

        Session::flash('success', 'Priority updated successfully');

        return response()->json([
            'success' => true
        ]);
    }
}