<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use App\Models\Social;
use Illuminate\Http\Request;
use Mail;
use Auth;
use Session;
use App\Models\ManipulationActivity;

session_start();
class CustomerController extends Controller
{

    public function all_customer()
    {
        $customers = Customers::paginate(6);
        return view('admin.customer.all_customer')->with(compact('customers'));
    }

    public function load_all_customer()
    {
        $customers = Customers::get();
        $output = $this->output_customer($customers);
        echo $output ;
    }
    public function search_all_customer(Request $request){
        $searchbyname_format = '%' . $request->key_sreach . '%';
        $customers = Customers::where('customer_name', 'like', $searchbyname_format)->orwhere('customer_email', 'like', $searchbyname_format)->get();
        $output = $this->output_customer($customers);
        echo $output;
    }
    public function sort_customer_bytype(Request $request){
        $type =  $request->type;
        if($type == 0){
            $customers = Customers::get();
            $output = $this->output_customer($customers);
            echo $output;

        }else if($type == 1){
            $output = '';
            $customers = Customers::with('social')->get();
            foreach ($customers as $key => $customer) {
                if( $customer->social == null ){
                    $output .= '
                    <tr>
                    <td>' . $customer->customer_id . '</td>
                    <td>' . $customer->customer_name . '</td>
                    <td>' . $customer->customer_phone . '</td>
                    <td>' . $customer->customer_email . '</td>
                    ';
                    if(Auth::user()->hasAnyRoles(['admin','manager'])) {
                        $output .= '<td><div style="width: 80px; text-overflow:ellipsis;overflow: hidden">'.$customer->customer_password.'</div></td>';
                    }
                    $output .= '
                    <td>
                    ';
                    if ($customer->customer_password != "") {
                        $output .= 'Hệ Thống';
        
                    } else {
                        $output .= $customer->social->provider;
        
                    }
                    $output .= '
                    </td> ';
                    if (Auth::user()->hasAnyRoles(['admin','manager'])) {
                        $output .= '
                        <td>
                        ';
                        if ($customer->customer_status == 1) {
                            $output .= '
                            <a>
                            <i style="color: rgb(52, 211, 52); font-size: 30px"
                                class="mdi mdi-toggle-switch"></i>
                            </a>
                            ';
                        } else {
                            $output .= '
                            <a ><i style="color: rgb(196, 203, 196);font-size: 30px"
                                class="mdi mdi-toggle-switch-off"></i></a>
                            ';
                        }
                        $output .= '
                    </td>
                    <td>' . $customer->customer_ip . '</td>
                    <td>' . $customer->customer_located . '</td>
                    <td><div style="width: 80px; text-overflow:ellipsis;overflow: hidden">' . $customer->customer_device . '</div></td>
                    <td>
                        <a style="margin-left: 14px">
                            <i style="font-size: 22px" class="mdi mdi-delete-sweep text-danger "></i>
                        </a>
                    </td>';
                    }
                    $output .= '
                </tr>
                ';
                }
            }

            echo $output;

        }else if($type == 2){
            $output = $this->output_sort_type('facebook');
            echo  $output;
        }else if($type == 3){
            $output = $this->output_sort_type('google');
            echo  $output;
        }
      
    }

    public function output_sort_type($type){
        $output = '';
        $customers = Customers::with('social')->get();
        foreach ($customers as $key => $customer) {
            if( $customer->social != null && $customer->social->provider == $type ){
                $output .= '
                <tr>
                <td>' . $customer->customer_id . '</td>
                <td>' . $customer->customer_name . '</td>
                <td>' . $customer->customer_phone . '</td>
                <td>' . $customer->customer_email . '</td>
                ';
                if(Auth::user()->hasAnyRoles(['admin','manager'])) {
                    $output .= '<td><div style="width: 80px; text-overflow:ellipsis;overflow: hidden">'.$customer->customer_password.'</div></td>';
                }
                $output .= '
                <td>
                ';
                if ($customer->customer_password != "") {
                    $output .= 'Hệ Thống';
    
                } else {
                    $output .= $customer->social->provider;
    
                }
                $output .= '
                </td> ';
                if (Auth::user()->hasAnyRoles(['admin','manager'])) {
                    $output .= '
                    <td>
                    ';
                    if ($customer->customer_status == 1) {
                        $output .= '
                        <span class = "update-status" data-customer_id = "'.$customer->customer_id.'" data-status = "0">
                        <i style="color: rgb(52, 211, 52); font-size: 30px"
                        class="mdi mdi-toggle-switch"></i>
                        </span>
                        ';
                    } else {
                        $output .= '
                        <span class = "update-status" data-customer_id = "'.$customer->customer_id.'" data-status = "1" >
                        <i style="color: rgb(196, 203, 196);font-size: 30px"
                            class="mdi mdi-toggle-switch-off"></i>
                        </span>
                        ';
                    }
                    $output .= '
                </td>
                <td>' . $customer->customer_ip . '</td>
                <td>' . $customer->customer_located . '</td>
                <td><div style="width: 80px; text-overflow:ellipsis;overflow: hidden">' . $customer->customer_device . '</div></td>
                <td>
                    <span class = "btn-delete-customer" data-customer_id = "'.$customer->customer_id.'"
                    style="margin-left: 4px">
                    <i style="font-size: 22px" class="mdi mdi-delete-sweep text-danger "></i>
                    </span>      
                </td>';
                }
                $output .= '
            </tr>
            ';
            }
        }

        return $output;
    }

