<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Customers;
use App\Models\Product;
use App\Models\Slider;
use App\Models\OrderDetails;
use App\Models\GalleryProduct;
use App\Models\Comment;

use App\Models\Wards;
use App\Models\City;
use App\Models\Province;
use App\Models\Feeship;
use App\Models\Coupon;

use App\Models\Shipping;
use App\Models\Payment;
use App\Models\Order;

use Carbon\Carbon;
use DB;
use Flashsale;
use Illuminate\Http\Request;


session_start();
class RepositoryAPIController extends Controller
{

    public function putOrder(Request $request){
        $data_payment =  json_decode($request->input('payment'));
        $data_shipping = json_decode($request->input('shipping'));
        $data_cart =  json_decode($request->input('cart'));
        $data_coupon = json_decode($request->input('coupon'));
        // Thêm dữ liệu vào bảng payment
        $payment = new Payment();
        $payment['payment_method'] = $data_payment->payment_method;
        $payment['payment_status'] = $data_payment->payment_status;
        $payment->save();
        $payment_id = DB::getPdo('tbl_payment')->lastInsertId();
        // Thêm Dữ Liệu Vào Bảng Shipping
        $shipping = new Shipping();
        $shipping->shipping_name = $data_shipping->shipping_name;
        $shipping->shipping_phone = $data_shipping->shipping_phone;
        $shipping->shipping_email = $data_shipping->shipping_email;
        $shipping->shipping_address = $data_shipping->shipping_address;
        $shipping->shipping_notes = $data_shipping->shipping_notes;
        $shipping->shipping_special_requirements = $data_shipping->shipping_special_requirements;
        $shipping->shipping_receipt = $data_shipping->shipping_receipt;
        $shipping->save();
        $shipping_id = DB::getPdo('tbl_shipping')->lastInsertId();
        if($data_coupon == null){
            $coupon_name_code = 'Không có';
        }else{
            $coupon_name_code = $data_coupon->coupon_name_code;
            $coupon_get = Coupon::where('coupon_name_code', $coupon_name_code)->first();
            if($coupon_get){
                if($coupon_get->coupon_qty_code > 0){
                    $coupon_get->coupon_qty_code -=1;
                    $coupon_get->save();
             }
            }
        }
        $ordercode = substr($request->ordercode,1);
        $ordercode = substr($ordercode, 0, -1); 
        $order_code_rd =$ordercode;

        $total_quantity = 0;
        $total_price_product = 0;
        $customer_id = -1;
        /* Lưu Dữ Liệu Vào Bảng OrderDetails */
        foreach ($data_cart as $cart) {
            $orderdetails = new OrderDetails();
            $orderdetails->order_code = $order_code_rd;
            $orderdetails->product_id = $cart->product_id;
            $orderdetails->product_name = $cart->product_name;
            $orderdetails->product_price = $cart->product_price;
            $orderdetails->product_sales_quantity = $cart->product_quantity;
            $orderdetails->save();
            $customer_id = $cart->customer_id;
            $total_quantity = $total_quantity + $cart->product_quantity;
            $total_price_product = $cart->product_quantity * $cart->product_price;
        }
           //Thêm Dữ Liệu Vào Bảng Order
           $order = new Order();
           $order->customer_id = $customer_id; 
           $order->shipping_id = $shipping_id;
           $order->payment_id = $payment_id;
           $order->order_status = 0;
           $order->order_code = $order_code_rd;
           $order->product_fee = $request->deliveringfee;
           $order->product_coupon = $coupon_name_code;
           if($coupon_name_code == 'Không có')
               $order->product_price_coupon = 0;
           else{
                if($coupon_get->coupon_price_sale > 100){
                    $order->product_price_coupon = $coupon_get->coupon_price_sale;
                    $total_price_product = $total_price_product - $coupon_get->coupon_price_sale;
                }else{
                    $order->product_price_coupon = ($total_price_product * $coupon_get->coupon_price_sale) /100;
                    $total_price_product = $total_price_product - ($total_price_product * $coupon_get->coupon_price_sale) /100;
                }
             
           }
           $order->total_price =  $total_price_product + $request->deliveringfee;
           $order->total_quantity = $total_quantity;
           $order->order_date = Carbon::now('Asia/Ho_Chi_Minh')->format('Y-m-d');
           $order->save();

           $order_new = Order::orderBy("order_id", "DESC")->take(1)->get();
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
             ]);
         }
    }

    public function deleteOrder(Request $request){
        $order_code = $request->order_code;
        $payment_id = $request->payment_id;
        $shipping_id = $request->shipping_id;

        $order = Order::where("order_code", $order_code)->first();
        $payment = Payment::where("payment_id", $payment_id)->first();
        $shipping = Shipping::where("shipping_id", $shipping_id)->first();

        $order->delete();
        $payment->delete();
        $shipping->delete();

        $order_details = Order::where("order_code", $order_code)->get();

        foreach($order_details as $value){
            $value->delete();
        }

        return response()->json([
            'data' => null,
            'status_code' => 200,
            'message' => 'Không có dữ liệu trả về!',
        ]);
    }

    public function getDeliveringFee(Request $request){
        $city = City::where('name_city',$request->name_city)->first();
        $province = Province::where('name_province',$request->name_province)->first();
        $wards = Wards::where('name_ward' , $request->name_ward)->first();

        if($city == null || $province == null  || $province == null){
            return response()->json([
                'data' =>99999999,
                'status_code' => 400,
                'message' => 'ok',
            ]);
        }else{
            $feeship = Feeship::where('fee_matp',$city->matp)->where('fee_maqh',$province->maqh)->where('fee_maxp',$wards->xaid)->first();
            if($feeship == null){
                return response()->json([
                    'data' =>30000,
                    'status_code' => 200,
                    'message' => 'ok',
                ]);
            }else{
                return response()->json([
                'data' => $feeship->fee_feeship,
                'status_code' => 200,
                'message' => 'ok',
                ]);
            }
        }
    }

    public function checkCoupon(Request $request){
        $coupon = Coupon::where('coupon_name_code',$request->coupon_name_code)->get();
        if($coupon->count() > 0){
            return response()->json([
                'data' => $coupon->toarray(),
                'status_code' => 200,
                'message' => 'ok',
            ]);
        }else{
            return response()->json([
                'data' => null,
                'status_code' => 404,
                'message' => 'ok',
            ]);
        }
    }

    public function testAPI(){
        $wards = Wards::take(10)->get();
        if($wards->count() > 0){
            foreach ($wards as $value) {
                $data[] = array(
                    'id' => $value->xaid,
                    'text1' => $value->name_ward,
                    'text2' => $value->type,
                );
            }
            return response()->json($data);
        }
    }

    public function getDataWards(Request $request){

        $province = Province::where('name_province',$request->name_province)->first();

        $wards = Wards::where('maqh' , $province->maqh)->get();
        if($wards->count() > 0){
            foreach ($wards as $value) {
                $data[] = array(
                    'xaid' => $value->xaid,
                    'name_ward' => $value->name_ward,
                    'type' => $value->type,
                    'maqh' => $value->maqh,
                );
            }
            return response()->json([
                'data' => $data,
                'status_code' => 200,
                'message' => 'ok',
            ]);
        }else{
            return response()->json([
                'data' => null,
                'status_code' => 404,
                'message' => 'ok',
            ]);
        }
    }
    

    public function getDataProvince(Request $request){

        $city = City::where('name_city',$request->name_city)->first();

        $province = Province::where('matp' , $city->matp)->get();
        if($province->count() > 0){
            foreach ($province as $value) {
                $data[] = array(
                    'maqh' => $value->maqh,
                    'name_province' => $value->name_province,
                    'type' => $value->type,
                    'matp' => $value->matp,
                );
            }
            return response()->json([
                'data' => $data,
                'status_code' => 200,
                'message' => 'ok',
            ]);
        }else{
            return response()->json([
                'data' => null,
                'status_code' => 404,
                'message' => 'ok',
            ]);
        }
    }
    

    public function getDataCity(){
        $city = City::get();
        if($city->count() > 0){
            foreach ($city as $value) {
                $data[] = array(
                    'matp' => $value->matp,
                    'name_city' => $value->name_city,
                    'type' => $value->type,
                );
            }
            return response()->json([
                'data' => $data,
                'status_code' => 200,
                'message' => 'ok',
            ]);
        }
    }

    public function get_product_v2(Request $request){
  
        $product = Product::paginate(4);
        if ($product->count() > 0) {
            foreach ($product as $value) {
                $all_product_data[] = array(
                    'product_id' => $value->product_id,
                    'category_id' => $value->category_id,
                    'category_name' => $value->category->category_name,
                    'product_name' => $value->product_name,
                    'product_desc' => $value->product_desc,
                    'product_price' => $value->product_price,
                    'product_image' => 'http://192.168.1.12/DoAnCNWeb/public/fontend/assets/img/product/' . $value->product_image,
                );
            }
            return response()->json([
                'data' => $all_product_data,
                'status_code' => 200,
                'message' => 'ok',
            ]);
        } else {
            return response()->json([
                'data' => null,
                'status_code' => 404,
                'message' => 'Không có dữ liệu trả về !',
            ]);
        }
    }
    public function evaluate_product(Request $request){
        $Comments = Comment::where('comment_product_id',$request->product_id)->get();
        if( $Comments->count() > 0){
            foreach ($Comments as $value) {
                $data[] = array(
                    'comment_id' =>  $value->comment_id,
                    'comment_title' => $value->comment_title,
                    'comment_content' => $value->comment_content,
                    'comment_customer_id' => $value->comment_customer_id,
                    'comment_customer_name' => $value->comment_customer_name,
                    'comment_product_id' => $value->comment_product_id,
                    'comment_rate_star' => $value->comment_rate_star,
                );
            }

            return response()->json([
                'status_code' => 200,
                'message' => 'Ok',
                'data' => $data,
            ]);

        }else{
            return response()->json([
                'status_code' => 404,
                'message' => 'Không có dữ liệu trả về !',
                'data' => null,
            ]);
        }
    }
    public function product_by_category(Request $request){
        $product_by_category = Product::where('category_id',$request->category_id)->where('product_status', 1)->where('flashsale_status', '0')->get();

        if ($product_by_category->count() > 0) {
            foreach ($product_by_category as $value) {
                $all_product_data[] = array(
                    'product_id' => $value->product_id,
                    'category_id' => $value->category_id,
                    'category_name' => $value->category->category_name,
                    'product_name' => $value->product_name,
                    'product_desc' => $value->product_desc,
                    'product_price' => $value->product_price,
                    'product_image' => 'http://192.168.1.12/DoAnCNWeb/public/fontend/assets/img/product/' . $value->product_image,
                );
            }
            return response()->json([
                'data' => $all_product_data,
                'status_code' => 200,
                'message' => 'ok',
            ]);
        } else {
            return response()->json([
                'data' => null,
                'status_code' => 404,
                'message' => 'Không có dữ liệu trả về !',
            ]);
        }
    }
    public function all_gallary_product(Request $request){
        $galleryProduct =  GalleryProduct::where('product_id' , $request->product_id)->get();
        if($galleryProduct->count() > 0){
            foreach ($galleryProduct as $value) {
                $result_arr[] = array(
                    'gallery_product_id' => $value->gallery_product_id,    
                    'product_id' => $value->product_id,    
                    'gallery_product_name' => $value->gallery_product_name,  
                    'gallery_product_image' => 'http://192.168.1.12/DoAnCNWeb/public/fontend/assets/img/product/' . $value->gallery_product_image,
                    'gallery_product_content' => $value->gallery_product_content,
                );
            }
            return response()->json([
                'status_code' => 200,
                'message' => 'ok',
                'data' => $result_arr,
            ]);
        }else {
            return response()->json([
                'status_code' => 404,
                'message' => 'Không có dữ liệu trả về !',
                'data' => null,
            ]);
        }
    }
    public function all_order_product()
    {
        /* Thuật Toán Sản Phẩm Flashsale - Dựa Vào Flashsale Status */
        $flashsale = Flashsale::where('flashsale_status', '1')->take(6)->get();
        /* Thuật Toán Sản Phẩm Bán Chạy - Dựa Vào OrderDetails */
        $list_id_product_order = array();
        $orderDetails = OrderDetails::get();
        foreach ($orderDetails as $key => $v_orderDetails) {
            $product_not_flashsale = Product::where('product_id', $v_orderDetails->product_id)->where('flashsale_status', 0)->first();
            if ($product_not_flashsale) {
                $list_id_product_order[$key] = $v_orderDetails->product_id;
            }
        }
        $i = 0;
        $list_id_product_order = array_unique($list_id_product_order);
        $list_id_product_order_5 = array();
        foreach ($list_id_product_order as $key => $v_list_id_product_order) {
            $list_id_product_order_5[$key] = $v_list_id_product_order;
            $i++;
            if ($i == 4) {
                break;
            }
        }
        $best_sale_product = Product::wherein('product_id', $list_id_product_order_5)->where('product_status', 1)->where('flashsale_status', '0')->get();
        /* Kết Thúc Thuật Toán */
        if ($best_sale_product->count() > 0) {
            foreach ($best_sale_product as $value) {
                $all_product_data[] = array(
                    'product_id' => $value->product_id,
                    'category_id' => $value->category_id,
                    'category_name' => $value->category->category_name,
                    'product_name' => $value->product_name,
                    'product_desc' => $value->product_desc,
                    'product_price' => $value->product_price,
                    'product_image' => 'http://192.168.1.12/DoAnCNWeb/public/fontend/assets/img/product/' . $value->product_image,
                );
            }
            return response()->json([
                'data' => $all_product_data,
                'status_code' => 200,
                'message' => 'ok',
            ]);
        } else {
            return response()->json([
                'data' => null,
                'status_code' => 404,
                'message' => 'Không có dữ liệu trả về !',
            ]);
        }

    }
    public function all_trending_product(){
                /* Thuật Toán Sản Phẩm Flashsale - Dựa Vào Flashsale Status */
                $flashsale = Flashsale::where('flashsale_status', '1')->take(6)->get();
                /* Thuật Toán Sản Phẩm Bán Chạy - Dựa Vào OrderDetails */
                $list_id_product_order = array();
                $orderDetails = OrderDetails::get();
                foreach ($orderDetails as $key => $v_orderDetails) {
                    $product_not_flashsale = Product::where('product_id', $v_orderDetails->product_id)->where('flashsale_status', 0)->first();
                    if ($product_not_flashsale) {
                        $list_id_product_order[$key] = $v_orderDetails->product_id;
                    }
                }
                $i = 0;
                $list_id_product_order = array_unique($list_id_product_order);
                $list_id_product_order_5 = array();
                foreach ($list_id_product_order as $key => $v_list_id_product_order) {
                    $list_id_product_order_5[$key] = $v_list_id_product_order;
                    $i++;
                    if ($i == 4) {
                        break;
                    }
                }
                $best_sale_product = Product::wherein('product_id', $list_id_product_order_5)->where('product_status', 1)->where('flashsale_status', '0')->get();
                /* Kết Thúc Thuật Toán */
                /* Thuật Toán Sản Phẩm Đang Thịnh Hành - Dựa Vào Viewer */
                $viewer_product = Product::where('product_status', 1)->where('flashsale_status', '0')->wherenotin('product_id', $list_id_product_order_5)->orderby('product_viewer', 'DESC')->take(5)->get();
                // $list_id_viewer_product = array();
                // foreach ($viewer_product as $key => $v_viewer_product) {
                //     $list_id_viewer_product[$key] = $v_viewer_product->product_id;
                // }
                /* Kết Thúc Thuật Toán */
                if ($viewer_product->count() > 0) {
                    foreach ($viewer_product as $value) {
                        $all_product_data[] = array(
                            'product_id' => $value->product_id,
                            'category_id' => $value->category_id,
                            'category_name' => $value->category->category_name,
                            'product_name' => $value->product_name,
                            'product_desc' => $value->product_desc,
                            'product_price' => $value->product_price,
                            'product_image' => 'http://192.168.1.12/DoAnCNWeb/public/fontend/assets/img/product/' . $value->product_image,
                        );
                    }
                    return response()->json([
                        'data' => $all_product_data,
                        'status_code' => 200,
                        'message' => 'ok',
                    ]);
                } else {
                    return response()->json([
                        'data' => null,
                        'status_code' => 404,
                        'message' => 'Không có dữ liệu trả về !',
                    ]);
                }
    }

    public function all_new_product(){
        /* Thuật Toán Sản Phẩm Flashsale - Dựa Vào Flashsale Status */
        $flashsale = Flashsale::where('flashsale_status', '1')->take(6)->get();
        /* Thuật Toán Sản Phẩm Bán Chạy - Dựa Vào OrderDetails */
        $list_id_product_order = array();
        $orderDetails = OrderDetails::get();
        foreach ($orderDetails as $key => $v_orderDetails) {
            $product_not_flashsale = Product::where('product_id', $v_orderDetails->product_id)->where('flashsale_status', 0)->first();
            if ($product_not_flashsale) {
                $list_id_product_order[$key] = $v_orderDetails->product_id;
            }
        }
        $i = 0;
        $list_id_product_order = array_unique($list_id_product_order);
        $list_id_product_order_5 = array();
        foreach ($list_id_product_order as $key => $v_list_id_product_order) {
            $list_id_product_order_5[$key] = $v_list_id_product_order;
            $i++;
            if ($i == 4) {
                break;
            }
        }
        $best_sale_product = Product::wherein('product_id', $list_id_product_order_5)->where('product_status', 1)->where('flashsale_status', '0')->get();
        /* Kết Thúc Thuật Toán */
        /* Thuật Toán Sản Phẩm Đang Thịnh Hành - Dựa Vào Viewer */
        $viewer_product = Product::where('product_status', 1)->where('flashsale_status', '0')->wherenotin('product_id', $list_id_product_order_5)->orderby('product_viewer', 'DESC')->take(5)->get();
        $list_id_viewer_product = array();
        foreach ($viewer_product as $key => $v_viewer_product) {
            $list_id_viewer_product[$key] = $v_viewer_product->product_id;
        }
        /* Kết Thúc Thuật Toán */
        /* Thuật Toán Sản Phẩm Mới - Dựa Vào Product_id */
        $new_product = Product::where('product_status', 1)->where('flashsale_status', '0')->wherenotin('product_id', $list_id_product_order_5)->wherenotin('product_id', $list_id_viewer_product)->take(5)->orderby('product_id', 'DESC')->get();
        // $list_id_new_product = array();
        // foreach ($new_product as $key => $v_new_product) {
        //     $list_id_new_product[$key] = $v_new_product->product_id;
        // }

        if ($new_product->count() > 0) {
            foreach ($new_product as $value) {
                $all_product_data[] = array(
                    'product_id' => $value->product_id,
                    'category_id' => $value->category_id,
                    'category_name' => $value->category->category_name,
                    'product_name' => $value->product_name,
                    'product_desc' => $value->product_desc,
                    'product_price' => $value->product_price,
                    'product_image' => 'http://192.168.1.12/DoAnCNWeb/public/fontend/assets/img/product/' . $value->product_image,
                );
            }
            return response()->json([
                'data' => $all_product_data,
                'status_code' => 200,
                'message' => 'ok',
            ]);
        } else {
            return response()->json([
                'data' => null,
                'status_code' => 404,
                'message' => 'Không có dữ liệu trả về !',
            ]);
        }
        
    }
    public function all_product()
    {
        /* Thuật Toán Sản Phẩm Flashsale - Dựa Vào Flashsale Status */
        $flashsale = Flashsale::where('flashsale_status', '1')->take(6)->get();
        /* Thuật Toán Sản Phẩm Bán Chạy - Dựa Vào OrderDetails */
        $list_id_product_order = array();
        $orderDetails = OrderDetails::get();
        foreach ($orderDetails as $key => $v_orderDetails) {
            $product_not_flashsale = Product::where('product_id', $v_orderDetails->product_id)->where('flashsale_status', 0)->first();
            if ($product_not_flashsale) {
                $list_id_product_order[$key] = $v_orderDetails->product_id;
            }
        }
        $i = 0;
        $list_id_product_order = array_unique($list_id_product_order);
        $list_id_product_order_5 = array();
        foreach ($list_id_product_order as $key => $v_list_id_product_order) {
            $list_id_product_order_5[$key] = $v_list_id_product_order;
            $i++;
            if ($i == 4) {
                break;
            }
        }
        $best_sale_product = Product::wherein('product_id', $list_id_product_order_5)->where('product_status', 1)->where('flashsale_status', '0')->get();
        /* Kết Thúc Thuật Toán */
        /* Thuật Toán Sản Phẩm Đang Thịnh Hành - Dựa Vào Viewer */
        $viewer_product = Product::where('product_status', 1)->where('flashsale_status', '0')->wherenotin('product_id', $list_id_product_order_5)->orderby('product_viewer', 'DESC')->take(5)->get();
        $list_id_viewer_product = array();
        foreach ($viewer_product as $key => $v_viewer_product) {
            $list_id_viewer_product[$key] = $v_viewer_product->product_id;
        }
        /* Kết Thúc Thuật Toán */
        /* Thuật Toán Sản Phẩm Mới - Dựa Vào Product_id */
        $new_product = Product::where('product_status', 1)->where('flashsale_status', '0')->wherenotin('product_id', $list_id_product_order_5)->wherenotin('product_id', $list_id_viewer_product)->take(5)->orderby('product_id', 'DESC')->get();
        $list_id_new_product = array();
        foreach ($new_product as $key => $v_new_product) {
            $list_id_new_product[$key] = $v_new_product->product_id;
        }
        /* Lấy Sản Phẩm Còn Lại - Sắp Xếp Dựa Theo Giá*/
        $best_price_product = Product::where('product_status', 1)->where('flashsale_status', '0')->orderby('product_price', 'ASC')->wherenotin('product_id', $list_id_new_product)->wherenotin('product_id', $list_id_product_order_5)->wherenotin('product_id', $list_id_viewer_product)->take(8)->get();
        if ($best_price_product->count() > 0) {
            foreach ($best_price_product as $value) {
                $all_product_data[] = array(
                    'product_id' => $value->product_id,
                    'category_id' => $value->category_id,
                    'category_name' => $value->category->category_name,
                    'product_name' => $value->product_name,
                    'product_desc' => $value->product_desc,
                    'product_price' => $value->product_price,
                    'product_image' => 'http://192.168.1.12/DoAnCNWeb/public/fontend/assets/img/product/' . $value->product_image,
                );
            }
            return response()->json([
                'data' => $all_product_data,
                'status_code' => 200,
                'message' => 'ok',
            ]);
        } else {
            return response()->json([
                'data' => null,
                'status_code' => 404,
                'message' => 'Không có dữ liệu trả về !',
            ]);
        }
    }

    public function check_login(Request $request)
    {
        $result = Customers::where('customer_email', $request->customer_email)->where('customer_password', md5($request->customer_password))->first();
        if ($result) {
            return response()->json([
                'data' => $result,
                'status_code' => 200,
                'message' => 'ok',
            ]);
        } else {
            return response()->json([
                'data' => null,
                'status_code' => 404,
                'message' => 'Email hoặc Mật Khẩu không chính xác!',
            ]);
        }
    }

    public function all_product_by_category(Request $request)
    {
        $all_product = Product::where('category_id', $request->category_id)->paginate(4);
        if ($all_product->count()) {
            foreach ($all_product as $value) {
                $all_product_data[] = array(
                    'product_id' => $value->product_id,
                    'category_id' => $value->category_id,
                    'category_name' => $value->category->category_name,
                    'product_name' => $value->product_name,
                    'product_desc' => $value->product_desc,
                    'product_price' => $value->product_price,
                    'product_image' => 'http://192.168.1.12/DoAnCNWeb/public/fontend/assets/img/product/' . $value->product_image,
                );
            }
            return response()->json([
                'data' => $all_product_data,
                'status_code' => 200,
                'message' => 'ok',
            ]);
        } else {
            return response()->json([
                'data' => null,
                'status_code' => 404,
                'message' => 'Không có dữ liệu trả về !',
            ]);
        }
    }

    public function type_product()
    {
        $type_product = Category::get();
        foreach ($type_product as $value) {
            $type_product_data[] = array(
                'id' => $value->category_id,
                'name' => $value->category_name,
                'image' => $value->category_image,
            );
        }
        return response()->json([
            'status_code' => 200,
            'message' => 'ok',
            'data' => $type_product_data,
        ]);
    }

    public function register_account(Request $request)
    {
        $data = array(
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'customer_email' => $request->customer_email,
            'customer_password' => md5($request->customer_password),
            'customer_status' => 1,
            'customer_ip' => '123',
            'customer_located' => '123',
            'customer_device' => '123',
        );
        DB::table('tbl_customers')->insert($data);
        $customer_id = DB::getPdo('tbl_customers')->lastInsertId();

        $customer = Customers::where('customer_id', $customer_id)->first()->toArray();

        return response()->json([
            'data' => $customer,
            'status_code' => 200,
            'message' => 'ok',
        ]);

    }

    public function all_slider()
    {

        $all_slider = Slider::paginate(5);
        if ($all_slider->count() > 0) {
            foreach ($all_slider as $value) {
                $all_slider_data[] = array(
                    'slider_id' => $value->slider_id,
                    'slider_name' => $value->slider_name,
                    'slider_image' => "http://192.168.1.12/DoAnCNWeb/public/fontend/assets/img/slider/" . $value->slider_image,
                    'slider_status' => $value->slider_status,
                    'slider_desc' => $value->slider_desc,
                    'created_at' => $value->created_at,
                    'updated_at' => $value->updated_at,
                );
            }
            return response()->json([
                'data' => $all_slider_data,
                'status_code' => 200,
                'message' => 'ok',
            ]);
        } else {
            return response()->json([
                'data' => null,
                'status_code' => 404,
                'message' => 'Không có dữ liệu trả về !',
            ]);
        }

    }

    // public function all_new_product()
    // {

    //     $all_product = Product::paginate(5);
    //     if ($all_product) {
    //         foreach ($all_product as $value) {
    //             $all_product_data[] = array(
    //                 'product_id' => $value->product_id,
    //                 'category_id' => $value->category_id,
    //                 'category_name' => $value->category->category_name,
    //                 'product_name' => $value->product_name,
    //                 'product_desc' => $value->product_desc,
    //                 'product_price' => $value->product_price,
    //                 'product_image' => 'http://192.168.1.12/DoAnCNWeb/public/fontend/assets/img/product/' . $value->product_image,
    //             );
    //         }
    //         return response()->json([
    //             'data' => $all_product_data,
    //             'status_code' => 200,
    //             'message' => 'ok',
    //         ]);
    //     } else {
    //         return response()->json([
    //             'data' => null,
    //             'status_code' => 404,
    //             'message' => 'Không có dữ liệu trả về !',
    //         ]);
    //     }

    // }

    public function all_category()
    {
        $all_category = Category::paginate(5);
        if ($all_category->count() > 0) {
            return response()->json([
                'data' => $all_category->items(),
                'status_code' => 200,
                'message' => 'ok',
            ]);
        } else {
            return response()->json([
                'data' => null,
                'status_code' => 404,
                'message' => 'Không có dữ liệu trả về !',
            ]);
        }
    }

}
