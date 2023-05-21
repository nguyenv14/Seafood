<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use App\Models\Slider;
use App\Models\Comment;
use App\Models\Order;
use App\Models\Admin;
use App\Models\ConfigWeb;
use App\Models\CompanyConfig;
use Auth;
use Session;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        
        $this->app->singleton(
            \App\Repositories\SliderRepository\SliderRepositoryInterface::class,
            \App\Repositories\SliderRepository\SliderRepository::class,
        );
        $this->app->singleton(
            \App\Repositories\ProductRepository\ProductRepositoryInterface::class,
            \App\Repositories\ProductRepository\ProductRepository::class,
        );
        $this->app->singleton(
            \App\Repositories\CategoryRepository\CategoryRepositoryInterface::class,
            \App\Repositories\CategoryRepository\CategoryRepository::class,
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /* Biến Toàn View */
        view()->composer('*', function ($view) {
            /* Biến Toàn View Của Back-End */

            // if(session()->has('impersonate')){
            //     $admin_id = session()->get('impersonate');
            // }else{
            //     $admin_id = Auth::id();
            // }
            // $admin = Admin::where('admin_id',$admin_id)->first();
            // $admin_roles =  $admin->viewRoles();
            // $roles_name = $admin_roles->roles_name;

            /* Biến Toàn View Của Font-End */
          
            $config_logo_web = ConfigWeb::where('config_type', 1)->first();
            $config_slogan_web = ConfigWeb::where('config_type', 2)->orderBy('config_id', "DESC")->take(4)->get();
            $config_brand_web = ConfigWeb::where('config_type', 3)->orderBy('config_id', "DESC")->take(4)->get();
            $company_config = CompanyConfig::where('company_id', 1)->first();
            $slider = Slider::orderby('slider_id','desc')->get();
            $view->with(compact('slider', 'config_logo_web', 'config_slogan_web', 'config_brand_web', 'company_config'));


        });

        Paginator::useBootstrap();/* Sử dụng Boostrap để làm giao diện cho phân trang */
    }
}
