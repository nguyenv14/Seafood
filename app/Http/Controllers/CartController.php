<?php
namespace App\Http\Controllers;


use App\Models\Couponn;
use App\Models\Customers;
use App\Models\Flashsale;
use App\Models\Product;

use App\Models\City;
use App\Models\Coupon;
use App\Models\Province;
use App\Models\Wards;
use App\Models\Feeship;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Session;
use App\Models\ManipulationActivity;
use Carbon\Carbon;
session_start();
class CartController extends Controller{

    public function show_cart(){
        $meta = array(
            'title' => 'Trang Chủ - Thế Giới Hải Sản',
            'description' => 'Thế Giới Hải Sản - Trang Tìm Kiếm Và Đặt Hải Sản Hàng Đầu Việt Nam',
            'keywords' => 'Hải Sản Đà Nẵng , Hải Sản Giá Rẻ , Hải Sản Tươi Sống , Hải Sản Giao Nhanh , Tôm Hùng , Cua , Ghẹ , .....',
            'canonical' => request()->url(),
            'sitename' => 'sepnguyenvanhanbro.thegioihaisan.laravel.vn',
            'image' => '',
        );

        $cities = City::whereIn('matp',['48','46','49'])->get();
        if(session()->get('customer_id')){
            $customer_id = session()->get('customer_id');
            $customer = Customers::where('customer_id', $customer_id)->first();
            ManipulationActivity::noteManipulationCustomer( "Xem Giỏ Hàng Cá Nhân");
            return view('pages.giohang.giohang')->with(compact('cities','customer','meta'));
        }else{
            ManipulationActivity::noteManipulationCustomer( "Xem Giỏ Hàng Cá Nhân");
            return view('pages.giohang.giohang')->with(compact('cities','meta'));
        }
    }

