<?php
namespace App\Repositories\SliderRepository;

use App\Repositories\BaseRepository;
class SliderRepository extends BaseRepository implements SliderRepositoryInterface
{
    //lấy model tương ứng
    public function getModel()
    {
        return \App\Models\Slider::class;
    }
    public function getAllByPaginate($value){
        return $this->model->paginate($value);
    }
    public function update_slider($data,$get_image){
        $slider = $this->find($data['slider_id']);
       
        if ($get_image != null) {
            $get_image_name = $get_image->getClientOriginalName(); /* Lấy Tên File */
            $image_name = current(explode('.', $get_image_name)); /* VD Tên File Là nhan.jpg thì hàm explode dựa vào dấm . để phân tách thành 2 chuổi là nhan và jpg , còn hàm current để chuổi đầu , hàm end thì lấy cuối */
            $new_image = $image_name . rand(0, 99) . '.' . $get_image->getClientOriginalExtension(); /* getClientOriginalExtension() hàm lấy phần mở rộng của ảnh */
            $get_image->move('public/fontend/assets/img/slider/', $new_image);
            unlink('public/fontend/assets/img/slider/'.$slider->slider_image);
            $slider['slider_image'] = $new_image;
        } 
       
        $slider->slider_id = $data['slider_id'];
        $slider->slider_name = $data['slider_name'];
        $slider->slider_status = $data['slider_status'];
        $slider->slider_desc = $data['slider_desc'];
        $slider->save();

    }
    public function insert_slider($data,$get_image){
        $get_image = $get_image;
        unset($data['_token']);
        if ($get_image != null) {
            $get_image_name = $get_image->getClientOriginalName(); /* Lấy Tên File */
            $image_name = current(explode('.', $get_image_name)); /* VD Tên File Là nhan.jpg thì hàm explode dựa vào dấm . để phân tách thành 2 chuổi là nhan và jpg , còn hàm current để chuổi đầu , hàm end thì lấy cuối */
            $new_image = $image_name . rand(0, 99) . '.' . $get_image->getClientOriginalExtension(); /* getClientOriginalExtension() hàm lấy phần mở rộng của ảnh */
            $get_image->move('public/fontend/assets/img/slider/', $new_image);
            $data['slider_image'] = $new_image;
        } 
        return $this->create($data);
    }
    public function delete_slider($slider_id){
        $slider = $this->find($slider_id);
        $this->delete($slider_id);
        unlink('public/fontend/assets/img/slider/'.$slider->slider_image);
    }
}