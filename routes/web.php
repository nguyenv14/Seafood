<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryProduct;
use App\Http\Controllers\CheckOutController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CompanyConfigController;
use App\Http\Controllers\ConfigWebController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\FlashsaleController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginAndRegister;
use App\Http\Controllers\ManipulationActivityController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\MyOrderController;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;

/* Back-end */
/* Admin Auth Login */
Route::group(['prefix' => 'admin/auth'], function () {
    Route::get('/register', [AuthController::class, 'show_register'])->middleware('admin.roles');
    Route::post('/registration-processing', [AuthController::class, 'registration_processing'])->middleware('admin.roles');
    Route::get('/login', [AuthController::class, 'show_login']);
    Route::post('/login-processing', [AuthController::class, 'login_processing']);
    Route::get('/logout', [AuthController::class, 'logout']);
});

/* Bảo Vệ Toàn Bộ URL Tránh Bị Vượt */
Route::group(['middleware' => 'ProtectAuthLogin'], function () {

    Route::group(['prefix' => 'admin'], function () {
        Route::get('/', [DashboardController::class, 'show_dashboard']);
        Route::get('/dashboard', [DashboardController::class, 'show_dashboard']);
        Route::get('/dashboard/filter-doanh-thu',  [DashBoardController::class, 'filter_doanh_thu']);
        Route::get('/dashboard/doanh-thu-five-day',  [DashBoardController::class, 'doanh_thu_five_day']);

    });

    /* Dashboard */
    Route::group(['prefix' => 'admin/dashboard'], function () {
        Route::get('/notification', [DashboardController::class, 'dashboard_notification']);
        Route::get('/count-admin-online', [DashboardController::class, 'admin_online']);
        Route::get('/count-customer-online', [DashboardController::class, 'customer_visit']);
        Route::get('/count-order-today', [DashboardController::class, 'today_order']);
        Route::get('/aboutcarbon', [DashboardController::class, 'aboutCarbon']);
    });
    /* Quản Lý Admin */
    Route::group(['prefix' => 'admin/auth'], function () {
        Route::group(['middleware' => 'admin.roles'], function () {
            Route::get('/load-table-admin', [AdminController::class, 'loading_table_admin']);
            Route::get('/all-admin', [AdminController::class, 'all_admin']);
            Route::POST('/assign-roles', [AdminController::class, 'assign_roles']);

            Route::get('/edit-admin', [AdminController::class, 'edit_admin']);
            Route::POST('/update-admin', [AdminController::class, 'update_admin']);
            Route::get('/all-admin-sreach', [AdminController::class, 'search_all_admin']);
            Route::get('/delete-admin-roles', [AdminController::class, 'delete_admin_roles']);
            Route::get('/impersonate', [AdminController::class, 'impersonate']);
        });
        Route::get('/destroy-impersonate', [AdminController::class, 'destroy_impersonate']);
    });

    /* Quản Lý Khách Hàng */
    Route::group(['prefix' => 'admin/customer'], function () {
        Route::get('/all-customer', [CustomerController::class, 'all_customer']);
        Route::get('/load-all-customer', [CustomerController::class, 'load_all_customer']);
        Route::get('/all-customer-sreach', [CustomerController::class, 'search_all_customer']);
        Route::get('/sort-customer-by-type', [CustomerController::class, 'sort_customer_bytype']);
        Route::get('/update-status-customer', [CustomerController::class, 'update_status_customer']);
        Route::get('/delete-customer', [CustomerController::class, 'delete_customer']);
        Route::get('/list-soft-deleted-customer', [CustomerController::class, 'garbage_can']);
        Route::get('/load-list-soft-deleted', [CustomerController::class, 'load_garbage_can']);

        Route::get('/view-email', [CustomerController::class, 'view_email']);
        Route::get('/selected-email', [CustomerController::class, 'selected_email']);
        Route::get('load-list-mail', [CustomerController::class, 'load_list_mail']);
        Route::POST('/send-email', [CustomerController::class, 'send_email']);
    });

    /* Slider */
    Route::group(['prefix' => 'admin/slider'], function () {
        Route::get('/', [SliderController::class, 'all_slider']);
        Route::get('/add-slider', [SliderController::class, 'add_slider']);
        Route::post('/save-slider', [SliderController::class, 'save_slider']);
        Route::get('/all-slider', [SliderController::class, 'all_slider']);
        Route::get('/edit-slider', [SliderController::class, 'edit_slider']);
        Route::post('/update-slider', [SliderController::class, 'update_slider']);
        Route::get('/delete-slider', [SliderController::class, 'delete_slider'])->middleware('admin.manager.roles');
        Route::get('/active-slider', [SliderController::class, 'active_slider']);
        Route::get('/unactive-slider', [SliderController::class, 'unactive_slider']);
    });

    /* Category Product */
    Route::group(['prefix' => 'admin/category'], function () {
        Route::get('/', [CategoryProduct::class, 'all_category']);
        Route::get('/all-category', [CategoryProduct::class, 'all_category']);

        Route::group(['middleware' => 'admin.manager.roles'], function () {
            Route::get('/add-category', [CategoryProduct::class, 'add_category']);
            Route::get('/edit-category', [CategoryProduct::class, 'edit_category']);
            Route::post('/update-category', [CategoryProduct::class, 'update_category']);
            Route::post('/save-category', [CategoryProduct::class, 'save_category']);
            Route::get('/delete-category', [CategoryProduct::class, 'delete_category']);
            Route::get('/active-category', [CategoryProduct::class, 'active_category']);
            Route::get('/unactive-category', [CategoryProduct::class, 'unactive_category']);
        });
    });

    /* Product */
    Route::group(['prefix' => 'admin/product'], function () {
        /* Product */
        Route::get('/', [ProductController::class, 'all_product']);
        Route::get('/add-product', [ProductController::class, 'add_product']);
        Route::get('/edit-product', [ProductController::class, 'edit_product']);
        Route::post('/update-product', [ProductController::class, 'update_product']);
        Route::get('/all-product', [ProductController::class, 'all_product']);
        Route::post('/save-product', [ProductController::class, 'save_product']);
        Route::get('/delete-product', [ProductController::class, 'delete_product']);
        Route::get('/update-status-product', [ProductController::class, 'update_status_product']);
        Route::get('/all-product-sreach', [ProductController::class, 'all_product_sreach']);
        Route::get('/sort-product-by-category', [ProductController::class, 'sort_product_by_category']);
        Route::get('/sort-all', [ProductController::class, 'sort_all']);
        /* Product Ajax*/
        Route::get('/all-product-ajax', [ProductController::class, 'all_product_ajax']);
        /* Product Details */
        Route::get('/product-details', [ProductController::class, 'product_details']);
        Route::get('/add-product-details', [ProductController::class, 'add_product_details']);
        Route::post('/save-product-details', [ProductController::class, 'save_product_details']);
        Route::get('/edit-product-details', [ProductController::class, 'edit_product_details']);
        Route::post('/update-product-details', [ProductController::class, 'update_product_details']);
        /* SoftDelete Product - Xóa Mềm - Xóa Tạm Thời  */
        Route::get('/list-soft-deleted-product', [ProductController::class, 'list_soft_deleted_product']);
        Route::get('/un-trash', [ProductController::class, 'un_trash']);
        Route::get('/trash-delete', [ProductController::class, 'trash_delete']);
        /* Gallery - Thư Viện Ảnh */
        Route::post('/loading-gallery', [ProductController::class, 'loading_gallery']);
        Route::post('/insert-gallery/{product_id}', [ProductController::class, 'insert_gallery']);
        Route::post('/update-image-gallery', [ProductController::class, 'update_image_gallery']);
        Route::post('/update-nameimg-gallery', [ProductController::class, 'update_name_gallery']);
        Route::post('/update-content-gallery', [ProductController::class, 'update_content_gallery']);
        Route::post('/delete-gallery', [ProductController::class, 'delete_gallery']);
    });
    Route::group(['middleware' => 'admin.manager.roles'], function () {
        /* Flashsale */
        Route::group(['prefix' => 'admin/flashsale'], function () {
            Route::get('/', [FlashsaleController::class, 'all_product_flashsale']);
            Route::get('/add-product-flashsale', [FlashsaleController::class, 'add_product_flashsale']);
            Route::post('/save-product-flashsale', [FlashsaleController::class, 'save_product_flashsale']);
            Route::get('/all-product-flashsale', [FlashsaleController::class, 'all_product_flashsale']);
            Route::get('/edit-product-flashsale', [FlashsaleController::class, 'edit_product_flashsale']);
            Route::post('/update-product-flashsale', [FlashsaleController::class, 'update_product_flashsale']);
            Route::get('/delete-product-flashsale', [FlashsaleController::class, 'delete_product_flashsale']);
            Route::get('/active-product-flashsale', [FlashsaleController::class, 'active_product_flashsale']);
            Route::get('/unactive-product-flashsale', [FlashsaleController::class, 'unactive_product_flashsale']);
        });

        /* Coupon - Mã Giảm Giá*/
        Route::group(['prefix' => 'admin/coupon'], function () {
            Route::get('/', [CouponController::class, 'list_coupon']);
            Route::get('/list-coupon', [CouponController::class, 'list_coupon']);
            Route::get('/add-coupon', [CouponController::class, 'add_coupon']);
            Route::get('/edit-coupon', [CouponController::class, 'edit_coupon']);
            Route::post('/update-coupon', [CouponController::class, 'update_coupon']);
            Route::post('/save-coupon', [CouponController::class, 'save_coupon']);
            Route::get('/delete-coupon', [CouponController::class, 'delete_coupon']);
        });

    });

    /* Delivery - Vận Chuyển */
    Route::group(['prefix' => 'admin/delivery'], function () {
        Route::GET('/', [DeliveryController::class, 'show_delivery']);
        Route::GET('/show-delivery', [DeliveryController::class, 'show_delivery']);
        Route::POST('/select-delivery', [DeliveryController::class, 'select_delivery']);
        Route::POST('/insert-delivery', [DeliveryController::class, 'insert_delivery']);
        Route::POST('/loading-feeship', [DeliveryController::class, 'loading_feeship']);
        Route::POST('/update-delivery', [DeliveryController::class, 'update_delivery']);
        Route::POST('/delete-delivery', [DeliveryController::class, 'delete_delivery']);
    });

    /* Order Manager */
    Route::group(['prefix' => 'admin/order'], function () {
        Route::get('/', [OrderController::class, 'manager_order']);
        Route::get('/loading-order-manager', [OrderController::class, 'loading_manager_order']);
        Route::get('/order-manager', [OrderController::class, 'manager_order']);
        Route::get('/view-order', [OrderController::class, 'view_order']);
        Route::get('/delete-order', [OrderController::class, 'delete_order'])->middleware('admin.manager.roles');
        Route::POST('/order-status', [OrderController::class, 'order_status']);
        Route::get('/print-order', [OrderController::class, 'print_order']);

    });

    /* Comment - Bình Luận */
    Route::group(['prefix' => 'admin/comment'], function () {
        Route::get('/', [CommentController::class, 'all_comment']);
        Route::get('/all-comment', [CommentController::class, 'all_comment']);
        Route::get('/loading-table-comment', [CommentController::class, 'loading_table_comment']);
        Route::get('/set-status', [CommentController::class, 'set_status']);
        Route::post('/delete-comment', [CommentController::class, 'delete_comment']);
        Route::get('/un-permit-comment', [CommentController::class, 'set_status']);
    });

    /* Nhật Ký Hoạt Động*/
    Route::group(['prefix' => 'admin/activity'], function () {
        /* Nhật Ký Đăng Nhập */
        Route::get('/all-activity-admin', [ActivityLogController::class, 'all_activity_admin'])->middleware('admin.roles');
        Route::get('/all-activity-customer', [ActivityLogController::class, 'all_activity_customer'])->middleware('admin.manager.roles');
        /* Nhật Ký Thao Tác */
        Route::get('/all-manipulation-admin', [ManipulationActivityController::class, 'all_manipulation_admin'])->middleware('admin.roles');
        Route::get('/all-manipulation-customer', [ManipulationActivityController::class, 'all_manipulation_customer'])->middleware('admin.manager.roles');
    });

    Route::group(['middleware' => 'admin.roles'], function () {
        Route::group(['prefix' => '/admin/web'], function () {
            Route::get('/', [ConfigWebController::class, 'show_config']);
            Route::post('/insert-config-image', [ConfigWebController::class, 'insert_config_image']);
            Route::post('/load-config-slogan', [ConfigWebController::class, 'loading_config_slogan']);
            Route::post('/edit-config-title', [ConfigWebController::class, 'edit_config_title']);
            Route::post('/edit-config-content', [ConfigWebController::class, 'edit_config_content']);
            Route::post('/update-image-config', [ConfigWebController::class, 'update_image_config']);
            Route::post('/load-logo-config', [ConfigWebController::class, 'load_logo_config']);
            Route::post('/delete-config-slogan', [ConfigWebController::class, 'delete_config_slogan']);
            Route::get('/load-config-brand', [ConfigWebController::class, 'load_config_brand']);
        });
        Route::group(['prefix' => '/admin/config-footer'], function () {
            Route::get('/', [CompanyConfigController::class, 'show_company_config']);
            Route::post('/edit-content-footer', [CompanyConfigController::class, 'edit_content_footer']);
        });
    });

});

