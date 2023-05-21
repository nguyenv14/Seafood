<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Payment;
use App\Models\Shipping;
use App\Models\Coupon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Mail;
use Session;
use App\Models\ManipulationActivity;
use Carbon\Carbon;
session_start();

class CheckOutController extends Controller
{
    public function show_payment()
    {
        $meta = array(
            'title' => 'Trang Chủ - Thế Giới Hải Sản',
            'description' => 'Thế Giới Hải Sản - Trang Tìm Kiếm Và Đặt Hải Sản Hàng Đầu Việt Nam',
            'keywords' => 'Hải Sản Đà Nẵng , Hải Sản Giá Rẻ , Hải Sản Tươi Sống , Hải Sản Giao Nhanh , Tôm Hùng , Cua , Ghẹ , .....',
            'canonical' => request()->url(),
            'sitename' => 'sepnguyenvanhanbro.thegioihaisan.laravel.vn',
            'image' => '',
        );

        if (Session::get('fee') != null && Session::get('cart') != null && Session::get('shipping') != null) {
            ManipulationActivity::noteManipulationCustomer( "Vào Trang Thanh Toán");
            return view('pages.thanhtoan.thanh_toan')->with(compact('meta'));
        } else {
            $this->message("warning", "Lỗi Không Xác Định , Hãy Đặt Lại Sản Phẩm!");
            ManipulationActivity::noteManipulationCustomer( "Lỗi Ở Trang Thanh Toán");
            return redirect('/');
        }
    }
    public function insert_notes(Request $request)
    {
        $content_notes = $request->content_notes;
        if (Session::get('shipping') != null) {
            $shipping = Session::get('shipping');
            $shipping['shipping_notes'] = $content_notes;
            session()->put('shipping', $shipping);
            echo "true";
            ManipulationActivity::noteManipulationCustomer( "Điền Yêu Cầu Riêng");
        }
    }

    public function special_requirements(Request $request)
    {
        $chooseone = $request->chooseone;
        $choosetwo = $request->choosetwo;
        if (Session::get('shipping') != null) {
            $shipping = Session::get('shipping');
            if ($chooseone == 1 && $choosetwo == 2) {
                $shipping['shipping_special_requirements'] = 3;
            } else if ($chooseone == '' && $choosetwo == 2) {
                $shipping['shipping_special_requirements'] = $choosetwo;
            } else if ($chooseone == 1 && $choosetwo == '') {
                $shipping['shipping_special_requirements'] = $chooseone;
            } else if ($chooseone == '' && $choosetwo == '') {
                $shipping['shipping_special_requirements'] = 0;
            }
            session()->put('shipping', $shipping);
            echo "true";
            ManipulationActivity::noteManipulationCustomer( "Điền Yêu Cầu Đặc Biệt");
        }
    }
    public function receipt(Request $request)
    {
        $choosereceipt = $request->choosereceipt;
        if (Session::get('shipping') != null) {
            $shipping = Session::get('shipping');
            if ($choosereceipt == 1) {
                $shipping['shipping_receipt'] = $choosereceipt;
            } else {
                $shipping['shipping_receipt'] = 0;
            }
            session()->put('shipping', $shipping);
            ManipulationActivity::noteManipulationCustomer( "Điền Yêu Cầu Nhận Hóa Đơn");
            echo "true";
        }
    }
        /* Thanh Toán Bằng VN Pay */
    public function vnpay_payment()
    {  
        $vnp_TmnCode = "R88K81XY"; //Mã website tại VNPAY
        $vnp_HashSecret = "XOPZTBHFSXAOVRWQKZEJOXYSEFELBYDB"; //Chuỗi bí mật
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html"; /* Trả về trang mà mình thanh toán */
        $vnp_Returnurl = "https://sepnguyenvanhanbro.thegioihaisan.laravel.vn/DoAnCNWeb/thanh-toan/vnpay-payment-callback?"; /* Đường dẫn trả về */
        $vnp_TxnRef = date("YmdHis"); //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY
        $vnp_OrderInfo = "Thanh toan hoa don. So tien  VND";
        $vnp_OrderType = 'billpayment';
        $vnp_BankCode = 'NCB';
        $vnp_Amount = 10000 * 100;
        $vnp_Locale = 'vn';
        $vnp_IpAddr = request()->ip();

        $inputData = array(
            "vnp_Version" => "2.0.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }
        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . $key . "=" . $value;
            } else {
                $hashdata .= $key . "=" . $value;
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
           // $vnpSecureHash = md5($vnp_HashSecret . $hashdata);
            $vnpSecureHash = hash('sha256', $vnp_HashSecret . $hashdata);
            $vnp_Url .= 'vnp_SecureHashType=SHA256&vnp_SecureHash=' . $vnpSecureHash;
        }
        return redirect($vnp_Url);
       
    }

