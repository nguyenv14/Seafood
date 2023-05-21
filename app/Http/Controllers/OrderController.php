<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Customers;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Payment;
use App\Models\Shipping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use PDF;
use Mail;
use App\Models\ManipulationActivity;

session_start();

class OrderController extends Controller
{
    public function manager_order()
    {
        $order = Order::orderby('created_at', 'DESC')->get();
        ManipulationActivity::noteManipulationAdmin("Xem Bảng Quản Lý Đơn Hàng");
        return view('admin.Order.order_manager')->with(compact('order'));
    }
    public function loading_manager_order(){
      /** sub
       * order_status : 0 ->  Đang chờ duyệt ,  -1 ->  Đơn Hàng Bị Từ Chối ,  1  ->  Đã Duyệt, Đang Vận Chuyển , 2 -> Hoàn Thành Đơn Hàng , 3 -> Đơn Bị Hoàn Trả
      **/
      $order = Order::orderby('created_at', 'DESC')->get();
      $output = '';
      $i = 1;
      foreach ($order as $key => $value_order){
        $output .= '
        <tr>
        <td>'.$i++.'</td>
        <td>'. $value_order->order_code.'</td>
        <td>';
            if($value_order->order_status == 0){
                $output .= '<span class="text-info"><b>Đang chờ duyệt</b></span>';
            }else if($value_order->order_status == -1) {
                $output .= '<span class="text-danger"><b>Đơn Hàng Bị Từ Chối</b></span>';
            }else if($value_order->order_status == 1) {
                $output .= '<span class="text-warning"><b>Đã Duyệt, Đang Vận Chuyển</b></span>';
            }else if($value_order->order_status == 2) {
                $output .= '<span class="text-success"><b>Hoàn Thành Đơn Hàng</b></span>';
            }
            else if($value_order->order_status == 3) {
                $output .= '<span class="text-danger"><b>Đơn Bị Hoàn Trả</b></span>';
            }
        $output .= '
        </td>
        <td>';
        if($value_order->payment->payment_method == 4){
            $output .= 'Khi Nhận Hàng';
        }else if($value_order->payment->payment_method == 1){
            $output .= 'Thanh Toán Momo';
        }
        $output .= '
        </td>
        <td>';
        if($value_order->payment->payment_status == 0){
            $output .= 'Chưa Thanh Toán';
        }else if($value_order->payment->payment_status == 1){
            $output .= 'Đã Thanh Toán';
        }
        $output .= '
        </td>
        <td>'. $value_order->created_at.'</td>
        <td>';
        if($value_order->order_status == 0){
        $output .= '
        <button style="margin-top:10px" class="btn-sm btn-gradient-success btn-rounded btn-fw btn-order-status" data-order_code="'.$value_order->order_code.'" data-order_status="1">Duyệt Đơn <i class="mdi mdi-calendar-check"></i></button> <br>
        <button style="margin-top:10px" class="btn-sm btn-gradient-danger btn-fw btn-order-status"  data-order_code="'.$value_order->order_code.'" data-order_status="-1" >Từ Chối <i class="mdi mdi-calendar-remove"></i></button> <br>';
        }else if($value_order->order_status == 1){
            $output .= '<button style="margin-top:10px" class="btn-sm btn-gradient-success btn-order-status"  data-order_code="'.$value_order->order_code.'" data-order_status="2">Hoàn Thành Đơn Hàng <i class="mdi mdi-calendar-check"></i></button> <br>';
            $output .= '<button style="margin-top:10px" class="btn-sm btn-gradient-danger btn-fw btn-order-status" data-order_code="'.$value_order->order_code.'" data-order_status="3">Đơn Hàng Hoàn Trả <i class="mdi mdi-calendar-remove"></i></button> <br>';
        }
        if($value_order->order_status == -1 || $value_order->order_status == 2 || $value_order->order_status == 3){
            $output .= '<a href="'.URL('admin/order/delete-order?order_id=' . $value_order->order_code).'"><button style="margin-top:10px" class="btn-sm btn-gradient-dark btn-rounded btn-fw">Xóa Đơn <i class="mdi mdi-delete-sweep"></i></button></a>';
        }

        $output .= '
        <a href="'.URL('admin/order/view-order?order_code=' . $value_order->order_code).'"><button style="margin-top:10px" class="btn-sm btn-gradient-info btn-rounded btn-fw">Xem Đơn <i class="mdi mdi-eye"></i></button></a> <br>
        </td>
    </tr>';
      }
      echo $output;
     
    }
    public function view_order(Request $request)
    {
        $order_code = $request->order_code;

        $order = Order::where('order_code', $order_code)->first();

        $customer_id = $order['customer_id'];
        $shipping_id = $order['shipping_id'];

        $customer = Customers::where('customer_id', $customer_id)->first();
        $shipping = Shipping::where('shipping_id', $shipping_id)->first();
        $orderdetails = OrderDetails::where('order_code', $order_code)->with('Product')->get();
     
        
        
        ManipulationActivity::noteManipulationAdmin("Xem Bảng Chi Tiết Đơn Hàng");
        return view('admin.Order.view_order')->with(compact('orderdetails', 'customer', 'shipping', 'order'));
    }

