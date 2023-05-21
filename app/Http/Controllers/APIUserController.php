<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use App\Models\Category;
use App\Models\Coupon;
use App\Models\Customers;
use App\Models\GalleryProduct;
use App\Models\Product;
use App\Rules\Captcha;
use Illuminate\Support\Facades\DB;
use ProductDetails;
use Session;
use Mail;
use Stevebauman\Location\Facades\Location;

session_start();
class APIUserController extends Controller
{
    public function all_user(){
        $user = Customers::get();
        dd($user);
    }

  
    public function create_customer(Request $request)
    {
       
        $currentUserInfo = Location::get(request()->ip());
        if($currentUserInfo != null){
            $customer_located = $currentUserInfo->cityName;
        }else {
            $customer_located = "Không Xác Định";
        }
        $data = array(
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'customer_email' => $request->customer_email,
            'customer_password' => md5($request->customer_password),
            'customer_status' => 1,
            'customer_ip' => request()->ip(),
            'customer_located' => $customer_located,
            'customer_device' =>request()->userAgent(),
        );

        DB::table('tbl_customers')->insert($data);
        $customer = Customers::orderBy("customer_id", "DESC")->first();
        $data_new[] = array(
            'customer_name' => $customer->customer_name,
            'customer_phone' => $customer->customer_phone,
            'customer_email' => $customer->customer_email,
            'customer_password' => $customer->customer_password,
        );
        if($customer){
            return response()->json([
                'data' => $data_new,
                'status_code' => 200,
                'message' => 'Đăng kí thành công',
            ]) ;
        }else{
            return response()->json([
                'data' => null,
                'status_code' => 400,
                'message' => 'Đăng kí không thành công',
            ]) ;
        }
    }
    

  

    function sendCodeEmailCustomer(Request $request){
        $email = $request->email;
        // $customer = json_decode($request->customer_new ,true);
        $code = rand(1000, 9999);
        $status = $request->status; //0 là tạo tài khoản 1 là mật khẩu mới
        if ($status == 0) {
            $email_old = Customers::where("customer_email", $email)->first();
            if($email_old){
                return response()->json([
                    'status_code' => 404,
                    'message' => 'Email đã tồn tại!',
                    'data' => null,
                ]) ;
            }
            $name = $request->customer_name;
            $mail_customer = $email;
            $type = "Bạn đã yêu cầu đăng ký tài khoản!";
            $to_name = "Vĩnh Nguyên - Mail Laravel";
            $to_email = "$mail_customer";
        };
        $data = array(
            "name" => "$name",
            "code" => "$code",
            "type" => "$type",
        ); // send_mail of mail.blade.php

        Mail::send('pages.Login_Register.mailtocustomer', $data, function ($message) use ($to_name, $to_email) {
            $message->to($to_email)->subject("Xin Chào ! Vĩnh Nguyên Đang Test Mail Chút Hihi"); //send this mail with subject
            $message->from($to_email, $to_name); //send from this mail
        });

        $data_new[] = array(
            "customer_email" => $email,
            "code" => $code,
            "status" => $status
        );

        return response()->json([
            'status_code' => 200,
            'message' => 'Đã gửi email thành công!',
            'data' => $data_new,
        ]);
    }

    function sendCodeChangePass(Request $request){
        $email = $request->email;
        $code = rand(1000, 9999);
        $status = $request->status;
        $customer = Customers::where("customer_email", $email)->first();
        if($customer){
            $name = $customer->customer_name;
            $mail_customer = $email;
            $type = "Bạn đã yêu cầu khôi phục tài khoản của mình!";
            $to_name = "Vĩnh Nguyên - Mail Laravel";
            $to_email = "$mail_customer";

            $data = array(
                "name" => "$name",
                "code" => "$code",
                "type" => "$type",
            ); // send_mail of mail.blade.php

            Mail::send('pages.Login_Register.mailtocustomer', $data, function ($message) use ($to_name, $to_email) {
                $message->to($to_email)->subject("Xin Chào ! Vĩnh Nguyên Đang Test Mail Chút Hihi"); //send this mail with subject
                $message->from($to_email, $to_name); //send from this mail
            });

            $data_new[] = array(
                "customer_email" => $email,
                "code" => $code,
                "status" => $status
            );

            return response()->json([
                'status_code' => 200,
                'message' => 'Đã gửi email thành công!',
                'data' => $data_new,
            ]); 
        }else{
            return response()->json([
                'status_code' => 404,
                'message' => 'Email không tồn tại!',
                'data' => null,
            ]); 
        }
    }

    function changePass(Request $request){
        $email = $request->customer_email;
        $pass = $request->customer_password;
        $customer = Customers::where("customer_email", $email)->first();
        $customer->customer_password = md5($pass);
        $customer->save();
        $customer_new = Customers::where("customer_email", $email)->get()->toArray();
        return response()->json([
            'status_code' => 200,
            'message' => 'Đã cập nhật pass!',
            'data' => $customer_new,
        ]);
    }

    public function logIn(Request $request){
        $result = Customers::where('customer_password', md5($request->customer_password))->Where('customer_email', $request->customer_email)->first();
        if($result){
            // $data[] = array(
            //     "customer_id" => $result->customer_id,
            //     "customer_name" => $result->customer_name,
            //     "customer_email" => $result->customer_email,
            //     "customer_password" => $result->customer_password,2
            // );
            return response()->json([
                'status_code' => 200,
                'message' => 'Đăng nhập thành công!',
                'data' => $result,
            ]) ;
        }else{
            return response()->json([
                'status_code' => 404,
                'message' => 'Sai email đăng nhập hoặc mật khẩu!',
                'data' => null,
            ]) ;
        }
    }

    function updateUserType($customerNew){
        if($customerNew){
            $data[] = array(
                "customer_id" => $customerNew->customer_id,
                "customer_name" => $customerNew->customer_name,
                "customer_phone" => $customerNew->customer_phone,
                "customer_email" => $customerNew->customer_email,
                "customer_password" => $customerNew->customer_password,
            );
           
                return response()->json([
                    'status_code' => 200,
                    'message' => 'Đăng kí thành công',
                    'data' => $data,
                ]) ;
           
               
        }else{
            return response()->json([
                'status_code' => 400,
                'message' => 'Đăng kí không thành công',
                'data' => null,
            ]) ;
        }
    }

    public function updateName(Request $request){
        $customer = Customers::where("customer_id", $request->customer_id)->first();
        if($customer){
            $customer['customer_name'] = $request->name;
            $customer->save();
        }

        $customer_new = Customers::where("customer_id", $request->customer_id)->first();
        return $this->updateUserType($customer_new);
    }

    public function updatePhone(Request $request){
        $customer = Customers::where("customer_id", $request->customer_id)->first();
        if($customer){
            $customer['customer_phone'] = $request->phone;
            $customer->save();
        }

        $customer_new = Customers::where("customer_id", $request->customer_id)->first();
        return $this->updateUserType($customer_new);
    }
    
    public function updateEmail(Request $request){
        $customer = Customers::where("customer_id", $request->customer_id)->first();
        if($customer){
            $customer['customer_email'] = $request->email;
            $customer->save();
        }

        $customer_new = Customers::where("customer_id", $request->customer_id)->first();
        return $this->updateUserType($customer_new);
    }

    public function updatePass(Request $request){
        $customer = Customers::where("customer_id", $request->customer_id)->first();
        if($customer){
            $customer['customer_password'] = md5($request->password);
            $customer->save();
        }
        $customer_new = Customers::where("customer_id", $request->customer_id)->first();
        return $this->updateUserType($customer_new);
    }

}