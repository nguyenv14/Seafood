<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use App\Models\Category;
use App\Models\Slider;
use Flashsale;
use Product;
use ProductDetails;
use Session;

session_start();
class APISliderProduct extends Controller
{
    public function add_category()
    {
        return view('admin.Category.add_category');
    }

    public function all_slider()
    {  
        $all_slider = Slider::get(); 
        if($all_slider->count() > 0){
            
            foreach ($all_slider as $key => $value) {
                $data[] = array(
                    "slider_id" => $value->slider_id,
                    "slider_name" => $value->slider_name,
                    "slider_image" => "http://192.168.1.7/DoAnCNWeb/public/fontend/assets/img/slider/".$value->slider_image,
                    "slider_status" => $value->slider_status,
                    "slider_desc" => $value->slider_desc,
                    "created_at" => $value->created_at,
                    "updated_at" => $value->updated_at,
                );
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
