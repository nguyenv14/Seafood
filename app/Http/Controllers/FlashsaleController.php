<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use Flashsale;
use Product;
use Session;
use App\Models\ManipulationActivity;
use Auth;
session_start();

class FlashsaleController extends Controller
{
    public function add_product_flashsale()
    {
        $product = new Product();
        $products =  $product->all_product();
        return view('admin.Flashsale.add_product_flashsale')->with(compact('products'));
    }
    public function save_product_flashsale(Request $request)
    {
        $data = $request->all();
        $product = new Product();
        $product_byId = $product->find_product_byId($data['product_id']);
        $product_price = $product_byId['product_price'];
        $flashsale = new Flashsale();
        $flashsale['product_id'] =  $data['product_id'];
        $flashsale['flashsale_condition'] =  $data['flashsale_condition'];
        $flashsale['flashsale_price_sale'] =  $data['flashsale_price_sale'];
        $flashsale['flashsale_status'] =  $data['flashsale_status'];
        if($data['flashsale_condition'] == 0){
            $flashsale['flashsale_product_price'] =  $product_price - ( $product_price / 100 ) *  $data['flashsale_price_sale'];
        }else{
            $flashsale['flashsale_product_price'] = $product_price - $data['flashsale_price_sale'];
        }
         $flashsale->save();
         /* Active FlashSale In Table Product */
         $product_byId['flashsale_status'] = 1;
         $product_byId->save();
         ManipulationActivity::noteManipulationAdmin("Thêm Sản Phẩm Vào Flashsale ( ID SP : ".$flashsale['product_id'].")");
         $this->message("success","Thêm Sản Phẩm Vào Chương Trình Flashsale Thành Công!");
         return redirect('/admin/flashsale/all-product-flashsale');
    }

    public function all_product_flashsale()
    {
        $flashsale = new Flashsale();
        $flashsales = $flashsale->all_product_flashsale();
        ManipulationActivity::noteManipulationAdmin("Xem Bảng Sản Phẩm Flashsale");
        return view('admin.Flashsale.all_product_flashsale')->with(compact('flashsales'));
    }
    public function edit_product_flashsale(Request $request)
    {
        $flashsale_id = $request->flashsale_id;
        $flashsale = new Flashsale();
        $flashsale_old = $flashsale->find_product_flashsale_byID($flashsale_id);
        return view('admin.Flashsale.edit_product_flashsale')->with(compact('flashsale_old'));
    }

    public function update_product_flashsale(Request $request)
    {
        $data = $request->all();
        $product = new Product();
        $product_byId = $product->find_product_byId($data['product_id']);
        $product_price = $product_byId['product_price'];
        
        $flashsales = new Flashsale();
        $flashsale =  $flashsales->find_product_flashsale_byID($data['flashsale_id']);
     
        $flashsale['flashsale_condition'] =  $data['flashsale_condition'];
        $flashsale['flashsale_price_sale'] =  $data['flashsale_price_sale'];
       
        if($data['flashsale_condition'] == 0){
            $flashsale['flashsale_product_price'] =  $product_price - ( $product_price / 100 ) *  $data['flashsale_price_sale'];
        }else{
            $flashsale['flashsale_product_price'] = $product_price - $data['flashsale_price_sale'];
        }
         $flashsale->save();
         ManipulationActivity::noteManipulationAdmin("Cập Nhật Sản Phẩm Flashsale ( ID FL : ".$data['flashsale_id'].")");
         $this->message("success","Cập Nhật Sản Phẩm Flashsale Thành Công!");
         return redirect('/admin/flashsale/all-product-flashsale');
    }

    public function delete_product_flashsale(Request $request)
    {
        $flashsale_id = $request->flashsale_id;
        $flashsales = new Flashsale();
        $flashsale =  $flashsales->find_product_flashsale_byID($flashsale_id);
        $flashsale->delete();
        $this->message("success","Xóa Sản Phẩm Flashsale Thành Công!");
        ManipulationActivity::noteManipulationAdmin("Xóa Sản Phẩm Flashsale ( ID FL : ".$request->flashsale_id.")");
        return redirect('/admin/flashsale/all-product-flashsale');
    }

    public function active_product_flashsale(Request $request)
    {
        $flashsale_id = $request->flashsale_id;
        $flashsales = new Flashsale();
        $flashsale =  $flashsales->find_product_flashsale_byID($flashsale_id);
        $flashsale['flashsale_status'] = 1;
        $flashsale->save();
        ManipulationActivity::noteManipulationAdmin("Kích Hoạt Sản Phẩm Flashsale ( ID FL : ".$request->flashsale_id.")");
        $this->message("success","Kích Hoạt Sản Phẩm Flashsale Thành Công!");
        return redirect('/admin/flashsale/all-product-flashsale');
    }

    public function unactive_product_flashsale(Request $request)
    {
        $flashsale_id = $request->flashsale_id;
        $flashsales = new Flashsale();
        $flashsale =  $flashsales->find_product_flashsale_byID($flashsale_id);
        $flashsale['flashsale_status'] = 0;
        $flashsale->save();
        ManipulationActivity::noteManipulationAdmin("Vô Hiệu Sản Phẩm Flashsale ( ID FL : ".$request->flashsale_id.")");
        $this->message("success","Vô Hiệu Sản Phẩm Flashsale Thành Công!");
        return redirect('/admin/flashsale/all-product-flashsale');
    }
    public function message($type,$content){
        $message = array(
            "type" => "$type",
            "content" => "$content",
        ); 
        Session::put('message', $message);
    }
}