    public function load_detail_cart(){
        
        $total_all_product = 0;
        $quantity = 0;
        $output ='';
        if(session()->get('cart')){
            
            foreach(session()->get('cart') as $key => $cart){
            
            $output .= '<tr>
            <td><img src="'. URL('public/fontend/assets/img/product/'.$cart['product_image']) .'" alt="" width="200px"></td>';
            $output .='<td>'. $cart['product_name'] .'</td>
            <td>'.number_format($cart['product_price'], 0, ',','.').'đ</td>
            <td><input type="number" style="width: 50px; height:30px;" value="'. $cart['product_quantity'] .'" min="1" data-cart_product_id="'.$cart['session_id'].'" class="changequantity" id="" width="20px"></td>';
            
            $total_price_product = $cart['product_price'] * $cart['product_quantity'];
           
            $output .= '<td>'. number_format($total_price_product, 0, ',','.') .'đ</td>
           
            <td><i class="fas fa-trash-alt" data-cart_id="'.$cart['session_id'].'"></i></td>
        </tr>';
        
            $total_all_product += $total_price_product;
            $quantity = $quantity + $cart['product_quantity'];
            }   
        session()->put('price_all_product',  $total_all_product);
            $output .= '<tr class="table-foot">
                    <td colspan="4">Tổng tiền</td>
                    <td colspan="3">'.number_format($total_all_product, 0, ',','.') .'đ</td>
                </tr>';
            
        }else{
            $output .='<tr>
                <td colspan="6">Không có sản phẩm nào trong giỏ hàng. <a href="'.URL('/').'">Đi đến cửa hàng</a></td>
            </tr>';
            $output .= '<tr class="table-foot">
                    <td colspan="4" style="font-weight: 900;">Tổng tiền</td>
                    <td colspan="3">'.number_format($total_all_product, 0, ',','.') .'đ </td>
                </tr>';
           session()->put('price_all_product',  $total_all_product);
        }

        // foreach($carts as $key => $cart){
        //     if($cart['session_id'] == $cart_id){
        //         $carts[$key]['product_quantity'] = $quantity; 
        //     }
        // }
        session()->put('product_quantity', $quantity);
        echo $output;
    }

    
    public function load_payment(){
        $coupon = session()->get('coupon-cart');
        $price_all_product =  session()->get('price_all_product');
        $fee = session()->get('fee');
        $output ='';
       
        if($fee){
            $total_price = $price_all_product + $fee['fee_feeship'] ;
        }else{
            $total_price = $price_all_product ;
        }
        if(!$coupon){
            $output .='<tr>
            <th>Tổng tiền</th>
            <td>'.number_format($price_all_product, 0, '.', ',').'Đ</td>
        </tr>
        <tr>
            <th>Phiếu giảm giá</th>
            <td>Chưa Có</td>
        </tr>
        <tr>
            <th>Phí vận chuyển</th>';
            if( $fee ){
                $output .= '<td class="fee_feeship"> +'.number_format($fee['fee_feeship'] , 0, '.', ',').'Đ</td>';
            }else{
                $output .= '<td class="fee_feeship">0đ</td>';
            }
            
        $output .= '</tr> 
        <tr>
            <th>Tổng cộng</th>
            <td>'.number_format($total_price, 0, '.', ',').'</td>
        </tr>';
        }else{
            if($coupon->coupon_condition == 1){
                $price_sale_percent = ($coupon->coupon_price_sale * $price_all_product)/100;
                $product_sale_price = $total_price - $price_sale_percent;
                $output .='<tr>
                <th>Tổng tiền</th>
                <td>'.number_format($price_all_product, 0,'.',',').'</td>
            </tr>
            <tr>
                <th>Phiếu giảm giá</th>
                <td>-'.number_format($price_sale_percent, 0, '.', ',').'</td>
            </tr>
            <tr>
                <th>Phí vận chuyển</th>';
                if($fee){
                    $output .= '<td class="fee_feeship"> +'.number_format($fee['fee_feeship'], 0, '.', ',').'Đ</td>';
                }else{
                    $output .= '<td class="fee_feeship">0Đ</td>';
                }
            $output .='</tr>
            <tr>
                <th>Tổng cộng</th>
                <td>'.number_format($product_sale_price, 0,'.',',').'Đ</td>
            </tr>';
            }else{
                $price_sale_percent = $coupon->coupon_price_sale;
                $product_sale_price = $total_price - $price_sale_percent;
                $output .='<tr>
                <th>Tổng tiền</th>
                <td>'.number_format($price_all_product, 0,'.',',').'</td>
            </tr>
            <tr>
                <th>Phiếu giảm giá</th>
                <td>'.number_format($price_sale_percent, 0, '.', ',').'</td>
            </tr>
            <tr>
                <th>Phí vận chuyển</th>';
                if($fee){
                    $output .= '<td class="fee_feeship"> +'.number_format($fee['fee_feeship'], 0, '.', ',').'Đ</td>';
                }else{
                    $output .= '<td class="fee_feeship">0Đ</td>';
                }
            $output .= '</tr>
            <tr>
                <th>Tổng cộng</th>
                <td>'.number_format($product_sale_price, 0,'.',',').'Đ</td>
            </tr>';
            }
        }

        
        echo $output;
    }

    public function load_coupon(){
        $output = '';
        if (session()->get('coupon-cart')){
            $coupon = session()->get('coupon-cart');
            if ($coupon->coupon_condition == 1){ 
                    $output .= '<div class="coupon-apply">
                    '. $coupon->coupon_name.': giảm giá '. $coupon->coupon_price_sale .'% <i class="fa-solid fa-circle-xmark"></i>
                        </div>';
            }else{
                    $output .='<div class="coupon-apply">
                        '.$coupon->coupon_name .': giảm giá '.number_format($coupon->coupon_price_sale, 0, '.',',') .'đ <i class="fa-solid fa-circle-xmark"></i>
                        </div>';
            }
        }else{
            $output .='<div class="coupon-apply" style="display:flex;justify-content:center;">
                        Chưa áp dụng mã giảm giá nào!
                        </div>';
        }
        echo $output;
    }




