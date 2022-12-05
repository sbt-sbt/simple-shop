<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
//
//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
Route::get('/healthcheck', function (Request $request) {
    return [
        'status' => 'up',
        'services' => [
            'database' => 'up',
            'redis' => 'up',
            'r'=>$request->all()
        ],
    ];
});

//Route::group([], function ($router) {
//    $router->get('users',[\App\Http\Controllers\UserController::class,'index']);
//
//});
Route::prefix('administrator')->group(function (){
    Route::resource('categories',\App\Http\Controllers\Admin\CategoryController::class);
    Route::get('categories/{id}/settings',[\App\Http\Controllers\Admin\CategoryController::class,'getCategoryAttributes'])->name('get.CategoryAttributes');
    Route::post('categories/{id}/settings',[\App\Http\Controllers\Admin\CategoryController::class,'setCategoryAttributes'])->name('set.CategoryAttributes');
    Route::post('register',[\App\Http\Controllers\Auth\AuthController::class,'register'])->name('register');
    Route::post('login',[\App\Http\Controllers\Auth\AuthController::class,'login'])->name('login');
    Route::resource('attributes-group',\App\Http\Controllers\Admin\AttributesGroupController::class);
    Route::resource('attributes-value',\App\Http\Controllers\Admin\AttributesValueController::class);
    Route::resource('brands',\App\Http\Controllers\Admin\BrandController::class);
    Route::resource('photos',\App\Http\Controllers\PhotoController::class);
    Route::resource('products',\App\Http\Controllers\Admin\ProductController::class);
    Route::resource('coupons',\App\Http\Controllers\Admin\CouponController::class);

    Route::get('user', [\App\Http\Controllers\UserController::class,'index'])->middleware('auth:sanctum');
    Route::get('cities/{id}', [\App\Http\Controllers\Auth\AuthController::class,'getAllCities'])->name('province.cities');
    Route::get('/logout', function (){
        \request()->user()->tokens()->delete();
        return response([
            ['message'=>'goodby']
        ], 204);
    })->middleware('auth:sanctum');

    Route::post('coupon',[\App\Http\Controllers\CouponController::class,'addCoupon'])->name('addCoupon');
    Route::get('orders',[\App\Http\Controllers\OrderController::class,'index'])->name('index.orders');
    Route::get('orders/{id}',[\App\Http\Controllers\OrderController::class,'orderDetails'])->name('order.details');
    Route::get('verify/{status}',[\App\Http\Controllers\OrderController::class,'verify'])->name('verify.orders');
//    Route::get('payment-verify',[\App\Http\Controllers\OrderController::class,'verify'])->name('verify.payment');

});
Route::post('add-to-cart/{id}',[\App\Http\Controllers\CartController::class,'addToCart'])->name('addToCart');
Route::post('remove-item/{id}',[\App\Http\Controllers\CartController::class,'removeItem'])->name('removeItem');
Route::get('product/{slug}',[\App\Http\Controllers\ProductController::class,'getProductBySlug'])->name('product.show');
Route::get('products/category/{id}/{page?}',[\App\Http\Controllers\ProductController::class,'getProductsByCategory'])->name('products.category');
Route::get('products/brand/{id}/{page?}',[\App\Http\Controllers\ProductController::class,'getProductsByBrand'])->name('products.brand');
Route::get('products/sort/{categoryId}/{sort}/{page?}',[\App\Http\Controllers\ProductController::class,'getSortedProducts'])->name('products.sorted');
Route::get('category/attributes/{categoryId}',[\App\Http\Controllers\ProductController::class,'getAttributes'])->name('category.attributes');
Route::get('products/attributes/{categoryId}/{sort}/{attributes}/{page?}',[\App\Http\Controllers\ProductController::class,'filterProductByAttributes'])->name('products.attributes');

