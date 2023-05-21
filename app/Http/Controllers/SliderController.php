<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Repositories\SliderRepository\SliderRepositoryInterface;
use Session;
use App\Models\ManipulationActivity;
use Auth;
session_start();

class SliderController extends Controller
{
     /**
     * @var PostRepositoryInterface|\App\Repositories\Repository
     */
    protected $sliderRepo;
    public function __construct(SliderRepositoryInterface $sliderRepo)
    {
        $this->sliderRepo = $sliderRepo;
    }

    public function add_slider(){
        return view('admin.Slider.add_slider');
    }
    public function save_slider(Request $request){
        $result = $this->sliderRepo->insert_slider($request->all(),$request->file('slider_image'));
        ManipulationActivity::noteManipulationAdmin( "Thêm Mới Slider ".$request->slider_name);
        $this->message("success","Thêm Mới Slider Thành Công!");
        return redirect('/admin/slider/all-slider');
    }

    public function all_slider(){
        $all_slider = $this->sliderRepo->getAllByPaginate(2);
        ManipulationActivity::noteManipulationAdmin( "Xem Danh Sách Slider");
        return view('admin.Slider.all_slider')->with(compact('all_slider'));
    }
    public function edit_slider(Request $request){
        $slider_old = $this->sliderRepo->find($request->slider_id);
        return view('admin.Slider.edit_slider')->with(compact('slider_old'));
    }

    public function update_slider(Request $request){
        $result = $this->sliderRepo->update_slider($request->all(),$request->file('slider_image'));
        ManipulationActivity::noteManipulationAdmin( "Cập Nhật Slider ".$request->slider_name."( ID : ".$request->slider_id.")");
        $this->message("success","Cập Nhật Slider Thành Công!");
        return redirect('/admin/slider/all-slider');
    }

    public function delete_slider(Request $request){
        $this->sliderRepo->delete_slider($request->slider_id);
        ManipulationActivity::noteManipulationAdmin( "Xóa Slider ( ID : ".$request->slider_id.")");
        $this->message("success","Xóa Slider Thành Công!");
        return redirect('/admin/slider/all-slider');
    }

    public function active_slider(Request $request){
        $data = $request->all();
        $data['slider_status'] = 1;
        $result = $this->sliderRepo->update($data['slider_id'], $data);
        ManipulationActivity::noteManipulationAdmin( "Kích Hoạt Slider ( ID : ".$data['slider_id'].")");
        $this->message("success","Kích Hoạt Slider Thành Công!");
        return redirect('/admin/slider/all-slider');
    }

    public function unactive_slider(Request $request){
        $data = $request->all();
        $data['slider_status'] = 0;
        $result = $this->sliderRepo->update($data['slider_id'], $data);
        ManipulationActivity::noteManipulationAdmin( "Vô Hiệu Slider ( ID : ".$data['slider_id'].")");
        $this->message("success","Vô Hiệu Slider Thành Công!");
        return redirect('/admin/slider/all-slider');
    }

    public function message($type,$content){
        $message = array(
            "type" => "$type",
            "content" => "$content",
        ); 
        Session::put('message', $message);
    }
}