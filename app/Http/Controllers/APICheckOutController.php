<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use App\Models\Category;
use App\Models\Customers;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Payment;
use App\Models\Shipping;
use Carbon\Carbon;
use Flashsale;
use Illuminate\Support\Facades\Mail;
use Product;
use ProductDetails;
use Session;

session_start();
class APICheckOutController extends Controller
{   

    public function checkPass(Request $request){
        $pass = $request->password;
        $id = $request->id;

        $customer = Customers::where("customer_id", $id)->where("customer_pass", $pass)->first();
        $data_new = array(
            'customer_name' => $customer->customer_name,
            'customer_phone' => $customer->customer_phone,
            'customer_email' => $customer->customer_email,
            'customer_password' => $customer->customer_password,
        );
        return response()->json([
            'data' => $data_new,
            'status_code' => 200,
            'message' => 'thành công',
        ]);
    }

    public function checkOut(Request $request){
        
        $order_code_rd = 'TGHSOD' . rand(0001, 999999);
        $customer_id = $request->customer_id;
        $cart = json_decode($request->cart ,true);
        $shipping = json_decode($request->shipping, true);
        $coupon = json_decode($request->coupon_product, true);
        $payment_method = $request->payment_method;
        $fee_ship = $request->product_fee;
        $total_price = $request->total_price;

        $payment = new Payment();
        $payment->payment_method = $payment_method;
        $payment->payment_status = 0;
        $payment->save();

        $shipping_new = new Shipping();
        $shipping_new->shipping_name = $shipping["shipping_name"];
        $shipping_new->shipping_email = $shipping["shipping_email"];
        $shipping_new->shipping_phone = $shipping["shipping_phone"];
        $shipping_new->shipping_address = $shipping["shipping_address"];
        if($shipping["shipping_notes"]){
            $shipping_new->shipping_notes = $shipping["shipping_notes"];
        }else{
            $shipping_new->shipping_notes = "Không có";
        }
        $shipping_new->shipping_special_requirements = 0;
        $shipping_new->shipping_receipt = 1;
        $shipping_new->save();

        $payment_old = Payment::orderBy("payment_id", "DESC")->first();
        $shipping_old = Shipping::orderBy("shipping_id", "DESC")->first();
        $total_quantity = 0;
        foreach($cart as $key => $value){
            $order_Detail = new OrderDetails();
            $order_Detail->order_code = $order_code_rd;
            $order_Detail->product_id = $value["product_id"];
            $order_Detail->product_name = $value["product_name"];
            $order_Detail->product_price = $value["product_price"];
            $order_Detail->product_sales_quantity = $value["product_quantity"];
            $order_Detail->save();
            $total_quantity = $total_quantity + $value["product_quantity"];
        }
        
        $order = new Order();
        $order->customer_id = $customer_id;
        $order->shipping_id = $shipping_old->shipping_id;
        $order->payment_id = $payment_old->payment_id;
        $order->order_status = 0;
        $order->order_code = $order_code_rd;
        $order->product_fee = $fee_ship;
        if($coupon != null){
            $order->product_coupon = $coupon["coupon_name_code"];
            if($coupon["coupon_price_sale"] > 100){
                $order->product_price_coupon = $coupon["coupon_price_sale"];
            }else{
                $coupon_price = ($total_price * $coupon["coupon_price_sale"]) / 100;
                $order->product_price_coupon = $coupon_price;
            }
        }
        else{
            $order->product_price_coupon = 0;
        }
        $order->total_price = $total_price;
        $order->total_quantity = $total_quantity;
        $order->order_date = Carbon::now('Asia/Ho_Chi_Minh')->format('Y-m-d');
        $order->save();

        $this->email_order_to_customer();
        
        $order_new = Order::where("order_code", $order_code_rd)->get();

        return $this->fetchOrder($order_new);
    }

