<?php

namespace App\Http\Controllers;
use App\Models\Admin;
use App\Models\Customers;
use App\Models\Comment;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductDetails;
use App\Models\ActivityLog;
use App\Models\ManipulationActivity;
use App\Models\Statistical;
use Carbon\Carbon;
use Illuminate\Http\Request;
session_start();
class DashboardController extends Controller
{
    public function show_dashboard()
    {
        $count_order = Order::count();
        $count_admin = Admin::count();
        $count_customers = Customers::count();
       
        return view('admin.dashboard')->with(compact( 'count_order' ,'count_admin' , 'count_customers'));
    }
    /* Chức Năng Tin Nhắn */
    public function dashboard_notification()
    {
        $all_product = Product::get();
        $all_product_details = ProductDetails::get();
        $check_product_details = 0;
        foreach ($all_product as $product) {
            $product_id = $product->product_id;
            foreach ($all_product_details as $product_details) {
                if ($product_id == $product_details->product_id) {
                    $check_product_details++;
                }
            }
        }
        $mesage_product_details = $all_product->count() - $check_product_details;
        $comment = Comment::where('comment_status', '0')->get();
        $order = Order::where('order_status', '0')->get();
        $output = '';
        /* Đếm Số Order Chưa Duyệt */
        if (!$comment && !$order && $mesage_product_details == 0) {
            $content = "Bạn Không Có Tin Nhắn Nào";
            $output .= $this->output_notification('#',$content);
        } else {
            if ($order) {
                $count_order = $order->count();
                $url = url('admin/order/order-manager');
                $content = "Bạn Có $count_order Đơn Hàng Cần Phê Duyệt";
                $output .= $this->output_notification($url,$content);
            }
            if ($comment) {
                $count_comment = $comment->count();
                $url = url('admin/comment/all-comment');
                $content = "Bạn Có $count_comment Bình Luận Cần Phê Duyệt";
                $output .= $this->output_notification($url,$content);
            }

            if ($mesage_product_details > 0) {
                $url = url('admin/product/all-product');
                $content = "Bạn Có $mesage_product_details Sản Phẩm Chưa Thêm Thông Tin Chi Tiết";
                $output .= $this->output_notification($url,$content);
            }
        }
        echo $output;
    }
    public function output_notification($url,$content){
        $output = '
        <div class="dropdown-divider"></div>
            <a href="' .$url. '" class="dropdown-item preview-item">
              <div class="preview-thumbnail">
                <img src="' . asset('public/backend/assets/images/faces/face4.jpg') . '" alt="image" class="profile-pic">
              </div>
                  <div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                      <h6 class="preview-subject ellipsis mb-1 font-weight-bolder text-danger">Quản Trị Viên</h6>
                      <p class="text-black ellipsis mb-0">'.$content.'</p>
                  </div>
            </a>
        ';
        return $output;
    }