    public function output_customer($customers)
    {
        $output = '';
        foreach ($customers as $key => $customer) {
            $output .= '
            <tr>
            <td>' . $customer->customer_id . '</td>
            <td>' . $customer->customer_name . '</td>
            <td>' . $customer->customer_phone . '</td>
            <td>' . $customer->customer_email . '</td>
            ';
            if(Auth::user()->hasAnyRoles(['admin','manager'])) {
                $output .= '<td><div style="width: 80px; text-overflow:ellipsis;overflow: hidden">'.$customer->customer_password.'</div></td>';
            }
            $output .= '
            <td>
            ';
            if ($customer->customer_password != "") {
                $output .= 'Hệ Thống';

            } else {
                $output .= $customer->social->provider;

            }
            $output .= '
            </td> ';
            if (Auth::user()->hasAnyRoles(['admin','manager'])) {
                $output .= '
                <td>
                ';
                if ($customer->customer_status == 1) {
                    $output .= '
                    <span class = "update-status" data-customer_id = "'.$customer->customer_id.'" data-status = "0">
                    <i style="color: rgb(52, 211, 52); font-size: 30px"
                        class="mdi mdi-toggle-switch"></i>
                    </span>
                    ';
                } else {
                    $output .= '
                    <span class = "update-status" data-customer_id = "'.$customer->customer_id.'" data-status = "1" >
                    <i style="color: rgb(196, 203, 196);font-size: 30px"
                        class="mdi mdi-toggle-switch-off"></i>
                </span>
                    ';
                }
                $output .= '
            </td>
            <td>' . $customer->customer_ip . '</td>
            <td>' . $customer->customer_located . '</td>
            <td><div style="width: 80px; text-overflow:ellipsis;overflow: hidden">' . $customer->customer_device . '</div></td>
            <td>
                <span class = "btn-delete-customer" data-customer_id = "'.$customer->customer_id.'"
                style="margin-left: 4px">
                <i style="font-size: 22px" class="mdi mdi-delete-sweep text-danger "></i>
                </span>       
            </td>';
            }
            $output .= '
        </tr>
        ';
        }
        return $output;
    }


    public function update_status_customer(Request $request){
        $customer = Customers::where('customer_id',  $request->customer_id)->first();
        $customer['customer_status'] = $request->status;
        $customer->save();
        if($request->status == 1){
            ManipulationActivity::noteManipulationAdmin( "Mở Khóa Tài Khoản ( ID : ".$request->customer_id.")");
        }else if($request->status == 0){
            ManipulationActivity::noteManipulationAdmin( "Vô Hiệu Tài Khoản ( ID : ".$request->customer_id.")");
        }
    }
    public function delete_customer(Request $request){
        $customer = Customers::where('customer_id',  $request->customer_id)->first();
        $social = Social::where('user',  $request->customer_id)->first();
        if($social){
            $social->delete();
        }
        $customer->delete();
    }
    public function garbage_can(){
        $customers = Customers::take(7)->onlyTrashed()->orderby('created_at', 'desc')->get();
        ManipulationActivity::noteManipulationAdmin( "Xem Tài Khoản Khách Hàng Trong Thùng Rác");
        return view('admin.Customer.soft_deleted_customer')->with(compact('customers'));
    }

