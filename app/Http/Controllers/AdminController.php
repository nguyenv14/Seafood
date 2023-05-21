<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Roles;
use App\Rules\Captcha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Session;

session_start();
class AdminController extends Controller
{

    /* Admin Auth */
    public function all_admin()
    {
        $admins = Admin::with('roles')->orderby('admin_id', 'DESC')->paginate(3);
        return view('admin.Admin.all_admin')->with(compact('admins'));
    }

    public function assign_roles(Request $request)
    {
        if (Auth::id() == $request->admin_id) {
            echo "permission_error";
        } else {
            $admin = Admin::where('admin_id', $request->admin_id)->first();
            $admin->roles()->detach(); /* Nó Hủy Các Quyền Hiện Tại Ra */
            // $admin->roles()->attach(); /* Nó kết hợp admin với roles lại và lấy ra quyền */

            if ($request->index_roles == 1) {
                $admin->roles()->attach(Roles::where('roles_name', 'admin')->first());
                echo "admin";
            } else if ($request->index_roles == 2) {
                $admin->roles()->attach(Roles::where('roles_name', 'manager')->first());
                echo "manager";
            } else if ($request->index_roles == 3) {
                $admin->roles()->attach(Roles::where('roles_name', 'employee')->first());
                echo "employee";
            }
        }
    }

    public function delete_admin_roles(Request $request)
    {
        if (Auth::id() == $request->admin_id) {
            echo "error_delete_admin";
        }else{
            $admin = Admin::where('admin_id', $request->admin_id)->first();
            if ($admin) {
                $admin->roles()->detach(); /* Gỡ quyền */
                $admin->delete();
            }
            echo "true";
        } 
    }
    public function impersonate(Request $request)
    {
        $admin = Admin::where('admin_id', $request->admin_id)->first();
        if ($admin) {
            session()->put('impersonate', $admin->admin_id);
        }
        $this->message("success", "Chuyển Quyền Thành Công !");
        return redirect('admin/dashboard');
    }
    public function destroy_impersonate()
    {
        session()->forget('impersonate');
        $this->message("success", "Hủy Chuyển Quyền Thành Công !");
        return redirect('admin/auth/all-admin');
    }

    public function edit_admin(Request $request){
        $admin_id = $request->admin_id;
        $admin = Admin::where('admin_id', $admin_id)->first();
        return view('admin.Admin.edit_admin')->with(compact('admin'));
    }

    public function update_admin(Request $request){
        $data = $request->all();
        if($data['admin_password_1'] != $data['admin_password_2']){
            $this->message('warning', 'Mật Khẩu Xác Nhận Không Giống Nhau');
            return Redirect()->back();
        }else{
            $admin_id = $data['admin_id'];
            $admin_old = Admin::where('admin_id', $admin_id)->first();
            $admin_old['admin_name'] = $data['admin_name'];
            $admin_old['admin_email'] = $data['admin_email'];
            $admin_old['admin_phone'] = $data['admin_phone'];
            $admin_old['admin_password'] = md5($data['admin_password_1']);

            $admin_old->save();
            $this->message('success', 'Đã cập nhật thành công');
            return Redirect('admin/auth/all-admin');
        }
    }

    public function search_all_admin(Request $request){
        $searchbyname_format = '%' . $request->key_sreach . '%';
        $admins = Admin::where('admin_name', 'like', $searchbyname_format)->orwhere('admin_email', 'like', $searchbyname_format)->get();
        $output = $this->output_admin( $admins );
        echo $output;
    }

    public function loading_table_admin(){
        $admins = Admin::with('roles')->orderby('admin_id', 'DESC')->paginate(3);
        $output = $this->output_admin( $admins );
        echo  $output;  
    }

    public function output_admin($admins){
        $output = '';
        foreach ($admins as $key => $admin){
            $output .= '
            <tr>
            <td>'.$admin->admin_id.'</td>
            <td>'.$admin->admin_name .'</td>
            <td>'.$admin->admin_phone.'</td>
            <td>'.$admin->admin_email.'</td>
            <td><div style="width: 120px; text-overflow:ellipsis;overflow: hidden">'.$admin->admin_password.'</div></td>
            <td>
               
                   
                        <input type="radio" class="form-check-input"
                            name="roles'.$admin->admin_id.'" ';
                            if($admin->hasRoles('admin')){
                                $output .= 'checked';
                            }else{
                                $output .= ' ';
                            }
                            $output .= '
                            value="1"
                            data-admin_id="'.$admin->admin_id.'">
                   
              
            </td>
            <td>
             
                        <input type="radio" class="form-check-input"
                            name="roles'.$admin->admin_id.'" ';
                            if($admin->hasRoles('manager')){
                                $output .= 'checked';
                            }else{
                                $output .= ' ';
                            }
                            $output .= '
                             value="2"
                            data-admin_id="'.$admin->admin_id.'">
                
            </td>
            <td>
              
                        <input type="radio" class="form-check-input"
                            name="roles'.$admin->admin_id.'" ';
                            if($admin->hasRoles('employee')){
                                $output .= 'checked';
                            }else{
                                $output .= ' ';
                            }
                            $output .= '
                            value="3"
                            data-admin_id="'.$admin->admin_id.'">
                  
            </td>

            <td>
                <div style="margin-top: 10px">
                    <button type="button" class="btn-sm btn-gradient-dark btn-rounded btn-fw btn-delete-admin-roles" data-admin_id="'.$admin->admin_id .'">Xóa Quyền 
                    </button>
                </div>
                <div style="margin-top: 10px">
                    <a href="'.url('admin/auth/impersonate?admin_id=' . $admin->admin_id).'"><button
                            type="button" class="btn-sm btn-gradient-info btn-rounded btn-fw">Chuyển
                            Quyền</button>
                    </a>
                </div>
                <div style="margin-top: 10px">
                                        <a href="'. url('admin/auth/edit-admin?admin_id=' . $admin->admin_id).'"><button
                                                type="button" class="btn-sm btn-gradient-danger btn-dangee btn-fw">Chỉnh sửa</button>
                                        </a>
                </div>
            </td>
        </tr>
        ';
        }
        return $output;
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
