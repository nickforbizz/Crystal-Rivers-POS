<?php

use App\Events\UserRegistered;
use App\Http\Controllers\cms\AssignRoleController;
use App\Http\Controllers\cms\CustomerController;
use App\Http\Controllers\cms\NotificationController;
use App\Http\Controllers\cms\PermissionController;
use App\Http\Controllers\cms\UserController;
use App\Http\Controllers\cms\PostCategoryController;
use App\Http\Controllers\cms\PostController;
use App\Http\Controllers\cms\ProductCategoryController;
use App\Http\Controllers\cms\ProductController;
use App\Http\Controllers\cms\ReportController;
use App\Http\Controllers\cms\RoleController;
use App\Http\Controllers\cms\SearchController;
use App\Http\Controllers\frontend\ViewsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\cms\OrderController;
use App\Http\Controllers\cms\OrderItemController;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/test', function () {
    // $admins = Role::whereIn('name', ['admin', 'superadmin'])
    // ->with('users')->get();
    $users = User::whereHas('roles', function ($query) {
        $query->whereIn('name', ['admin', 'superadmin']);
    })->get();
    return $users;
    // return what you want
});



Route::get('/optimize', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    Artisan::call('optimize');
    Artisan::call('storage:link');
    Artisan::call('composer dump-autoload');
    return 'done';
});

Route::get('/flush-perms', function () {
    Artisan::call('permission:cache-reset');
    return "Flushing Complete";
});

Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');


// Route::get('/', function () {
//     return view('welcome');
// });


// Frontend Views
Route::get('/', [ViewsController::class, 'index'])->name('wellcome');
Route::get('/about', [ViewsController::class, 'about'])->name('about');
Route::get('/blog/{id}', [ViewsController::class, 'getPost'])->name('blog');
Route::get('/blogs', [ViewsController::class, 'posts'])->name('blogs');
Route::prefix('web')->group(function () {

});

// Backend/CMS
Route::middleware('cms')->group(function () {

    Route::get('/home', [HomeController::class, 'cms'])->name('home');
    Route::get('/cms', [HomeController::class, 'cms'])->name('cms');
    Route::get('/search', [SearchController::class, 'search'])->name('search');


    // Downloadable Reports
    Route::get('reports/download/csv', [ReportController::class, 'downloadCsv'])->name('reports.download.csv');



    // Resources Routes
    Route::resources([
        'users' => UserController::class,
        'posts' => PostController::class,
        'postCategories' => PostCategoryController::class,
        'customers' => CustomerController::class,
        'productCategories' => ProductCategoryController::class,
        'products' => ProductController::class,
        'orders' => OrderController::class,
        'orderItems' => OrderItemController::class,
        'roles' => RoleController::class,
        'permissions' => PermissionController::class,
        'assignRoles' => AssignRoleController::class,
        'reports' => ReportController::class,
        'notifications' => NotificationController::class,
    ]);

    Route::get('orders/invoice/{order}', [OrderController::class, 'invoice'])->name('orders.invoice');

    // Transactions
    Route::get('orders/{order}/transactions/create', [TransactionController::class, 'create'])->name('transactions.create');
    Route::post('orders/{order}/transactions', [TransactionController::class, 'store'])->name('transactions.store');

    // CART Routes
    Route::get('cart', [ProductController::class, 'cart'])->name('cart');
    Route::get('add-to-cart/{id}', [ProductController::class, 'addToCart'])->name('addToCart');
    Route::patch('update-cart', [ProductController::class, 'updateCart'])->name('updateCart');
    Route::delete('remove-from-cart', [ProductController::class, 'removeCartItem'])->name('removeCartItem');

    Route::post('/notifications//mark-as-read', [NotificationController::class, 'markNotification'])->name('notifications.markNotification');
});


