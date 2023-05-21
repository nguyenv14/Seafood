<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Location;
use Carbon\Carbon;
use App\Models\Activitylog;
use App\Models\ManipulationActivity;
session_start();
class ActivityLogController extends Controller
{
    /* Nhật Ký Đăng Nhập - Đăng Xuất Của Admin */
    public function all_activity_admin()
    {
        $admin_activities = Activitylog::where('activitylog_type',0)->orderby('activitylog_id','DESC')->Paginate(10);
        ManipulationActivity::noteManipulationAdmin("Xem Bảng Hoạt Động Đăng Nhập - Đăng Xuất Admin");
        return view('admin.Activity.Activitylog.all_activity_admin')->with(compact('admin_activities'));
    }
    /* Nhật Ký Của Người Dùng */
    public function all_activity_customer()
    {
        $customer_activities = Activitylog::where('activitylog_type',1)->orderby('activitylog_id','DESC')->Paginate(10);
        ManipulationActivity::noteManipulationAdmin("Xem Bảng Hoạt Động Đăng Nhập - Đăng Xuất Người Dùng");
        return view('admin.Activity.Activitylog.all_activity_customer')->with(compact('customer_activities'));
    }

    public function message($type, $content)
    {
        $message = array(
            "type" => "$type",
            "content" => "$content",
        );
        Session::put('message', $message);
    }

}