/* Font-End */

/* Đăng Ký - Đăng Nhập - Quên Mật Khẩu ! */
Route::group(['prefix' => '/user'], function () {
    /* Đăng Nhập Tài Khoản Hệ Thống*/
    Route::post('/login-customer', [LoginAndRegister::class, 'login_customer']);
    /* Đăng Nhập Bằng Tài Khoản Google */
    Route::get('/login-google', [LoginAndRegister::class, 'login_google']);
    Route::get('/login-google/callback', [LoginAndRegister::class, 'login_google_callback']);
    /* Đăng Nhập Bằng Tài Khoản Facebook */
    Route::get('/login-facebook', [LoginAndRegister::class, 'login_facebook']);
    Route::get('/login-facebook/callback', [LoginAndRegister::class, 'login_facebook_callback']);
    /* Đăng Ký */
    Route::post('/create-customer', [LoginAndRegister::class, 'create_customer']);
    Route::post('/verification-code-rg', [LoginAndRegister::class, 'verification_code_rg']);
    Route::get('/MailToCustomer', [LoginAndRegister::class, 'MailToCustomer']);
    Route::get('/successful-create-account', [LoginAndRegister::class, 'successful_create_account']);
    /* Quên Mật Khẩu */
    Route::post('/find-account-recovery-pw', [LoginAndRegister::class, 'find_account_recovery_pw']);
    Route::post('/verification-code-rc', [LoginAndRegister::class, 'verification_code_rc']);
    Route::post('/confirm-password', [LoginAndRegister::class, 'confirm_password']);
    /* Đăng Xuất */
    Route::get('/logout', [LoginAndRegister::class, 'logout']);

    Route::get('/order', [MyOrderController::class, 'show_order']);
    Route::get('/order/loading-order', [MyOrderController::class, 'loading_order']);
    Route::get('/order/submit-order', [MyOrderController::class, 'submit_order']);


    // Route::get('/order', [OrderController::class, 'show_order']);
    // Route::get('/order/loading-order', [OrderController::class, 'loading_order']);
    // Route::get('/order/submit-order', [OrderController::class, 'submit_order']);
    Route::get('/order/search-order', [MyOrderController::class, 'search_order']);
    Route::get('/order/check-order', [MyOrderController::class, 'check_order']);
    Route::get('/order/submit-order-check', [MyOrderController::class, 'submit_order_check']);
    Route::get('/order/comment-order', [MyOrderController::class, 'comment_order']);
});

