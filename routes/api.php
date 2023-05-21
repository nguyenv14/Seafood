<?php

use App\Http\Controllers\APICategoryProduct;
use App\Http\Controllers\APICheckOutController;
use App\Http\Controllers\APICommentController;
use App\Http\Controllers\APIDeliveryController;
use App\Http\Controllers\APIProductController;
use App\Http\Controllers\APISliderProduct;
use App\Http\Controllers\APIUserController;
use App\Http\Controllers\RepositoryAPIController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/* Đặc Điểm Của API Là Không Xử Lý Được Session -> Cách Xử Lý Là Viết Bên Route Chứ Bên Này Không Được */
/* Mặc định là api/admin/category/all-category */
/* Các Bảo Mật API Được Sử Dụng Json Web Token JWT Tìm Hiểu Thêm Để Bảo Mật API */

Route::get('web/coupon', [APIProductController::class, 'get_coupon']);
Route::get('web/user/all-user', [APIUserController::class, 'all_user']);
Route::post('web/user/create-user', [APIUserController::class, 'create_customer']);
// User
Route::post('android/check-login', [APIUserController::class, 'logIn']);
Route::post('android/update-user-name', [APIUserController::class, 'updateName']);  
Route::post('android/update-user-phone', [APIUserController::class, 'updatePhone']);
Route::post('android/update-user-email', [APIUserController::class, 'updateEmail']);
Route::post('android/update-user-pass', [APIUserController::class, 'updatePass']);

Route::post('android/change-user-pass', [APIUserController::class, 'changePass']);
Route::post('android/send-code-email-customer', [APIUserController::class, 'sendCodeEmailCustomer']);
Route::post('android/send-code-change-pass', [APIUserController::class, 'sendCodeChangePass']);

Route::post('android/sign-up-customer', [APIUserController::class, 'create_customer']);

// Comment
Route::post('android/get-evaluate-product', [APICommentController::class, 'getCommentProduct']);
Route::get('android/get-evaluate-order', [APICommentController::class, 'getEvaluateOrderCode']);
Route::post('android/insert-evaluate-order', [APICommentController::class, 'insertEvaluateToOrder']);

// Product
Route::GET('android/get-product', 'App\Http\Controllers\APIProductController@getProduct');
Route::GET('android/get-new-product', 'App\Http\Controllers\APIProductController@getNewProduct');
Route::GET('android/get-trending-product', 'App\Http\Controllers\APIProductController@getTrendingProduct');
Route::GET('android/get-order-product', 'App\Http\Controllers\APIProductController@getOrderProduct');
Route::POST('android/get-product-by-category', 'App\Http\Controllers\APIProductController@getProductByCategory');
Route::POST('android/get-gallery-product', 'App\Http\Controllers\APIProductController@getGalleryProduct');
Route::POST('android/get-evaluate-product', 'App\Http\Controllers\APIProductController@getEvaluateProduct');
Route::GET('android/get-category', 'App\Http\Controllers\APICategoryProduct@getCategory');

Route::post('android/get-product-by-categoryId', [APIProductController::class, 'getProductByCategoryId']);

// Search product
Route::post('android/get-product-by-search', [APIProductController::class, 'getProductBySearch']);
Route::get('android/get-price-min-and-max', [APIProductController::class, 'getPriceMinPriceMax']);

// Slider
Route::get('android/get-slider', [APISliderProduct::class, 'all_slider']);

// Order
Route::GET('android/get-order',[APICategoryProduct::class, 'getOrder']);
Route::GET('android/get-evaluate-order',[APICategoryProduct::class, 'getEvaluateOrder']);
Route::GET('android/get-order-details', [APICategoryProduct::class, 'getOrderDetails']);
Route::POST('android/order-cancel', [APICategoryProduct::class, 'orderCancel']);
Route::POST('android/order-receive', [APICategoryProduct::class, 'orderReceive']);
Route::GET('android/search-order', [APICategoryProduct::class, 'searchOrder']);

// Address
Route::get('android/get-city', 'App\Http\Controllers\RepositoryAPIController@getDataCity');
Route::get('android/get-province', 'App\Http\Controllers\RepositoryAPIController@getDataProvince');
Route::get('android/get-wards', 'App\Http\Controllers\RepositoryAPIController@getDataWards');

// checkout
Route::get('android/check-coupon', 'App\Http\Controllers\RepositoryAPIController@checkCoupon');
Route::get('android/get-delivering-fee', 'App\Http\Controllers\RepositoryAPIController@getDeliveringFee');
Route::post('android/put-order', 'App\Http\Controllers\RepositoryAPIController@putOrder');
Route::post('android/delete-order', 'App\Http\Controllers\RepositoryAPIController@deleteOrder');