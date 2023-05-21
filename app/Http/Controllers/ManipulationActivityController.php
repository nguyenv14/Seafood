<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Location;
use App\Models\ManipulationActivity;
use Carbon\Carbon;
session_start();
class ManipulationActivityController extends Controller
{
    /* Nhật Ký Của Admin */
    public function all_manipulation_admin()
    {
        $admin_manipulation = ManipulationActivity::where('manipulation_activity_type',0)->orderby('manipulation_activity_id','DESC')->Paginate(20);
        ManipulationActivity::noteManipulationAdmin("Xem Bảng Nhật Ký Thao Tác Admin");
        return view('admin.Activity.ManipulationActivity.all_manipulation_admin')->with(compact('admin_manipulation'));
    }
    /* Nhật Ký Của Người Dùng */
    public function all_manipulation_customer()
    {
        $customer_manipulation = ManipulationActivity::where('manipulation_activity_type',1)->orderby('manipulation_activity_id','DESC')->Paginate(20);
        ManipulationActivity::noteManipulationAdmin("Xem Bảng Nhật Ký Thao Tác Người Dùng");
        return view('admin.Activity.ManipulationActivity.all_manipulation_customer')->with(compact('customer_manipulation'));
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
