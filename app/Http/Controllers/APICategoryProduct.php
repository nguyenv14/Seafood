<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Shipping;
use Flashsale;
use ProductDetails;
use Session;

session_start();
class APICategoryProduct extends Controller
{   
    public function getOrder(Request $request){
        $customer_id = $request->customer_id;
        $order_status = $request->order_status;
        $order = null;
        if($order_status == -1){
            $order = Order::where("customer_id" , $customer_id)->whereIn("order_status", [$order_status, 3])->orderBy("order_id", "DESC")->get(); 
        }else if($order_status == 4){
            $order = Order::where("customer_id" , $customer_id)->whereIn("order_status", [$order_status, 2])->orderBy("order_id", "DESC")->get(); 
        }else{
            $order = Order::where("customer_id" , $customer_id)->where("order_status", $order_status)->orderBy("order_id", "DESC")->get(); 
        }
        return $this->fetchOrder($order);
    }

    public function getEvaluateOrder(Request $request){
        $customer_id = $request->customer_id;
        $order_status = $request->order_status;
        $order = null;
    
            $order = Order::where("customer_id" , $customer_id)->where("order_status", $order_status)->orderBy("order_id", "DESC")->get(); 
        return $this->fetchOrder($order);
    }

    public function searchOrder(Request $request){
        $customer_id = $request->customer_id;
        $order_status = $request->order_status;
        $order_code = $request->order_code;
        $order = Order::where("customer_id" , $customer_id)->where("order_status", $order_status)->where("order_code","like","%".$order_code."%")->get(); 
        return $this->fetchOrder($order);
    }

    public function getOrderDetails(Request $request){
        $order = Order::where("order_code", $request->order_code)->take(1)->get();
        return $this->fetchOrder($order);
    }

    public function orderCancel(Request $request){
        $order = Order::where("order_code", $request->order_code)->first();
        $order->order_status = 3;
        $order->save();

        $order_new = Order::where("order_code", $request->order_code)->take(1)->get();
        return $this->fetchOrder($order_new);
    }
    public function orderReceive(Request $request){
        $order = Order::where("order_code", $request->order_code)->first();
        $order->order_status = 2;
        $order->save();
        $order_new = Order::where("order_code", $request->order_code)->take(1)->get();
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
    
    public function add_category()
    {
        return view('admin.Category.add_category');
    }

    public function getCategory()
    {  
        $all_category = Category::get(); 
        if($all_category->count() > 0){
            
            foreach ($all_category as $key => $value) {
                $product = Product::where("category_id", $value->category_id)->where("product_status", 1)->first();
                if($product){
                    $data[] = array(
                        
                            "category_id" => $value->category_id,
                            "category_name" => $value->category_name,
                            "category_desc" => $value->category_desc,
                            "category_image" => 'http://192.168.1.7/DoAnCNWeb/public/fontend/assets/img/category/'.$value->category_image,
                            "category_status" => $value->category_status,
                    );
                }
            }
            return response()->json([
                'status_code' => 200,
                'message' => 'ok',
                'data' => $data,
            ]) ;
        }else{

            return response()->json([
                'status_code' => 404,
                'message' => 'Không có dữ liệu trả về !',
                'data' => null,
            ]) ;
        }
        
    }

}