/* Trang Chủ */
Route::get('/', [HomeController::class, 'index']);
Route::group(['prefix' => '/trang-chu'], function () {
    Route::get('/', [HomeController::class, 'index']);
    Route::get('/show-product-category', [HomeController::class, 'show_product_category']);
    Route::get('/sort-max-price-product', [HomeController::class, 'sort_max_price_product']);
    Route::get('/sort-min-price-product', [HomeController::class, 'sort_min_price_product']);
    Route::get('/sort-newproduct', [HomeController::class, 'sort_newproduct']);
    Route::get('/search-by-voice', [HomeController::class, 'search_by_voice']);
    Route::get('/price-filter', [HomeController::class, 'price_filter']);

});

Route::group(['prefix' => '/cua-hang'], function(){
    Route::get('/', [HomeController::class, 'danh_sach_san_pham']);
    Route::get('/danh-sach-san-pham', [HomeController::class, 'danh_sach_san_pham']);
    Route::get('/load-danh-sach-san-pham', [HomeController::class, 'load_danh_sach_san_pham']);
    Route::get('/search-san-pham', [HomeController::class, 'search_san_pham']);
});

/* Chi Tiết Sản Phẩm */
Route::group(['prefix' => '/san-pham'], function () {
    Route::get('/san-pham-chi-tiet-flash-sale', [ProductController::class, 'san_pham_chi_tiet_flash_sale']);
    Route::get('/san-pham-chi-tiet', [ProductController::class, 'san_pham_chi_tiet']);
    /* Comment */
    Route::get('/tai-binh-luan', [CommentController::class, 'load_comment']);
    Route::POST('/dang-binh-luan', [CommentController::class, 'insert_comment']);

});

