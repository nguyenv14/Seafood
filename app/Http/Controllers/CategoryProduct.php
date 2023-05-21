<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use Category;
use Flashsale;
use Product;
use ProductDetails;
use App\Models\ManipulationActivity;
use App\Repositories\CategoryRepository\CategoryRepositoryInterface;
use Auth;

use Session;

session_start();
class CategoryProduct extends Controller
{
    protected $cateRepo;

    public function __construct(CategoryRepositoryInterface $cateRepo)
    {
        $this->cateRepo = $cateRepo;
    }

    public function add_category()
    {
        return view('admin.Category.add_category');
    }

    public function all_category()
    {   
        $all_category = $this->cateRepo->getAllByPaginate(5);
        ManipulationActivity::noteManipulationAdmin( "Xem Danh Sách Danh Mục");
        return view('admin.Category.all_category')->with('all_category', $all_category);
    }
    public function save_category(Request $request)
    {   
        $data = $request->all();
        $this->cateRepo->create($data);
        ManipulationActivity::noteManipulationAdmin( "Thêm Mới Danh Mục ".$request->category_name);
        $this->message("success","Thêm Mới Danh Mục Thành Công!");
        return Redirect('/admin/category/all-category');
    }
    public function edit_category(Request $request)
    {
        $dataOld = $this->cateRepo->find($request->category_id);
        return view('admin.Category.edit_category')->with('editvalue', $dataOld);
    }
    public function update_category(Request $request)
    {
        $data = $request->all();
        $product = $this->cateRepo->update( $data['category_id'], $data);
        ManipulationActivity::noteManipulationAdmin( "Cập Nhật Danh Mục ". $data['category_name']."( ID : ". $data['category_id'].")");
        $this->message("success","Cập Nhật Danh Mục Thành Công!");
        return Redirect('/admin/category/all-category');
    }
    public function delete_category(Request $request)
    {
        $product = new Product();
        $productdetails = new ProductDetails();
        $flashsale = new Flashsale();

        /* Xóa Các Sản Phẩm Liên Quan Đến Danh Mục */
        $product_by_cate =  $product->find_all_product_byCategory($request->category_id);
        
        foreach($product_by_cate as $pro_by_cate){
            $product_id =  $pro_by_cate->product_id;

            $product_delete = $product->find_product_byId($product_id);
            $productdetails_delete = $productdetails->find_product_details_byId($product_id);
            $flashsale_delete = $flashsale->find_product_flashsale_byID($product_id);

            $product_delete->delete();
            $productdetails_delete->delete();
            $flashsale_delete->delete();
        }
         /* Xóa Danh Mục */
        $this->cateRepo->delete($request->category_id);
        ManipulationActivity::noteManipulationAdmin( "Xóa Danh Mục ( ID : ".$request->category_id.")");
        $this->message("success","Xóa Danh Mục Thành Công!");
        return Redirect('/admin/category/all-category');
    }

    public function active_category(Request $request)
    {
        $data = $request->all();
        $data['category_status'] = 1;
        $result = $this->cateRepo->update($data['category_id'], $data);
        ManipulationActivity::noteManipulationAdmin( "Kích Hoạt Danh Mục ( ID : ".$request->category_id.")");
        $this->message("success","Kích Hoạt Danh Mục Thành Công!");
        return redirect('/admin/category/all-category');
    }
    public function unactive_category(Request $request)
    {
        $data = $request->all();
        $data['category_status'] = 0;
        $result = $this->cateRepo->update($data['category_id'], $data);
        ManipulationActivity::noteManipulationAdmin( "Vô Hiệu Danh Mục ( ID : ".$request->category_id.")");
        $this->message("success","Vô Hiệu Danh Mục Thành Công!");
        return redirect('/admin/category/all-category');
    }

    public function message($type,$content){
        $message = array(
            "type" => "$type",
            "content" => "$content",
        ); 
        Session::put('message', $message);
    }

}