    public function order_status(Request $request){
        $order = Order::where('order_code',$request->order_code)->first();
        $order->order_status = $request->order_status;
        $order->save();
        /* Còn Thiếu Xử Lý Về Sau Này */
        if($request->order_status == 1 || $request->order_status == -1){
            $this->email_order_to_customer($request->order_code , $request->order_status);
        }
        if($request->order_status == -1){
            ManipulationActivity::noteManipulationAdmin("Hủy Đơn Hàng ( Order Code : ".$request->order_code.")");
            echo "refuse";
        }else if($request->order_status == 1){
            ManipulationActivity::noteManipulationAdmin("Duyệt Đơn Hàng ( Order Code : ".$request->order_code.")");
            echo "browser";
        }
        else if($request->order_status == 2){
            ManipulationActivity::noteManipulationAdmin("Đơn Hàng Hoàn Thành( Order Code : ".$request->order_code.")");
            echo "success";
        }else if($request->order_status == 3){
            ManipulationActivity::noteManipulationAdmin("Đơn Hàng Hoàn Trả ( Order Code : ".$request->order_code.")");
            echo "return";
        }
       
    }

    public function email_order_to_customer($order_code , $order_status )
    {
       
        $order = Order::where('order_code',$order_code)->first();
        $orderdetails = OrderDetails::where('order_code',$order_code)->get();
        // $subject = '';
        if( $order_status == 1){
            $type = "Đơn Hàng ".$order->order_code." Đã Được Duyệt !";
            $subject =  "Thế Giới Hải Sản - Đơn Hàng Của Bạn Đã Được Duyệt !";
        }else if($order_status == -1){
            $type = "Đơn Hàng ".$order->order_code." Đã Bị Từ Chối !";
            $subject =  "Thế Giới Hải Sản - Đơn Hàng Của Bạn Đã Bị Từ Chối !";
        }
       
        $to_name = "Lê Khả Nhân - Mail Laravel";
        $to_email =  $order->shipping->shipping_email;
      
        $data = array(
            "type" => $type,
            "order" => $order,
            "orderdetails" => $orderdetails,
        ); 
        Mail::send('admin.Order.email_order_to_customer_submit', $data, function ($message) use ($to_name, $to_email , $subject) {
            $message->to($to_email)->subject($subject); //send this mail with subject
            $message->from($to_email, $to_name); //send from this mail
        });
    

    }
    public function print_order(Request $request){
        $order_code = $request->checkout_code;
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($this->pdf_view($order_code));
        // return $pdf->download('hoadon.pdf');
        return $pdf->stream();
    }

