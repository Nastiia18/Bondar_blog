
<?php
use App\Http\Controllers\RestTestController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DiggingDeeperController;
use App\Http\Controllers\Api\Blog\PostController; 
use App\Http\Controllers\Api\Blog\CategoryControllerApi;


Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
// створює маршрути для CRUD операцій з ресурсом rest
Route::resource('rest', RestTestController::class)->names('restTest');

//всі маршрути, які ведуть до методів контролера PostController
Route::group([ 'namespace' => 'App\Http\Controllers\Blog', 'prefix' => 'blog'], function () {
    Route::resource('posts', App\Http\Controllers\Blog\Admin\PostController::class)->names('blog.posts');
});

Route::group(['prefix' => 'digging_deeper'], function () {
   
    Route::get('process-video', 'App\Http\Controllers\DiggingDeeperController@processVideo')
    ->name('digging_deeper.processVideo');
    
    Route::get('prepare-catalog', 'App\Http\Controllers\DiggingDeeperController@prepareCatalog')
    ->name('digging_deeper.prepareCatalog'); 


    Route::get('collections', [App\Http\Controllers\DiggingDeeperController::class, 'collections'])

        ->name('digging_deeper.collections');

});
//Адмінка
$groupData = [
    'namespace' => 'App\Http\Controllers\Blog\Admin',
    'prefix' => 'admin/blog',
];
Route::group($groupData, function () {
    //BlogCategory
    $methods = ['index','edit','store','update','create',];
    Route::resource('categories', CategoryController::class)
    ->only($methods)
    ->names('blog.admin.categories'); 

    //BlogPost
    Route::resource('posts', App\Http\Controllers\Blog\Admin\PostController::class)
    ->except(['show'])                               //не робити маршрут для метода show
    ->names('blog.admin.posts');
 });

 
// Додаємо маршрут для API запиту посту
Route::get('api/blog/posts', [App\Http\Controllers\Api\Blog\PostController::class, 'index']);
Route::get('api/blog/post/{id}', [App\Http\Controllers\Api\Blog\PostController::class, 'getPostById']);


// Додаємо маршрути для категорій

Route::get('api/blog/categories', [App\Http\Controllers\Api\Blog\CategoryControllerApi::class, 'index']);
Route::post('api/blog/categories', [App\Http\Controllers\Api\Blog\CategoryControllerApi::class, 'store']); // Додавання категорії
Route::get('api/blog/categories/{id}', [App\Http\Controllers\Api\Blog\CategoryControllerApi::class, 'getCategoryById']);