    function load_garbage_can(){
        $customers = Customers::take(7)->onlyTrashed()->orderby('created_at', 'desc')->get();
        $output = $this->output_garbage_can($customers);
        echo $output;
    }
    public function output_garbage_can($customers)
    {
        $output = '';
        foreach ($customers as $key => $customer) {
            $output .= '
            <tr>
            <td>' . $customer->customer_id . '</td>
            <td>' . $customer->customer_name . '</td>
            <td>' . $customer->customer_phone . '</td>
            <td>' . $customer->customer_email . '</td>
            ';
            if(Auth::user()->hasAnyRoles(['admin','manager'])) {
                $output .= '<td><div style="width: 80px; text-overflow:ellipsis;overflow: hidden">'.$customer->customer_password.'</div></td>';
            }
            $output .= '
            <td>
            ';
            if ($customer->customer_password != "") {
                $output .= 'Hệ Thống';

            } else {
                $output .= $customer->socialTrashed->provider;

            }
            $output .= '
            </td> ';
            if (Auth::user()->hasAnyRoles(['admin','manager'])) {
                $output .= '
                <td>
                ';
                if ($customer->customer_status == 1) {
                    $output .= '
                    <span class = "update-status" data-customer_id = "'.$customer->customer_id.'" data-status = "0">
                    <i style="color: rgb(52, 211, 52); font-size: 30px"
                        class="mdi mdi-toggle-switch"></i>
                    </span>
                    ';
                } else {
                    $output .= '
                    <span class = "update-status" data-customer_id = "'.$customer->customer_id.'" data-status = "1" >
                    <i style="color: rgb(196, 203, 196);font-size: 30px"
                        class="mdi mdi-toggle-switch-off"></i>
                </span>
                    ';
                }
                $output .= '
            </td>
            <td>' . $customer->customer_ip . '</td>
            <td>' . $customer->customer_located . '</td>
            <td><div style="width: 80px; text-overflow:ellipsis;overflow: hidden">' . $customer->customer_device . '</div></td>
            <td>

                <span class = "btn-restore-bin-customer" data-customer_id = "'.$customer->customer_id.'">
                <i style="color:rgb(52, 211, 52);font-size: 20px" class="mdi mdi-backup-restore"></i>
                </span>  
        
                <span    style="margin-left: 14px" class = "btn-delete-bin-customer" data-customer_id = "'.$customer->customer_id.'">
                <i style="font-size: 22px" class="mdi mdi-delete-forever text-danger "></i>
                </span>  

            </td>';
            }
            $output .= '
        </tr>
        ';
        }
        return $output;
    }
    public function view_email()
    {
        $customers = Customers::get();
        return view('admin.Customer.emailcustomer')->with(compact('customers'));
    }
    public function selected_email(Request $request)
    {
        $customer = Customers::where('customer_id', $request->customer_id)->first();
        $list_mail_customer = session()->get('list_mail_customer');
        if ($request->id_checked == $request->customer_id) { /* Trường Hợp Người Dùng Chọn Check Box */
            if ($list_mail_customer) { /* Khi List Mail Đã Tồn Tại */
                array_push($list_mail_customer, $customer->customer_email); /* Đưa Giá Trị Vào Mảng */
                $list_mail_customer = array_unique($list_mail_customer); /* Loại Bỏ Giá Trị Trùng Lặp */
                session()->put('list_mail_customer', $list_mail_customer);
                echo "selected";
            } else { /* Khi List Mail Chưa Tồn Tại */
                $list_mail_customer = array();
                array_push($list_mail_customer, $customer->customer_email);
                session()->put('list_mail_customer', $list_mail_customer);
                echo "selected";
            }
        } else if ($request->id_checked != $request->customer_id) { /* Trường Hợp Người Dùng Hủy Chọn Check Box */
            foreach ($list_mail_customer as $key => $value) {
                if ($customer->customer_email == $value) {
                    unset($list_mail_customer[$key]);
                    session()->put('list_mail_customer', $list_mail_customer);
                    echo "unselected";
                }
            }
        }
    }

    public function send_email(Request $request)
    {

        $to_name = "Thế Giới Hải Sản";
        $to_email = $request->to_email;
        $title_email = $request->title_email;
        $content_email = $request->content_email;
        $list_email_customer = preg_replace('/\s+/', '', $to_email); /* Xóa Toàn Bộ Khoảng Trắng Có Trong Chuỗi */
        $list_email_customer = explode(",", $list_email_customer); /* Tách Chuỗi Dựa Vào Dấu Chấm Thành Mảng */

        $data = array(
            "title_email" => $title_email,
            "content_email" => $content_email,
        );

        foreach ($list_email_customer as $to_email_customer) {
            if ($this->validate_email($to_email_customer)) {
                Mail::send('admin.Customer.emaillayout', $data, function ($message) use ($to_name, $to_email_customer, $title_email) {
                    $message->to($to_email_customer)->subject($title_email);
                    $message->from($to_email_customer, $to_name);
                });
            }
        }
        session()->forget('list_mail_customer');
        echo "true";
    }
    public function load_list_mail()
    {
        $list_mail_customer = session()->get('list_mail_customer');
        $output = '';
        if ($list_mail_customer) {
            foreach ($list_mail_customer as $email) {
                $output .= $email . ',';
            }
        }
        echo $output;
    }
    /* Hàm Kiểm Tra Xem Đối Tượng Đó Có Phải Là Email Hay Không*/
    public function validate_email($email)
    {
        return (preg_match("/(@.*@)|(\.\.)|(@\.)|(\.@)|(^\.)/", $email) || !preg_match("/^.+\@(\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/", $email)) ? false : true;
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
