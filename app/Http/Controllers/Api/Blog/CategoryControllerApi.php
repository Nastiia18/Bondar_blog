<?php
namespace App\Http\Controllers\Api\Blog;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class CategoryControllerApi extends Controller
{
    // public function index1()
    // {
    //     $categories = BlogCategory::all();
    //     return response()->json($categories);
    // }

    public function index()
    {
        $categories = BlogCategory::with('parentCategory')->get()->map(function ($category) {
            return [
                'id' => $category->id,
                'title' => $category->title,
                'slug' => $category->slug,
                'parent_id' => $category->parent_id,
                'description' => $category->description,
                'parent_title' => $category->parent_title, // Accessor for parent category title
                'created_at' => $category->created_at,
                'updated_at' => $category->updated_at,
                'deleted_at' => $category->deleted_at,
            ];
        });

        return response()->json($categories);
    }
   
    // Отримати конкретну категорію за ID
    public function show($id)
    {
        $category = BlogCategory::findOrFail($id);
        return response()->json($category);
    }


    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'slug' => 'required|string|max:255|unique:blog_categories',
                'parent_id' => 'nullable|integer|exists:blog_categories,id',
                'description' => 'nullable|string',
            ]);

            $category = BlogCategory::create($validatedData);

            return response()->json($category, 201);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Error creating category: ' . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }


    public function rules(): array
    {
        return [
            //
            'title' => 'required|min:5|max:200',
            'slug' => 'max:200',
            'description' => 'string|max:500|min:3',
            'parent_id' => 'required|integer|exists:blog_categories,id',
        ];
    }
    public function create()
    {
        //dd(METHOD);
        $item = new BlogCategory();
        $categoryList = $this->blogCategoryRepository->getForComboBox(); //BlogCategory::all();

        return view('blog.admin.categories.edit', compact('item', 'categoryList'));
    }


public function update(Request $request, $id)
{
    $category = BlogCategory::findOrFail($id);

    $validatedData = $request->validate([
        'title' => 'required|string|max:255',
        'slug' => 'required|string|max:255|unique:blog_categories,slug,' . $id,
        'parent_id' => 'nullable|integer|exists:blog_categories,id',
        'description' => 'nullable|string',
    ]);

    $category->update($validatedData);

    return response()->json($category);
}

public function edit($id)
{
    $category = BlogCategory::findOrFail($id);
    return view('admin.blog.categories.edit', compact('category'));
}


public function destroy($id)
{
    $category = BlogCategory::findOrFail($id);

    // Check if the category has child categories or posts associated
    if ($category->children()->exists() || $category->posts()->exists()) {
        return response()->json([
            'message' => 'Cannot delete category. It has child categories or posts associated.'
        ], 400);
    }

    $category->delete();

    return response()->json([
        'message' => 'Category deleted successfully'
    ]);

    
}
}