    /* Chức năng thông báo */
    public function dashboard_notification_customer(){
        $customer_notification_order = ManipulationActivity::where('manipulation_activity_type',1)
        ->whereBetween('created_at', [Carbon::now('Asia/Ho_Chi_Minh')->startOfDay(), Carbon::now('Asia/Ho_Chi_Minh')->endOfDay()])
        ->whereIn('manipulation_activity_action',['Đặt Hàng Thành Công!', 'Đặt Hàng Và Thanh Toán Thành Công Đơn Hàng Bằng Momo Thành Công!'])
        ->get();

        $customer_notification_comment = ManipulationActivity::where('manipulation_activity_type',1)
        ->whereBetween('created_at', [Carbon::now('Asia/Ho_Chi_Minh')->startOfDay(), Carbon::now('Asia/Ho_Chi_Minh')->endOfDay()])
        ->where('manipulation_activity_action','like','%Bình Luận Sản Phẩm%')
        ->get();

        $customer_notification_login = ActivityLog::where('activitylog_type',1)
        ->whereBetween('created_at', [Carbon::now('Asia/Ho_Chi_Minh')->subday(1)->startOfDay(), Carbon::now('Asia/Ho_Chi_Minh')->endOfDay()])
        ->where('activitylog_proceed','like','%Đăng Nhập%')
        ->get();
        
    }

    
    public function customer_visit(){
        $startOfDay = Carbon::now('Asia/Ho_Chi_Minh')->startOfDay();
        $endOfDay = Carbon::now('Asia/Ho_Chi_Minh')->endOfDay();
        $ip_customer = request()->ip();
        $count_customer_online = ManipulationActivity::distinct()->where('manipulation_activity_type',1)->whereBetween('created_at', [$startOfDay, $endOfDay])->where('manipulation_activity_ip',$ip_customer)->count('manipulation_activity_ip');
        $output = '<h3 class="font-weight-medium text-right mb-0">'.$count_customer_online.'</h3>';
        echo $output;
    }
    public function admin_online(){
        $startOfDay = Carbon::now('Asia/Ho_Chi_Minh')->startOfDay();
        $endOfDay = Carbon::now('Asia/Ho_Chi_Minh')->endOfDay();
        $ip_admin = request()->ip();
        $count_admin_online = ActivityLog::distinct()->where('activitylog_type',0)->whereBetween('created_at', [$startOfDay, $endOfDay])->where('activitylog_ip',$ip_admin)->count('activitylog_ip');
        $output = '<h3 class="font-weight-medium text-right mb-0">'.$count_admin_online.'</h3>';
        echo $output;
    }
    public function today_order(){
        /* % (tăng trưởng, lợi nhuận...) = (Năm sau - Năm trước)/Năm trước * 100. */
        $startOfDay = Carbon::now('Asia/Ho_Chi_Minh')->startOfDay();
        $endOfDay = Carbon::now('Asia/Ho_Chi_Minh')->endOfDay();
        $startOfYesterDay = Carbon::now('Asia/Ho_Chi_Minh')->subDay(1)->startOfDay();
        $endOfYesterDay = Carbon::now('Asia/Ho_Chi_Minh')->subDay(1)->endOfDay();

        $count_order_toDay = Order::whereBetween('created_at', [$startOfDay, $endOfDay])->count();
        $count_order_Yesterday = Order::whereBetween('created_at', [$startOfYesterDay, $endOfYesterDay])->count();
        if($count_order_Yesterday == 0){
            $growth = $count_order_toDay * 100;
        }else{
            $growth =  (($count_order_toDay -  $count_order_Yesterday)/ $count_order_Yesterday) * 100;
        }
        $growth = number_format( $growth,2);
        if($growth == 0){
            $output_growth = 'Tăng Trưởng Bằng 0% So Với Hôm Qua';
        }else if($growth > 0){
            $output_growth = 'Tăng '.$growth.' % So Với Hôm Qua';
        }else if($growth < 0){
            $output_growth = 'Giảm '.$growth.' % So Với Hôm Qua';
        }

        $output = '<div class="card-body">
        <div class="clearfix">
          <div class="float-left">
            <i class="mdi mdi-receipt text-warning icon-lg"></i>
          </div>
          <div class="float-right">
            <p class="mb-0 text-right">Đơn Hàng Hôm Nay</p>
            <div  class="fluid-container">
              <h3 class="font-weight-medium text-right mb-0">'.$count_order_toDay.'</h3>
            </div>
          </div>
        </div>
        <p class="text-muted mt-3 mb-0">
          <i class="mdi mdi-bookmark-outline mr-1" aria-hidden="true"></i>'.$output_growth.'
        </p>
      </div>';
        echo $output;
    }


    public function filter_doanh_thu(Request $request){
        $date_from = $request->date_from;
        $date_to = $request->date_to;
        $chart_data = array();
        $statitiscal = Statistical::whereBetween('order_date', [$date_from, $date_to])->orderby('order_date', 'ASC')->get();
        // dd($statitiscal);
        foreach($statitiscal as $value){
            $chart_data[] = array(
                'period' => $value->order_date,
                'order' => $value->total_order,
                'sales' => $value->sales,
                'order_boom' => $value->order_boom,
                'price_boom' => $value->price_boom,
                'quantity' => $value->quantity
            );
        }
        
        $data = json_encode($chart_data);
        // dd($data);
        echo $data;
    }

    
    public function doanh_thu_five_day(Request $request){
        $sub5day = Carbon::now('Asia/Ho_Chi_Minh')->subdays(30)->toDateString();
        $now = Carbon::now('Asia/Ho_Chi_Minh')->toDateString();
        $get = Statistical::whereBetween('order_date', [$sub5day, $now])->orderby('order_date', 'ASC')->get();
        // dd($get);
        $chart_data = array();
        foreach($get as $key => $value){
            $chart_data[] = array(
                'period' => $value->order_date,
                'order' => $value->total_order,
                'sales' => $value->sales,
                'order_boom' => $value->order_boom,
                'price_boom' => $value->price_boom,
                'quantity' => $value->quantity
            );
        }

        echo json_encode($chart_data);
    }





