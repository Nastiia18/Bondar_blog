<?php

namespace App\Http\Controllers\Api\Blog;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
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
}
