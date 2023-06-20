<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function allCategory()
    {
        $categories = Category::latest()->get();
        return view('backend.category.category_all', compact('categories'));
    }

    public function addCategory()
    {
        return view('backend.category.category_add');
    }

    public function storeCategory(Request $request)
    {
        Category::insert([
           'category_name' => $request->category_name,
           'category_slug' => strtolower(str_replace(' ', '-', $request->category_name)),
         ]);


        $notification = array(
           'message' => 'Category Inserted Successfully',
           'alert-type' => 'success'

         );

        return redirect()->route('all.category')->with($notification);
    }

    public function editCategory($id)
    {
        $category = Category::findOrFail($id);
        return view('backend.category.category_edit', compact('category'));
    }

    public function updateCategory(Request $request)
    {

        $cat_id = $request->id;

        Category::findOrFail($cat_id)->update([
            'category_name' => $request->category_name,
            'category_slug' => strtolower(str_replace(' ', '-', $request->category_name)),

        ]);


        $notification = array(
           'message' => 'Category Updated Successfully',
           'alert-type' => 'success'

      );

        return redirect()->route('all.category')->with($notification);
    }

    public function allSubCategory()
    {
        $subcategories = SubCategory::latest()->get();
        return view('backend.subcategory.subcategory_all', compact('subcategories'));
    }

    public function addSubCategory()
    {
        $categories = Category::latest()->get();
        return view('backend.subcategory.subcategory_add', compact('categories'));
    }

    public function storeSubCategory(Request $request)
    {
        SubCategory::insert([
           'category_id' => $request->category_id,
           'subcategory_name' => $request->subcategory_name,
           'subcategory_slug' => strtolower(str_replace(' ', '-', $request->subcategory_name)),

         ]);


        $notification = array(
           'message' => 'SubCategory Inserted Successfully',
           'alert-type' => 'success'

         );

        return redirect()->route('all.subcategory')->with($notification);
    }

    public function editSubCategory($id)
    {
        $categories = Category::latest()->get();
        $subcategory = SubCategory::findOrFail($id);
        return view('backend.subcategory.subcategory_edit', compact('categories', 'subcategory'));
    }

    public function updateSubCategory(Request $request)
    {
        $subcat_id = $request->id;

        SubCategory::findOrFail($subcat_id)->update([
            'category_id' => $request->category_id,
            'subcategory_name' => $request->subcategory_name,
            'subcategory_slug' => strtolower(str_replace(' ', '-', $request->subcategory_name)),

        ]);


        $notification = array(
           'message' => 'SubCategory Updated Successfully',
           'alert-type' => 'success'

      );

        return redirect()->route('all.subcategory')->with($notification);
    }

    public function deleteSubCategory($id)
    {
        SubCategory::findOrFail($id)->delete();

        $notification = array(
           'message' => 'SubCategory Deleted Successfully',
           'alert-type' => 'success'

     );

        return redirect()->back()->with($notification);
    }


    public function getSubCategory($category_id)
    {

        $subcat = Subcategory::where('category_id', $category_id)->orderBy('subcategory_name', 'ASC')->get();
        return json_encode($subcat);

    }
}