    public function fetchOrder($order){
        if($order->count() > 0){
        
            foreach ($order as $value) {
 
             $shipping_id = $value->shipping_id;
             $payment_id = $value->payment_id;
             $order_code = $value->order_code;
 
             $payment = Payment::where("payment_id" , $payment_id)->first()->toArray(); 
             $shipping = Shipping::where("shipping_id" , $shipping_id)->first()->toArray(); 
             $orderdetails = OrderDetails::where("order_code" , $order_code)->get(); 
             $data_orde = null;
             foreach ($orderdetails as $v) {
                 $data_orde[] = array(
                     'order_details_id' => $v->order_details_id,
                     'order_code' => $v->order_code,
                     'product_id' => $v->product_id,
                     'product_name' => $v->product_name,
                     'product_price' => $v->product_price,
                     'product_image' =>'http://192.168.1.7/DoAnCNWeb/public/fontend/assets/img/product/'.$v->product->product_image,
                     'category_id' => $v->product->category_id,
                     'category_name' => $v->product->category->category_name,
                     'product_sales_quantity' => $v->product_sales_quantity,
                     'created_at' =>  $v->created_at,
                     'updated_at' => $v->updated_at,
                     );
               }
 
             $data[] = array(
                 'order_id' => $value->order_id,
                 'customer_id' => $value->customer_id,
                 'shipping_id' => $value->shipping_id,
                 'payment_id' => $value->payment_id,
                 'order_status' => $value->order_status,
                 'order_code' => $value->order_code,
                 'product_fee' => $value->product_fee,
                 'product_coupon' =>  $value->product_coupon,
                 'product_price_coupon' => $value->product_price_coupon,
                 'total_price' => $value->total_price,
                 'total_quantity' => $value->total_quantity,
                 'order_date' => $value->order_date,
                 'shipping'  => $shipping,
                 'payment'  => $payment,
                 'orderDetails' => $data_orde,
                 'created_at' => $value->created_at,
                 'updated_at' => $value->updated_at,
                 );
             }
 
            return response()->json([
             'data' =>  $data,
             'status_code' => 200,
             'message' => 'Thành Công !',
            ]);
       
 
         }else{
             return response()->json([
                 'data' => null,
                 'status_code' => 404,
                 'message' => 'Không có dữ liệu trả về !',
             ]) ;
         }
    }

    public function saveShipping($user){
        $shipping = new Shipping();
        $shipping->shipping_name = $user["shipping_name"];
        $shipping->shipping_email = $user["shipping_email"];
        $shipping->shipping_phone = $user["shipping_phone"];
        $shipping->shipping_address = $user["shipping_address"];
        $shipping->shipping_notes = "Không có";
        $shipping->shipping_special_requirements = "Không có";
        $shipping->save();
    }

    public function saveOrder($order){
        $payment = Payment::orderBy("payment_id", "DESC")->first();
        $shipping = Shipping::orderBy("shipping_id", "DESC")->first();
        $order_code_rd = session()->get("order_code_rd");
        $order = new Order();
        $order->customer_id = $order->customer_id;
        $order->shipping_id = $shipping->shipping_id;
        $order->payment_id = $payment->payment_id;
        $order->order_status = 0;
        $order->order_code = $order_code_rd;
        $order->product_fee = 20000;
        $order->product_coupon = "Không có";
        // if($coupon_name_code == "Không có")
        $order->product_price_coupon = 0;
        // else{
        //     $order->product_price_coupon = $coupon_get->coupon_price_sale;
        // }
        $order->total_price = $order->total_price;
        $order->total_quantity = $order->product_quantity;
        $order->order_date = Carbon::now('Asia/Ho_Chi_Minh')->format('Y-m-d');
        $order->save();
    }

    public function saveOrder_Detail($order_details){
        $order_code_rd = session()->get('order_code_rd');
        
    }

    public function email_order_to_customer()
    {
        $shipping = Shipping::orderBy("shipping_id", "DESC")->first();
        $order_code_rd = session()->get('order_code_rd');
        // $shipping = session()->get('shipping');
        $order_details = OrderDetails::where("order_code", $order_code_rd)->get();
        $cart = session()->get('cart');
        $fee = 20000;
        $coupon = session()->get('coupon-cart');
        $mail_customer = $shipping['shipping_email'];
        $to_name = "Lê Khả Nhân - Mail Laravel";
        $to_email = "$mail_customer";

        $data = array(
            "order_code_rd" => "$order_code_rd",
            "shipping" => $shipping,
            "cart" => $cart,
            "fee" => $fee,
            "coupon" => $coupon,
        );

        Mail::send('pages.hoadon.email_khachhang', $data, function ($message) use ($to_name, $to_email) {
            $message->to($to_email)->subject("Thế Giới Hải Sản - Đơn Hàng Của Bạn Đang Chờ Xử Lý !"); //send this mail with subject
            $message->from($to_email, $to_name); //send from this mail
        });
    }

}