    public function save_cart(Request $request){
        $product_id = $request->product_id;
        $product_cart = Product::where('product_id', $product_id)->first();
        $flashsale_product = Flashsale::where('product_id', $product_id)->first();
        $session_id = substr(md5(microtime()), rand(0,26),5);
        $cart = session()->get('cart');
        
        if($flashsale_product == NULL){
            if($cart == true){ 
                $is_avaiable = 0;
                foreach($cart as $key => $val){
                    if($val['product_id'] == $product_id){
                        $is_avaiable++;
                    }
                }
                if($is_avaiable == 0){
                    $cart[] = array(
                        'session_id' => $session_id,
                        'product_name' => $product_cart->product_name,
                        'product_id' => $product_id,
                        'product_price' => $product_cart->product_price,
                        'product_quantity' => $request->product_qty,
                        'product_image' => $product_cart->product_image
                    );
                    session()->put('cart', $cart);
                }else{
                    echo 'error';
                }
            }else{
                $cart[] = array(
                    'session_id' => $session_id,
                    'product_name' => $product_cart->product_name,
                    'product_id' => $product_id,
                    'product_price' => $product_cart->product_price,
                    'product_quantity' => $request->product_qty,
                    'product_image' => $product_cart->product_image
                );
            }
        }else{
            if($cart == true){
                $is_avaiable = 0;
                foreach($cart as $key => $val){
                    if($val['product_id'] == $product_id){
                        $is_avaiable++;
                    }
                }
                if($is_avaiable == 0){
                    $cart[] = array(
                        'session_id' => $session_id,
                        'product_name' => $product_cart->product_name,
                        'product_id' => $product_id,
                        'product_price' => $flashsale_product->flashsale_product_price,
                        'product_quantity' => $request->product_qty,
                        'product_image' => $product_cart->product_image
                    );
                    session()->put('cart', $cart);
                }else{
                    echo 'error';
                }
            }else{
                $cart[] = array(
                    'session_id' => $session_id,
                    'product_name' => $product_cart->product_name,
                    'product_id' => $product_id,
                    'product_price' => $flashsale_product->flashsale_product_price,
                    'product_quantity' => $request->product_qty,
                    'product_image' => $product_cart->product_image
                );
            }
        }
        session()->put('cart', $cart);

        ManipulationActivity::noteManipulationCustomer( "Thêm Sản Phẩm ".$product_cart->product_name." Vào Giỏ Hàng ( ID : ". $product_id.")");
    }

    public function message_cart(){
        $output = '';
        if (session()->get('cart')) {
            $cart = session()->get('cart');
            $countcart = count($cart);
            $output .= '<i class="fa-solid fa-cart-shopping" style="position: absolute;"></i>
            <span style="padding: 3px 4px ; background-color: #FF3366; color: #fff ; border-radius: 8px ; font-size: 10px ; position: relative;bottom:-3px;left: 9px;">'.$countcart.'</span>';
        }
        else{
            $output .= '<i class="fa-solid fa-cart-shopping"></i>';
        } 
        echo $output;

    }
    
    public function delete_cart(Request $request){
        $cart_id = $request->cart_id;
        if(session()->get('cart')){
            $carts = session()->get('cart');
            foreach($carts as $key => $cart){
                if($cart['session_id'] == $cart_id){
                    unset($carts[$key]);
                    ManipulationActivity::noteManipulationCustomer( "Xóa Sản Phẩm ".$cart['product_name']." Khỏi Giỏ Hàng ( ID Session: ". $cart['session_id'].")");
                }
            }
            session()->put('cart', $carts);
        }
    }

    public function delete_all_cart(){
        if(session()->get('cart')){
            session()->forget('cart');
            ManipulationActivity::noteManipulationCustomer( "Xóa Toàn Bộ Sản Phẩm Khỏi Giỏ Hàng");
        }
    }


    public function update_all_cart(Request $request){
        $cart_id = $request->cart_id;
        $quantity = $request->quantity;

        if(session()->get('cart')){
            $carts = session()->get('cart');
            foreach($carts as $key => $cart){
                if($cart['session_id'] == $cart_id){
                    $carts[$key]['product_quantity'] = $quantity; 
                }
            }
        }
        ManipulationActivity::noteManipulationCustomer( "Cập Nhật Số Lượng Sản Phẩm Giỏ Hàng (ID Cart : ". $cart_id .")");
        session()->put('cart', $carts);  
    }