//Giỏ hàng Cần Việt Hóa URL
Route::group(['prefix' => '/cart'], function () {
    Route::get('/', [CartController::class, 'show_cart']);
    Route::get('/load-detail-cart', [CartController::class, 'load_detail_cart']);
    Route::get('/load-payment', [CartController::class, 'load_payment']);
    Route::get('/load-coupon', [CartController::class, 'load_coupon']);
    Route::get('/message-cart', [CartController::class, 'message_cart']);

    Route::POST('/save-cart', [CartController::class, 'save_cart']);
    Route::POST('/delete-cart', [CartController::class, 'delete_cart']);
    Route::POST('/delete-all-cart', [CartController::class, 'delete_all_cart']);
    Route::POST('/update-all-cart', [CartController::class, 'update_all_cart']);
    Route::get('/check-coupon', [CartController::class, 'check_coupon']);
    Route::POST('/delete-coupon', [CartController::class, 'delete_coupon']);

    Route::POST('/caculate-fee', [CartController::class, 'caculator_fee']);
    Route::POST('/confirm-cart', [CartController::class, 'confirm_cart']);

});
/* Thanh Toán */
Route::group(['prefix' => '/thanh-toan'], function () {
    Route::get('/', [CheckOutController::class, 'show_payment']);
    Route::POST('/yeu-cau-rieng', [CheckOutController::class, 'insert_notes']);
    Route::POST('/yeu-cau-dac-biet', [CheckOutController::class, 'special_requirements']);
    Route::POST('/yeu-cau-hoa-don', [CheckOutController::class, 'receipt']);
    Route::get('/direct-payment', [CheckOutController::class, 'direct_payment']);
    Route::get('/momo-payment', [CheckOutController::class, 'momo_payment']);
    Route::get('/momo-payment-callback', [CheckOutController::class, 'momo_payment_callback']);
    Route::get('/vnpay-payment', [CheckOutController::class, 'vnpay_payment']);
    Route::get('/vnpay-payment-callback', [CheckOutController::class, 'vnpay_payment_callback']);
    Route::get('/hoa-don', [CheckOutController::class, 'show_receipt']);
    Route::get('/un-set-order', [CheckOutController::class, 'un_set_order']);
});
