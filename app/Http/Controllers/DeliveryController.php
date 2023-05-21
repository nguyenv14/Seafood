<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Feeship;
use App\Models\Province;
use App\Models\Wards;
use Illuminate\Http\Request;
use App\Models\ManipulationActivity;
use Auth;
session_start();

class DeliveryController extends Controller
{
    public function show_delivery()
    {
        $cities = City::whereIn('matp',['48','46','49'])->orderby('matp', 'ASC')->get();
        ManipulationActivity::noteManipulationAdmin("Xem Thiết Lập Vận Chuyển");
        return view('admin.Delivery.add_delivery')->with(compact('cities'));
    }
    public function select_delivery(Request $request)
    {
        $data = $request->all();
        if ($data['action']) {
            $output = '';
            if ($data['action'] == 'city') {
                $select_province = Province::where('matp', $data['ma_id'])->orderby('maqh', 'ASC')->get();
                $output .= '<option value=""> ---Chọn Quận Huyện--- </option>';
                foreach ($select_province as $key => $province) {
                    $output .= '<option value=" ' . $province->maqh . '"> ' . $province->name_province . '</option>';
                }
            } else if ($data['action'] == 'province') {
                $select_wards = Wards::where('maqh', $data['ma_id'])->orderby('xaid', 'ASC')->get();
                $output .= '<option value=""> ---Chọn Xã Phường Thị Trấn--- </option>';
                foreach ($select_wards as $key => $ward) {
                    $output .= '<option value=" ' . $ward->xaid . '"> ' . $ward->name_ward . '</option>';
                }
            }
            echo $output;
        }
    }

    public function insert_delivery(Request $request)
    {
        $data = $request->all();
        $feeship = new Feeship();
        $feeship->fee_matp = $data['city'];
        $feeship->fee_maqh = $data['province'];
        $feeship->fee_maxp = $data['wards'];
        $feeship->fee_feeship = $data['fee_ship'];

        $feeship->save();
        ManipulationActivity::noteManipulationAdmin("Đặt Phí Vận Chuyển Vận Chuyển");
        return;
    }
    public function loading_feeship()
    {
        $feeship = Feeship::get();
        $output = '';
        foreach ($feeship as $key => $fee) {
            $output .= '
                    <tr>
                        <td>' . $fee->fee_id . '</td>
                        <td>' . $fee->city->name_city . '</td>
                        <td>' . $fee->province->name_province . '</td>
                        <td>' . $fee->wards->name_ward . '</td>
                        <td contenteditable class="fee_change" data-id_fee ="' . $fee->fee_id . '">' .number_format($fee->fee_feeship, 0, ',', '.').'đ'.'</td>
                        <td>
                        <button  style="border: none" class="delete_fee" data-id_fee = "' . $fee->fee_id . '"><i style="font-size: 22px" class="mdi mdi-delete-sweep text-danger "></i></button>
                        </td>
                    </tr>
                ';
        }
        echo $output;
    }

    public function update_delivery(Request $request)
    {      
        $data = $request->all();
        $feeship =  Feeship::where('fee_id', $data['feeship_id'])->first();
        $data_feeship = rtrim($data['feeship_value'] , '.');
        $data_feeship = rtrim($data['feeship_value'] , 'đ');
        $feeship->fee_feeship = $data_feeship;
        $feeship->save();
        ManipulationActivity::noteManipulationAdmin("Cập Nhật Phí Vận Chuyển ( ID : ".$data['feeship_id'].")");
    }

    public function delete_delivery(Request $request)
    {      
        $data = $request->all();
        $feeship =  Feeship::where('fee_id', $data['feeship_id'])->first();
        $feeship->delete();
        ManipulationActivity::noteManipulationAdmin("Xóa Phí Vận Chuyển ( ID : ".$data['feeship_id'].")");
    }


}
