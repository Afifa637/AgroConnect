<?php

use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\RegisterLoginCheckController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\FarmerController;
use App\Http\Controllers\Farm_CropController;
use App\Http\Controllers\BidController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\SslCommerzPaymentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ================= Admin Routes =================

// Admin Login & Signup
Route::controller(AdminLoginController::class)->group(function () {
    Route::get('/admin/login', 'a_login')->name('a_login');
    Route::post('admin/login/check', 'admin_login_check')->name('admin_login_check');
    Route::post('/admin_pw_change_link', 'admin_pw_change_link')->name('admin_pw_change_link');
    Route::get('/admin_pw_change/{email}', 'admin_pw_change')->name('admin_pw_change');
    Route::post('/admin_pass_change_save/{email}', 'admin_pass_change_save')->name('admin_pass_change_save');

    Route::middleware('a_check')->group(function () {
        Route::get('/admin/signup', 'admin_signup')->name('admin_signup');
        Route::post('admin/signup/save', 'admin_registerSave')->name('admin_registerSave');
        Route::get('/account_verify/{username}', 'admin_account_verify')->name('admin_account_verify');
        Route::post('/admin/registerUpdate', 'adminregisterUpdate')->name('adminregisterUpdate');
    });
});

// Admin Home & Management
Route::middleware('a_check')->controller(AdminController::class)->group(function () {
    Route::get('/admin/home', 'a_home')->name('a_home');

    // Crops
    Route::get('/published/crop', 'published_crops')->name('published_crops');
    Route::get('/crop/unpublished/{id}', 'crop_unpublished_save')->name('crop_unpublished_save');
    Route::get('/unpublished/crop', 'unpublished_crops')->name('unpublished_crops');
    Route::get('/crop/published/{id}', 'crop_published_save')->name('crop_published_save');
    Route::get('/deleted/crop', 'deleted_crops')->name('deleted_crops');
    Route::get('/crop/deleted/{id}', 'crop_delete')->name('crop_delete');

    // Categories
    Route::get('/add/Categories', 'add_categories')->name('add_categories');
    Route::post('/categories/save', 'save_categories_db')->name('save_categories_db');
    Route::get('/manage/categories', 'manage_categories')->name('manage_categories');
    Route::get('/change/categories/status/{id}', 'categories_status')->name('categories_status');
    Route::get('/edit/categories/{id}', 'edit_categories')->name('edit_categories');
    Route::post('/categories/update', 'update_categories_db')->name('update_categories_db');
    Route::get('/categories/delete/{id}', 'categories_delete')->name('categories_delete');

    // News
    Route::get('/add/news', 'add_news')->name('add_news');
    Route::post('/news/save', 'save_news_db')->name('save_news_db');
    Route::get('/manage/news', 'manage_news')->name('manage_news');
    Route::get('/edit/news/{id}', 'edit_news')->name('edit_news');
    Route::post('/news/update', 'update_news_db')->name('update_news_db');
    Route::get('/delete/news/{id}', 'delete_news')->name('delete_news');

    // Admin Profile & Settings
    Route::get('/admin/profile', 'a_profile')->name('a_profile');
    Route::get('/admin/settings', 'a_settings')->name('a_settings');

    // Users
    Route::get('/farmers', 'all_farmer')->name('all_farmer');
    Route::get('/customers', 'all_customer')->name('all_customer');
    Route::get('/farmer/{id}', 'f_action')->name('f_action');
    Route::get('/customer/{id}', 'c_action')->name('c_action');
    Route::get('farmerr/profile/{id}', 'farmer_profile')->name('farmer_profile');
    Route::get('user/profile/{id}', 'user_profile')->name('user_profile');
    Route::get('user/details/{id}', 'user_details')->name('user_details');
    Route::get('/admin/search', 'admin_search')->name('admin_search');
});

// ================= Home Routes =================
Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index')->name('home');
    Route::get('/about', 'about')->name('about');
    Route::get('/services', 'services')->name('services');
    Route::get('/contact', 'contact')->name('contact');
    Route::get('/gallery', 'gallery')->name('gallery');
    Route::get('/news_info', 'news_info')->name('news_info');
    Route::get('/categories/{crop_type}', 'Categories')->name('Categories');
    Route::get('/sessions/categories/{crop_type}/{crop_session}', 'Session_Categories')->name('Session_Categories');
    Route::get('/crop_details/{id}', 'crop_details')->name('crop_details');
    Route::get('/search', 'search')->name('search');
});

// Buyer Routes
Route::controller(BuyerController::class)->group(function () {
    Route::get('/customer/profile/{c_username}', 'cust_profile')->name('cust_profile');
    Route::get('/confirm/message', 'c_message')->name('c_message');
    Route::get('/customer', 'c_settings')->name('c_settings');
    Route::get('/farmer/profile/check/{f_username}', 'farm_profile')->name('farm_profile');
});