    public function check_coupon(Request $request){
        $code_sale = $request->input;
        $output = '';
        $now = Carbon::now('Asia/Ho_Chi_Minh')->format('Y-m-d');
        $coupon = Coupon::where('coupon_name_code', $code_sale)->where('coupon_start_date', '<=',  $now)->where('coupon_end_date', '>=', $now)->first();
        // dd($coupon);
        $orders = Order::where('customer_id', session()->get('customer_id'))->get();
        if( $coupon && $coupon->coupon_qty_code == 0 ){
            $output .= 'không';
        }else{
            if(count($orders) > 0 && $coupon){
                $i = 0;
                
                    foreach($orders as $order){
                        if($order->product_coupon == $coupon->coupon_name_code){
                            $i++;
                        }
                    }
                
                if($i != 0){
                    $output .= 'trùng';
                }else{
                    if(!$coupon){
                        $output .= 'error';
                    }else{
                        session()->put('coupon-cart', $coupon);
                        ManipulationActivity::noteManipulationCustomer( "Sử Dụng Mã Giảm Giá : ". $code_sale."");
                        $output .= 'success';
                    }
                }
            }else{
                if(!$coupon){
                    $output .= 'error';
                }else{
                    session()->put('coupon-cart', $coupon);
                    ManipulationActivity::noteManipulationCustomer( "Sử Dụng Mã Giảm Giá : ". $code_sale."");
                    $output .= 'success';
                }
            }
        }
        echo $output;
    }
    

    public function delete_coupon(){
        if(session()->get('coupon-cart')){
            session()->forget('coupon-cart');
        }
        echo 'success';
        ManipulationActivity::noteManipulationCustomer( "Gỡ Mã Giảm Giá");
    }

    public function caculator_fee(Request $request)
    {
        $data = $request->all(); 
        if ($data != null) {    
            $feeship = Feeship::where('fee_matp', $data['id_city'])->where('fee_maqh', $data['id_province'])->where('fee_maxp', $data['id_wards'])->first();
            if ($feeship != null) {
                $fee = array(
                    'fee_id_city' => $feeship->fee_matp,
                    'fee_name_city' => $feeship->city->name_city,
                    'fee_id_province' =>$feeship->fee_maqh,
                    'fee_name_province' => $feeship->province->name_province,
                    'fee_id_wards' =>$feeship->fee_maxp,
                    'fee_name_wards' => $feeship->wards->name_ward,
                    'fee_feeship' =>  $feeship->fee_feeship,
                );
                session()->put('fee', $fee);
                session()->save();
            } else {
                $city = City::find($data['id_city']);
                $province = Province::find($data['id_province']);
                $wards = Wards::find($data['id_wards']);
                
                $fee = array(
                    'fee_id_city' =>  $city->matp,
                    'fee_name_city' => $city->name_city,
                    'fee_id_province' =>$province->maqh ,
                    'fee_name_province' => $province->name_province,
                    'fee_id_wards' =>$wards->xaid,
                    'fee_name_wards' => $wards->name_ward,
                    'fee_feeship' => 30000,
                );
              

                session()->put('fee', $fee);
                session()->save();
            }
        }
    }

    public function confirm_cart(Request $request){
        $fee = session()->get('fee');
        $shipping_name = $request->shipping_name;
        $shipping_phone = $request->shipping_phone;
        $shipping_email = $request->shipping_email;
        $shipping_home_number = $request-> shipping_home_number;
        $shipping_address =  $shipping_home_number.' '. $fee['fee_name_wards'].', '.$fee['fee_name_province'].', '.$fee['fee_name_city'];

        $shipping = array(
            'shipping_name' =>  $shipping_name,
            'shipping_phone' => $shipping_phone,
            'shipping_email' => $shipping_email ,
            'shipping_address' => $shipping_address,
            'shipping_notes' => 'Không Có',
            'shipping_special_requirements' => 0,
            'shipping_receipt' => 0,
        );
        session()->put('shipping',  $shipping);
        session()->save();

        $order_code_rd = 'TGHSOD' . rand(0001, 9999);
        session()->put('order_code_rd', $order_code_rd);
        echo "true";
        ManipulationActivity::noteManipulationCustomer("Ấn Thanh Toán Sản Phẩm");
    }

}
?>