    public function pdf_view($order_code){
        $order = Order::where('order_code', $order_code)->first();

        $order_details = OrderDetails::where('order_code', $order_code)->get();

        $coupon = Coupon::where('coupon_name_code', $order->product_coupon)->first();

        $shipping = Shipping::where('shipping_id', $order->shipping_id)->first();
        $total_price = 0;
        $i = 0;
        $output ='
        <style>
            body{
                font-family: DejaVu Sans;
            }
        </style>
        <div class="" style="display: flex; justify-content: center;"> 
        <table style="margin-left: 60px">
            <tr>
                <td>
                    <img src="https://iweb.tatthanh.com.vn/pic/3/blog/images/image(3328).png" alt="" width="150px" >

                </td>
                <td></td>
                <td></td>
                <td>
                    <h2>
                        Thế Giới Hải Sản -  Seafood
                    </h2>
                    <h3>Address: 470 - Tran Dai Nghia - Ngu Hanh Son</h3>
                    <h3>Phone: 0839519415</h3>
                </td>
            </tr>
        </table>
    </div>
    <div style="text-align: center;">
        <h2>Customer Name: '.$shipping->shipping_name.'</h2>
        <h3>Phone: '.$shipping->shipping_phone.'</h3>
    </div>
    <div style="text-align: center;">
        <h3>BILL - '.$order_code.'</h3>
        
        <div style="display: flex; justify-content: center;">
            <table border="0" style="width: 550px; border-collapse: collapse;margin: 20px auto">
                <thead style="height: 60px;">
                    <tr style="border-bottom: 3px solid black;">
                        <th>STT</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total Price</th>
                    </tr>
                </thead>
                <tbody>';
                foreach($order_details as $order_detail){
                    $i++;
                    $output .= '
                    <tr style="height: 40px;">
                        <th>'.$i.'</th>
                        <th>'.$order_detail->product_name.'</th>
                        <th>'.$order_detail->product_sales_quantity.'</th>
                        <th>'.number_format($order_detail->product_price, 0, ',', '.').' đ</th>
                        <th>'.number_format($order_detail->product_price * $order_detail->product_sales_quantity, 0, ',', '.').' đ</th>
                    </tr>';

                    $total_price = $total_price + $order_detail->product_price * $order_detail->product_sales_quantity;
                }
                $fee_ship = $order->product_fee;
                $coupon_sale = 0;
                if($order->product_coupon != 'Không có'){
                    if($order->product_price_coupon <= 100){
                        $coupon_sale = ($total_price / 100) * $order->product_price_coupon;
                    }else{
                        if($order->product_price_coupon > $total_price){
                            $coupon_sale = $product_price_coupon;
                        }else{
                            $coupon_sale = $order->product_price_coupon;
                        }
                    }
                }else{
                    $coupon_sale = 0;
                }
                 $output .= '   
                </tbody>
                <tfoot>
                    <tr style="border-top:3px solid black; height: 40px;">
                        <th colspan="3">
                           Total Price
                        </th>
                        <th colspan="2">'.number_format($total_price, 0, ',', '.').' đ</th>
                    </tr>
                </tfoot>
            </table>
        
            
        </div>
        <div style="display: flex;justify-content: center;margin: 20px auto">
            <table border="2" style="width: 450px; border-collapse: collapse;margin: 20px auto">
                <tr>
                    <th>Coupon</th>';
                    if($coupon_sale == 0){
                        $output .= '<th>Không Có</th>';
                    }else{
                        $output .= '<th> '.$order->product_coupon.' - '.number_format($coupon_sale, 0, ',', '.').' đ</th>';
                    }
                $output .= '</tr>
                    <tr>
                    <th>Fee Ship</th>
                    <th>'.number_format($fee_ship, 0, ',', '.').'đ</th>
                </tr>
                <tr>
                    <th>Payment</th>
                    <th>'.number_format($total_price + $fee_ship - $coupon_sale, 0, ',', '.').'đ</th>
                </tr>
            </table>
        </div>
        <span style="display: block;margin-top: 20px; font-family: monospace; font-weight: 900;">Wishing '.$shipping->shipping_name.' Happy Customers, See you again!</span>
    </div>';
        return $output; 
    }

}
