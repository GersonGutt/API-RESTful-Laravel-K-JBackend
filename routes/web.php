<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\FallbackController;
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

Route::get('/', function () {
    return view('welcome');
});


//Route::get('/blog/{name}', [PostController::class, 'show'])
//->whereAlpha('name');
//Route::get('/blog/{id}/{name}', [PostController::class, 'show'])
//->whereNumber('id')
//->whereAlpha('name');
Route::prefix('/blog')->group(function () {
    Route::get('/edit/{id}', [PostController::class, 'edit'])->name('blog.edit');
    Route::get('/create', [PostController::class, 'create'])->name('blog.create');
    Route::get('/', [PostController::class, 'index'])->name('blog.index');
    Route::get('/{id}', [PostController::class, 'show'])->name('blog.show');


    Route::post('/', [PostController::class, 'store'])->name('blog.store');


    Route::patch('/{id}', [PostController::class, 'update'])->name('blog.update');

    Route::delete('/{id}', [PostController::class, 'destroy'])->name('blog.destroy');

    Route::post('login', [UserController::class, 'login']);


});



//Route::fallback(FallbackController::class);