    public function aboutCarbon()
    {
        // Carbon::now(); // thời gian hiện tại
        // Carbon::yesterday(); //thời gian hôm qua
        // Carbon::tomorrow(); // thời gian ngày mai
        // $newYear = new Carbon('first day of January 2018'); // 2018-01-01 00:00:00
        // toDayDateTimeString();  Thu, Oct 18, 2018 9:16 PM
        // toFormattedDateString(); // Oct 18, 2018
        // format('l jS \\of F Y h:i:s A'); // Thursday 18th of October 2018 09:18:57 PM
        // toDateString() 26/08/2002

        // Carbon::now()->day; //ngày
        // Carbon::now()->month; //tháng
        // Carbon::now()->year; //năm
        // Carbon::now()->hour; //giờ
        // Carbon::now()->minute; //phút
        // Carbon::now()->second; //giây
        // Carbon::now()->dayOfWeek; //ngày của tuần
        // Carbon::now()->dayOfYear; //ngày của năm
        // Carbon::now()->weekOfMonth; //ngày của tháng
        // Carbon::now()->weekOfYear; //tuần của năm
        // Carbon::now()->daysInMonth; //số ngày trong tháng

        // $now = Carbon::now();
        // $now->isWeekday();
        // $now->isWeekend();
        // $now->isYesterday();
        // $now->isToday();
        // $now->isTomorrow();
        // $now->isFuture()
        // $now->isPast();
        // $now->isBirthday(); // là ngày sinh nhật hay không

        // Carbon::setLocale('vi'); // hiển thị ngôn ngữ tiếng việt.
        // $dt = Carbon::create(2022, 10, 14, 14, 40, 16);
        // $dt2 = Carbon::create(2018, 10, 18, 13, 40, 16);
        // echo  $dt;
        // $now = Carbon::now();
        // echo $dt->diffForHumans($now); //12 phút trước
        // echo $dt2->diffForHumans($now); //1 giờ trước

        //$dt =Carbon::now('Asia/Ho_Chi_Minh');

        // echo $dt->addYears(5);
        // echo $dt->addYear();
        // echo $dt->subYear();
        // echo $dt->subYears(5);

        // echo $dt->addMonths(60);
        // echo $dt->addMonth();
        // echo $dt->subMonth();
        // echo $dt->subMonths(60);

        // echo $dt->addWeeks(3);
        // echo $dt->addWeek();
        // echo $dt->subWeek();
        // echo $dt->subWeeks(3);

        // echo $dt->addDays(29);
        // echo $dt->addDay();
        // echo $dt->subDay();
        // echo $dt->subDays(29);

        // echo $dt->addHours(24);
        // echo $dt->addHour();
        // echo $dt->subHour();
        // echo $dt->subHours(24);

        // startOfDay() Bat dau 1 ngay
        // endOfDay() ket thuc 1 ngay

        // $startOfDay = Carbon::now('Asia/Ho_Chi_Minh')->startOfDay();
        // $endOfDay = Carbon::now('Asia/Ho_Chi_Minh')->endOfDay();

        // $test = ActivityLog::distinct()->where('activitylog_type',0)->count('activitylog_ip');; /* Đếm các trường duy nhất */
       
        // $test = ActivityLog::get()->unique('activitylog_ip');
        // $timestamp = time();
        // echo(date("Y/m/d h:i:s", $timestamp));
        // echo date("Y/m/d");

        // $startOfYesterDay = Carbon::now('Asia/Ho_Chi_Minh')->subDay(1)->startOfDay();
        // $endOfYesterDay = Carbon::now('Asia/Ho_Chi_Minh')->subDay(1)->endOfDay();

       

    }


}
