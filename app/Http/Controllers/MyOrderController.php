<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Coupon;
use App\Models\Customers;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Payment;
use App\Models\Roles;
use App\Models\Shipping;
use App\Models\Statistical;
use App\Rules\Captcha;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Session;

session_start();
class MyOrderController extends Controller
{

    // public function show_order(Request $request){
        

    //     $customer_id = $request->customer_id;
    //     $customer = Customers::where('customer_id', $customer_id)->first();

    //     $orders = Order::where('customer_id', $customer_id)->paginate(5);
    //     return view('pages.home.my_order')->with( compact('customer', 'meta', 'orders'));
    // }



    public function show_order(Request $request){
        $meta = array(
            'title' => 'Tìm Kiếm - Thế Giới Hải Sản',
            'description' => 'Thế Giới Hải Sản - Trang Tìm Kiếm Và Đặt Hải Sản Hàng Đầu Việt Nam',
            'keywords' => 'Hải Sản Đà Nẵng , Hải Sản Giá Rẻ , Hải Sản Tươi Sống , Hải Sản Giao Nhanh , Tôm Hùng , Cua , Ghẹ , .....',
            'canonical' => request()->url(),
            'sitename' => 'sepnguyenvanhanbro.thegioihaisan.laravel.vn',
            'image' => '',
        );
        $customer_id = $request->customer_id;
        if($customer_id != -1){
            if(!session()->get('customer_id') || session()->get('customer_id') != $customer_id){
                $this->message('error', 'Bạn không được phép truy cập vào đường link này');
                return redirect()->back();
            }
            $customer = Customers::where('customer_id', $customer_id)->first();
            $orders = Order::where('customer_id', $customer_id)->orderby('order_id', "DESC")->paginate(5);
            return view('pages.home.my_order')->with( compact('customer', 'orders', 'meta'));
        }else{
            return view('pages.home.my_order_1')->with('meta', $meta);
        }
    }

    public function loading_order(Request $request){
        $customer_id = $request->customer_id;
        $orders = Order::where('customer_id', $customer_id)->orderby('order_id', "DESC")->paginate(5);
        $output = $this->print_my_order($orders);
        return $output;
    }

    public function check_order(Request $request){
        $order_code = $request->order_code;

        $order = Order::where('order_code', $order_code)->get();
        // dd($order);

        $output = '';
        if(count($order) > 0){
            $output .= $this->print_my_order($order);
        }else{
            $output .= 'fail';
        }
        // dd($output);
        return $output;
    }