    public function vnpay_payment_callback(){

    }

     /* TK TEST MOMO */
    /*
    Số Thẻ : 9704 0000 0000 0018
    Tên Chủ Thẻ : NGUYEN VAN A
    Ngày Phát Hành : 03/07
    OTP : OTP
    SDT : 0987654321
     */

    public function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data))
        );
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        //execute post
        $result = curl_exec($ch);
        //close connection
        curl_close($ch);
        return $result;
    }
    public function momo_payment()
    {
        $order_code_rd = session()->get('order_code_rd');
        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
        $partnerCode = 'MOMOBKUN20180529';
        $accessKey = 'klm05TvNBzhg7h7j';
        $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
        $orderId = $order_code_rd . rand(0000, 9999); // Mã đơn hàng
        $orderInfo = "Thanh toán qua MoMo";
        $amount = Session::get('price_all_product');
        $redirectUrl = "https://sepnguyenvanhanbro.thegioihaisan.laravel.vn/DoAnCNWeb/thanh-toan/momo-payment-callback?";
        $ipnUrl = "https://sepnguyenvanhanbro.thegioihaisan.laravel.vn/DoAnCNWeb/thanh-toan/momo-payment-callback?";
        $extraData = "";
        $requestId = time() . "";
        $requestType = "payWithATM";
        $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
        $signature = hash_hmac("sha256", $rawHash, $secretKey);
        $data = array('partnerCode' => $partnerCode,
            'partnerName' => "Test",
            "storeId" => "MomoTestStore",
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature);
        $result = $this->execPostRequest($endpoint, json_encode($data));
        $jsonResult = json_decode($result, true); // decode json

        return redirect($jsonResult['payUrl']);
    }

    public function momo_payment_callback(Request $request)
    {
        // if($request->message =='Successful'){
        $payment = array(
            'payment_method' => 1,
            'payment_status' => 1,
        );
        session()->put('payment', $payment);
        $this->order();
        $this->message("success", "Thanh Toán Bằng Momo Thành Công, Đơn Hàng Của Bạn Đã Được Ghi Lại!");
        ManipulationActivity::noteManipulationCustomer( "Đặt Hàng Và Thanh Toán Thành Công Đơn Hàng Bằng Momo Thành Công!");
        return redirect('/thanh-toan/hoa-don');
        // }else{
        //     $this->message("warning", "Đã Xãy Ra Lỗi Khi Thanh Toán , Vui Lòng Thanh Toán Lại!");
        //     return redirect('/thanh-toan');
        // }
    }

    public function direct_payment()
    {
        $payment = array(
            'payment_method' => 4,
            'payment_status' => 0,
        );
        session()->put('payment', $payment);
        $this->order();
        $this->message("success", "Đặt Hàng Thành Công!");
        ManipulationActivity::noteManipulationCustomer( "Đặt Hàng Thành Công!");
        return redirect('/thanh-toan/hoa-don');
    }

    public function order()
    {
        // Thêm dữ liệu vào bảng payment
        $data_payment = session()->get('payment');
        $payment = new Payment();
        $payment['payment_method'] = $data_payment['payment_method'];
        $payment['payment_status'] = $data_payment['payment_status'];
        $payment->save();
        $payment_id = DB::getPdo('tbl_payment')->lastInsertId();

        // Thêm Dữ Liệu Vào Bảng Shipping
        $shipping = new Shipping();
        $data_shipping = session()->get('shipping');
        $shipping->shipping_name = $data_shipping['shipping_name'];
        $shipping->shipping_phone = $data_shipping['shipping_phone'];
        $shipping->shipping_email = $data_shipping['shipping_email'];
        $shipping->shipping_address = $data_shipping['shipping_address'];
        $shipping->shipping_notes = $data_shipping['shipping_notes'];
        $shipping->shipping_special_requirements = $data_shipping['shipping_special_requirements'];
        $shipping->shipping_receipt = $data_shipping['shipping_receipt'];
        $shipping->save();
        $shipping_id = DB::getPdo('tbl_shipping')->lastInsertId();
        $fee = session()->get('fee');   
        $coupon = session()->get('coupon-cart');
        $coupon_name_code = '';
        if ($coupon != null) {
            $coupon_name_code = $coupon->coupon_name_code;
        } else {
            $coupon_name_code = 'Không có';
        }
        $coupon_get = Coupon::where('coupon_name_code', $coupon_name_code)->first();
        if($coupon_get){
            if($coupon_get->coupon_qty_code > 0){
                $coupon_get->coupon_qty_code -=1;
                $coupon_get->save();
            }
        }

        //Thêm Dữ Liệu Vào Bảng Order
        $order_code_rd = session()->get('order_code_rd');
        $order = new Order();
        if (session()->get('customer_id')) {
            $order->customer_id = session()->get('customer_id');
        } else {
            $order->customer_id = -1;
        }
        $order->shipping_id = $shipping_id;
        $order->payment_id = $payment_id;
        $order->order_status = 0;
        $order->order_code = $order_code_rd;
        $order->product_fee = $fee['fee_feeship'];
        $order->product_coupon = $coupon_name_code;
        if($coupon_name_code == 'Không có')
        $order->product_price_coupon = 0;
        else{
            $order->product_price_coupon = $coupon_get->coupon_price_sale;
        }
        $order->total_price = session()->get('price_all_product');
        $order->total_quantity = session()->get('product_quantity');
        $order->order_date = Carbon::now('Asia/Ho_Chi_Minh')->format('Y-m-d');
        $order->save();

        
        /* Lưu Dữ Liệu Vào Bảng OrderDetails */
        $carts = session()->get('cart');
        foreach ($carts as $key => $cart) {
            $orderdetails = new OrderDetails();
            $orderdetails->order_code = $order_code_rd;
            $orderdetails->product_id = $cart['product_id'];
            $orderdetails->product_name = $cart['product_name'];
            $orderdetails->product_price = $cart['product_price'];
            $orderdetails->product_sales_quantity = $cart['product_quantity'];
            $orderdetails->save();
        }
        ManipulationActivity::noteManipulationCustomer( "Hệ Thống Lưu Đơn Hàng Vào Database");
        $this->email_order_to_customer();
    }

    public function email_order_to_customer()
    {
        $order_code_rd = session()->get('order_code_rd');
        $shipping = session()->get('shipping');
        $cart = session()->get('cart');
        $fee = session()->get('fee');
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
        ManipulationActivity::noteManipulationCustomer( "Hệ Thống Gửi Mail Đến Người Dùng");
    }

    public function show_receipt()
    {
        $meta = array(
            'title' => 'Trang Chủ - Thế Giới Hải Sản',
            'description' => 'Thế Giới Hải Sản - Trang Tìm Kiếm Và Đặt Hải Sản Hàng Đầu Việt Nam',
            'keywords' => 'Hải Sản Đà Nẵng , Hải Sản Giá Rẻ , Hải Sản Tươi Sống , Hải Sản Giao Nhanh , Tôm Hùng , Cua , Ghẹ , .....',
            'canonical' => request()->url(),
            'sitename' => 'sepnguyenvanhanbro.thegioihaisan.laravel.vn',
            'image' => '',
        );
        if (Session::get('fee') != null && Session::get('cart') != null && Session::get('shipping') != null) {
            return view('pages.hoadon.hoa_don')->with(compact('meta'));
            ManipulationActivity::noteManipulationCustomer( "Vào Trang Hóa Đơn Kết Quả Đặt Hàng");
        } else {
            ManipulationActivity::noteManipulationCustomer( "Lỗi Khi Vào Trang Hóa Đơn Kết Quả Đặt Hàng");
            return redirect('/');
        }

    }
    public function un_set_order()
    {
        session()->forget('payment');
        session()->forget('cart');
        session()->forget('shipping');
        session()->forget('fee');
        session()->forget('coupon-cart');
        session()->forget('price_all_product');
        session()->forget('order_code_rd');
        ManipulationActivity::noteManipulationCustomer( "Hệ Thống Hủy Toàn Bộ Session Liên Quan Đến Giỏ Hàng - Thanh Toán");
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
