<?php

namespace App\Http\Controllers\Api\Blog;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\BlogCategory; 
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        // Отримуємо всі пости разом з користувачами та категоріями
        $posts = BlogPost::with(['user', 'category'])->get();

        // Повертаємо пости у вигляді JSON
        return response()->json($posts);
    }

    public function getPostById($id) 
    {
        // Отримуємо пост за його ID разом з користувачами та категоріями
        $post = BlogPost::with(['user', 'category'])->findOrFail($id);

        // Повертаємо пост у вигляді JSON
        return response()->json($post);
    }

     // Методи для категорій
    
     public function indexCategories()
     {
         // Отримуємо всі категорії
         $categories = BlogCategory::all();
 
         // Повертаємо категорії у вигляді JSON
         return response()->json($categories);
     }
 
     public function getCategoryById($id)
     {
         // Отримуємо категорію за її ID
         $category = BlogCategory::findOrFail($id);
 
         // Повертаємо категорію у вигляді JSON
         return response()->json($category);
     }
}