    public function print_my_order($orders){
        // dd($orders);
        $output = '';
        if(count($orders) > 0){
            foreach($orders as $order){
                $coupon = Coupon::where('coupon_name_code', $order->product_coupon)->first();
                $order_details = OrderDetails::where('order_code', $order->order_code)->get();
                // dd($order_details);
                $shipping = Shipping::where('shipping_id', $order->shipping_id)->first();
                $payment = Payment::where('payment_id', $order->payment_id)->first();
                $total_price = 0;
                $output .= ' <div class="order_box mt-3">
                <h4>Mã Đơn: '.$order->order_code.' - '.$order->created_at.'</h4>
                <hr width="100%">
                <div class="row  mt-2 mb-2">';
                    foreach($order_details as $order_detail){
                        // dd($order_detail->product->product_image);
                        $output .= '
                        <div class="col-md-4 col-sm-12 mt-2 product_item">
                            <img width="100px" style="object-fit: cover;border-radius: 10px; margin-right: 20px;" src="'.url('public/fontend/assets/img/product/'.$order_detail->product->product_image.'').'" alt="">
                            <div class="ms-4 mt-2">
                                <span style="font-weight: 500;">'.$order_detail->product_name.'</span> <br>
                                Số lượng: '.$order_detail->product_sales_quantity.' <br>
                                Giá: '.number_format($order_detail->product_price, 0, ',', '.').'đ
                            </div>
                            <input type="text" class="order_code_box" value="'.$order->order_code.'" hidden>
                        </div>';
                        $total_price = $total_price + $order_detail->product_sales_quantity * $order_detail->product_price;

                    }
                    $fee_ship = $order->product_fee;
                    $coupon_sale = 0;
                    if($order->product_price_coupon > 0){
                        if($order->product_price_coupon < 100){
                            $coupon_sale = ($total_price / 100) * $coupon->product_price_coupon;
                        }else{
                            if($order->product_price_coupon > $total_price){
                                $coupon_sale = $total_price;
                            }else{
                                $coupon_sale = $order->product_price_coupon;
                            }
                        }
                    }else{
                        $coupon_sale = 0;
                    }
                $output .= '
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-7 col-sm-12 shopper_detail mt-4">
                        <h5>Người Đặt: '.$shipping->shipping_name.'</h5>
                        <h5>SĐT: '.$shipping->shipping_phone.'</h5>
                        <h5>Địa Chỉ: '.$shipping->shipping_address.'</h5>
                    </div>
                    <div class="col-md-5 col-sm-12">
                        <table class="m-3" style="width: 90%;">
                            <tr style="border-bottom: 1px solid #0c0c0c;">
                                <th>Phí Ship</th>
                                <th style="text-align: right;">'.number_format($fee_ship, 0, ',', '.').'đ</th>
                            </tr>
                            
                            <tr style="border-bottom: 1px solid #0c0c0c;">
                                <th>Mã Giảm Giá</th>';
                                if($order->product_price_coupon){
                                   $output .= '<th style="text-align: right;"> '.$order->product_coupon.' - '.number_format($coupon_sale, 0, ',', '.').'đ</th>';
                                    
                                }else{
                                   $output .= '<th style="text-align: right;">Không có</th>';
                                }
                            $output .= '
                            </tr>
                            <tr style="border-bottom: 1px solid #0c0c0c;">
                                <th>Tổng tiền</th>
                                <th style="color: red;text-align: right;">'.number_format($total_price + $fee_ship - $coupon_sale , 0, ',', '.').'đ</th>
                            </tr>
                            <tr>
                                <th>Tình Trạng</th>';
                                /*
        -1: đơn hàng đã bị từ chối
        0: đơn hàng đang chờ duyệt
        1: đơn hàng đang được cbi, vận chuyển
        2: đơn hàng hoàn thành
        3: đơn hàng hoàn trả
        */
                                if($order->order_status == 0){
                                    $output .= '<th style="text-align: right;color: red;">Đang Duyệt</th>';
                                }else if($order->order_status == -1){
                                    $output .= '<th style="text-align: right;color: red;">Từ Chối Bởi Shop</th>';
                                }else if($order->order_status == 1){
                                    $output .= '<th style="text-align: right;color: red;">Đang Vận Chuyển</th>';
                                }else if($order->order_status == 2 || $order->order_status == 4){
                                    $output .= '<th style="text-align: right;color: green;">Đã Hoàn Thành</th>';
                                }else if($order->order_status == 3){
                                    $output .= '<th style="text-align: right;color: red;">Đã Từ Chối Nhận</th>';
                                }
                            $output .='</tr>
                            </table>
        
                        <div class="row m-3 d-flex justify-content-center">';
                        if($order->order_status == 0){
                            $output .= '<button class="btn-primary btn-danger btn_cancel_order" style="width: 200px" data-toggle="modal" data-target="#deleted" data-order_id="'.$order->order_id.'" data-order_status="hủy">Hủy Đơn Hàng</button>';
                        }else if($order->order_status == -1){
                            $output .= '';
                        }else if($order->order_status == 1){
                            $output .= '
                        <div class="col-md-12">
                            <button class="btn-primary btn-success btn_cancel_order" style="width: 200px" data-toggle="modal" data-target="#submit" style="width:90%;background:orange;" data-order_id="'.$order->order_id.'" data-order_status="nhận">Đã Nhận Được Hàng</button>
                        </div>';
                        }else if($order->order_status == 2 && session()->get('customer_id')){
                            $output .= '
                            <div class="col-md-12 d-flex justify-content-center">
                                <button class="btn-secondary btn-danger btn_cancel_order btn_cm" data-toggle="modal" data-target="#danhgia" style="width:200px;" data-order_code="'.$order->order_code.'">Đánh Giá Sản Phẩm</button> 
                            </div> 
                            ';
                        }else if($order->order_status == 3 || $order->order_status == 4){
                            $output .= '';
                        }    
                        $output .=  '</div>
                    </div>
                </div>
            </div>';
            // break;
            }
        }else{
            $output .= '<h2 style="text-align:center;">Quý Khách Chưa Đặt Đơn Hàng Nào Cả.</h2>';
        }
        echo $output;
    }