// Register & Login Routes
Route::controller(RegisterLoginCheckController::class)->group(function () {
    Route::get('/login', 'login')->name('login');
    Route::post('login/check', 'login_check')->name('login_check');
    Route::get('/signup', 'signup')->name('signup');
    Route::post('signup/save', 'registerSave')->name('registerSave');
    Route::get('/account_verify/{username}/{uses_as}', 'account_verify')->name('account_verify');
    Route::post('/pw_change_link', 'pw_change_link')->name('pw_change_link');
    Route::get('/pw_change/{uses}/{email}', 'pw_change')->name('pw_change');
    Route::post('/pass_change_save/{uses_as}/{email}', 'pass_change_save')->name('pass_change_save');
    Route::post('/farmer/registerUpdate', 'farmerRegisterUpdate')->name('farmerRegisterUpdate');
    Route::post('/customer/registerUpdate', 'customerRegisterUpdate')->name('customerRegisterUpdate');
});

// Wishlist Routes
Route::controller(WishlistController::class)->group(function () {
    Route::get('/customer/wishlist/save/{id}', 'wishlist_db')->name('wishlist_db');
    Route::get('/customer/wishlist/{c_username}', 'wishlist')->name('wishlist');
    Route::get('/wishlist/remove/{id}', 'wishlist_remove')->name('wishlist_remove');
});

// ================= Farmer Routes =================
Route::middleware('f_check')->group(function () {
    // Farmer Home & Profile
    Route::controller(FarmerController::class)->group(function () {
        Route::get('/farmer/home/page', 'f_home')->name('f_home');
        Route::get('/farmer/bid/messages', 'farm_bid_messages')->name('farm_bid_messages');
        Route::get('/farmer/confirm/form/{id}', 'confirm_form')->name('confirm_form');
        Route::get('/confirm/crops', 'confirm_crops')->name('confirm_crops');
        Route::get('/confirm/delete/{id}', 'delete_confirm')->name('delete_confirm');
        Route::get('/farmer/profile/{f_username}', 'fa_profile')->name('fa_profile');
        Route::get('/farmer/', 'f_settings')->name('f_settings');
        Route::get('customer/details/{username}', 'customer_profile')->name('customer_profile');
    });

    // Farmer Crop Management
    Route::controller(Farm_CropController::class)->group(function () {
        Route::get('/crop/import', 'crop_import')->name('crop_import');
        Route::post('/crop/add/save', 'add_product_db')->name('add_product_db');
        Route::get('/crop/manage', 'crop_manage')->name('crop_manage');
        Route::get('/crop/edit/{id}', 'edit_crop')->name('edit_crop');
        Route::post('/crop/update/save', 'update_product_db')->name('update_product_db');
        Route::get('/crop/condition/{id}', 'condition_crop')->name('condition_crop');
        Route::get('/crop/delete/{id}', 'delete_crop')->name('delete_crop');
    });
});

// Farmer NID Verification & Logout
Route::controller(FarmerController::class)->group(function () {
    Route::post('/NID_verification', 'NID_verification')->name('NID_verification');
    Route::get('/logout/{name}', 'logout')->name('logout');
});

// ================= Bid Routes =================
Route::controller(BidController::class)->group(function () {
    Route::get('/bid/model/{id}', 'Bid_model')->name('Bid_model');
    Route::post('/Bid/message', 'bid_msg_save')->name('bid_msg_save');
    Route::post('/Bid/message/save', 'bid_msg_saved')->name('bid_msg_saved');
    Route::get('/bid/delete/{id}/{crop_id}', 'bid_delete')->name('bid_delete');
    Route::post('/pay/confirm/message', 'pay_confirm_message')->name('pay_confirm_message');
});

// ================= Order Routes =================
Route::controller(OrderController::class)->group(function () {
    Route::get('/order/paymet/form/{id}', 'payment_form')->name('payment_form');
    Route::post('/payment/manually', 'manually_payment')->name('manually_payment');
    Route::get('/farmer/order/messages', 'farm_order_messages')->name('farm_order_messages');
    Route::get('/customer/order/messages', 'cust_order_messages')->name('cust_order_messages');
});

// ================= Invoice Routes =================
Route::controller(InvoiceController::class)->group(function () {
    Route::get('/bid_details/download/invoices/{id}', 'bids_download_invoice')->name('bids_download_invoice');
    Route::get('/Pay_Confirm/download/invoice/{id}', 'pay_confirm_download_invoice')->name('pay_confirm_download_invoice');
    Route::get('/invoice/order/{id}', 'order_download_invoice')->name('order_download_invoice');
});

// ================= SSLCOMMERZ Routes =================
Route::controller(SslCommerzPaymentController::class)->group(function () {
    Route::get('/example2/{id}/{crop_id}', 'exampleHostedCheckout')->name('example2');
    Route::post('/pay', 'index');
    Route::post('/success', 'success');
    Route::post('/fail', 'fail');
    Route::post('/cancel', 'cancel');
    Route::post('/ipn', 'ipn');
});