    public function submit_order(Request $request){
        $order_id = $request->order_id;
        $status = $request->order_status;

        $order = Order::where("order_id", $order_id)->first();
        $coupon_sale = 0;
                    if($order->product_price_coupon > 0){
                        if($order->product_price_coupon < 100){
                            $coupon_sale = ($total_price / 100) * $coupon->product_price_coupon;
                        }else{
                            if($order->product_price_coupon > $total_price){
                                $coupon_sale = $total_price;
                            }else{
                                $coupon_sale = $order->product_price_coupon;
                            }
                        }
                    }else{
                        $coupon_sale = 0;
                    }
        $payment = Payment::where('payment_id', $order->payment_id)->first();

        if($status == 'nhận'){
            $order->order_status = 2;
            $payment->payment_status = 1;

            $order->save();
            $payment->save();

            $now = Carbon::now('Asia/Ho_Chi_Minh')->format('Y-m-d');

            $statical = Statistical::where('order_date', $now)->first();
            if($statical){
                $statical['sales'] = $statical['sales'] + $order->total_price - $coupon_sale;
                $statical['quantity'] = $statical['quantity'] + $order->total_quantity;
                $statical['total_order'] += 1;

                $statical->save();
            }else{
                // dd('huhu');
                $statis = new Statistical();
                $statis->order_date = $order->order_date;
                $statis->sales = $order->total_price - $coupon_sale;
                $statis->order_boom = 0;
                $statis->price_boom = 0;    
                $statis->quantity = $order->total_quantity;
                $statis->total_order = 1;
                $statis->save();
            }
        }else{ 
            $order->order_status = 3;
            $order->save();
            $now = Carbon::now('Asia/Ho_Chi_Minh')->format('Y-m-d');

            $statical = Statistical::where('order_date', $now)->first();
            if($statical){
                $statical['price_boom'] = $statical['price_boom'] + $order->total_price;
                $statical['quantity'] = $statical['quantity'] + $order->total_quantity;
                $statical['total_order'] += 1;
                $statical['order_boom'] += 1;
                $statical->save();
            }else{
                // dd('huhu');
                $statis = new Statistical();
                $statis->order_date = $order->order_date;
                $statis->sales = 0;
                $statis->order_boom = 1;
                $statis->price_boom = $order->total_price;    
                $statis->quantity = $order->total_quantity;
                $statis->total_order = 1;
                $statis->save();
            }
        }
    }

    public function load_count_order(){
        $orders = Order::where('order_status', 0)->get();

        $count_order = count($orders);
        $output = '';
        if($count_order > 0){
            $output .= '
            <a href="'.url('admin/order-manager').'" class="dropdown-item preview-item">
                            <div class="preview-thumbnail">
                              <img src="'.url('public/fontend/assets/iconlogo/logo1.png').'" alt="image" class="profile-pic">
                            </div>
                            <div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                              <h6 class="preview-subject ellipsis mb-1 font-weight-normal">Có <span style="color:red; font-weight:600;">'.$count_order.'</span> đơn hàng đang chờ bạn xét duyệt</h6>
                            </div>
            </a>
            ';
        }else{
            $output = '
                <h5 class="m-2"> Không có tin nhắn nào cả.</h5>
            ';
        }
        echo $output;
    }

    public function search_order(Request $request){
        $order_code = $request->order_code;
        $order_customer = $request->customer_id;

        $order = Order::where('order_code', $order_code)->where('customer_id',$order_customer)->get();

        $output = '';
        if($order){
            $output = $this->print_my_order($order);
        }else{
            $output = 'fail';    
        }
        return $output;
    }

    public function submit_order_check(Request $request){
        $order_id = $request->order_id;
        $status = $request->order_status;

        $order = Order::where("order_id", $order_id)->first();
        $payment = Payment::where('payment_id', $order->payment_id)->first();

        if($status == 'nhận'){
            $customer_boom = Customers::where('customer_id', $order->shipping->customer_id)->first();

            if($customer_boom){
                $customer_boom->total_order +=1;
                // $customer_boom->order_boom +=1;
                $customer_boom->save();
            }
            $order->order_status = 2;
            $payment->payment_status = 1;

            $order->save();
            $payment->save();

            $now = Carbon::now('Asia/Ho_Chi_Minh')->format('Y-m-d');

            $statical = Statistical::where('order_date', $now)->first();
            if($statical){
                $statical['sales'] = $statical['sales'] + $order->total_price;
                $statical['quantity'] = $statical['quantity'] + $order->total_quantity;
                $statical['total_order'] += 1;

                $statical->save();
            }else{
                // dd('huhu');
                $statis = new Statistical();
                $statis->order_date = $order->order_date;
                $statis->sales = $order->total_price;
                $statis->profit = 0;    
                $statis->quantity = $order->total_quantity;
                $statis->total_order = 1;
                $statis->save();
            }
        }else{
            $order->order_status = 3;
            $order->save();

            $customer_boom = Customers::where('customer_id', $order->shipping->customer_id)->first();

            if($customer_boom){
                $customer_boom->total_order +=1;
                $customer_boom->order_boom +=1;
                $customer_boom->save();
            }
            $now = Carbon::now('Asia/Ho_Chi_Minh')->format('Y-m-d');

            $statical = Statistical::where('order_date', $now)->first();
            if($statical){
                $statical['total_price_boom'] = $statical['total_price_boom'] + $order->total_price;
                $statical['quantity'] = $statical['quantity'] + $order->total_quantity;
                $statical['total_order'] += 1;
                $statical['order_boom'] += 1;
                $statical->save();
            }else{
                // dd('huhu');
                $statis = new Statistical();
                $statis->order_date = $order->order_date;
                $statis->sales = 0;
                $statis->order_boom = 1;
                $statis->total_price_boom = $order->total_price;    
                $statis->quantity = $order->total_quantity;
                $statis->total_order = 1;
                $statis->save();
            }
        }
        $order_print = Order::where("order_id", $order_id)->get();
        $output = $this->print_my_order($order_print);

        return $output;
    }

    public function comment_order(Request $request){
        $order_code = $request->order_code;
        $title_cm = $request->title_cm;
        $content_cm = $request->content_cm;

        $order_details = OrderDetails::whereIn('order_code',[$order_code])->get();
        // dd($order_details);
        if(session()->get('customer_id')){
            foreach($order_details as $order_detail){
                $comment_product = new CommentProduct();
                $comment_product['product_id'] = $order_detail->product_id;
                $comment_product['customer_id'] = session()->get('customer_id');
                $comment_product['title_comment'] = $title_cm;
                $comment_product['content_comment'] = $content_cm;
                $comment_product->save();
            }
            $order = Order::where('order_code', $order_code)->first();
            $order['order_status'] = 4;
            $order->save();
        }
        $output = 'success';
        return $output;
    }

    public function message($type, $content){
        $data = array(
            'type' => $type,
            'content' => $content
        );
        session()->put('message', $data);
    }

}
