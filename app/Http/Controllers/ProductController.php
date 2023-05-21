<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Category;
use Flashsale;
use GalleryProduct;
use Product;
use ProductDetails;
use App\Models\ManipulationActivity;
use Auth;
use Session;

session_start();

class ProductController extends Controller
{

    /* Back-End -  Product*/
    public function add_product()
    {
        $dataCategory = Category::orderby('category_id', 'desc')->get();
        return view('admin.Product.add_product')->with('dataCategory', $dataCategory);

    }
    public function save_product(Request $request)
    {
        /* Lưu Ý -> Tên Index Của Mảng Phải Trùng Với Tên Trường Trong Tab */
        $data = $request->all();
        $product = new Product();
        $product['product_name'] = $data['product_name'];
        $product['category_id'] = $data['product_category'];
        $product['product_desc'] = $data['product_desc'];
        $product['product_price'] = $data['product_price'];
        $product['product_unit'] = $data['product_unit'];
        $product['product_unit_sold'] = $data['product_unit_sold'];
        $get_image = $request->file('product_image');
        if ($get_image) {
            $get_image_name = $get_image->getClientOriginalName(); /* Lấy Tên File */
            $image_name = current(explode('.', $get_image_name)); /* VD Tên File Là nhan.jpg thì hàm explode dựa vào dấm . để phân tách thành 2 chuổi là nhan và jpg , còn hàm current để chuổi đầu , hàm end thì lấy cuối */
            $new_image = $image_name . rand(0, 99) . '.' . $get_image->getClientOriginalExtension(); /* getClientOriginalExtension() hàm lấy phần mở rộng của ảnh */
            $get_image->move('public\fontend\assets\img\product', $new_image);
            $data['product_image'] = $new_image;
            $product['product_image'] = $data['product_image'];
        } else {
            $product['product_image'] = '';
        }
        $product['product_status'] = $data['product_status'];
        $product->save();
        ManipulationActivity::noteManipulationAdmin( "Thêm Mới Sản Phẩm ".$product['product_name']);
        $this->message("success","Thêm Mới Sản Phẩm Thành Công!");
        return Redirect('admin/product/all-product');
    }
    public function all_product()
    {
        $all_product = Product::orderby('created_at', 'desc')->Paginate(6);
        $categories = Category::get();
        $productRubbish = Product::onlyTrashed()->get();
        $countDelete = $productRubbish->count();
        ManipulationActivity::noteManipulationAdmin( "Xem Danh Sách Sản Phẩm");
        return view('admin.Product.all_product')->with(compact('all_product', 'countDelete' , 'categories'));
    }
    public function edit_product(Request $request)
    {
        $dataCategory = Category::orderby('category_id', 'desc')->get();
        $dataOld = Product::where('product_id', $request->product_id)->get();
        return view('admin.Product.edit_product')->with('dataOld', $dataOld)->with('dataCategory', $dataCategory);
        return Redirect('admin/product/edit-product');
    }
    public function update_product(Request $request)
    {
        $data = $request->all();
        $product = Product::where('product_id', $data['product_id'])->first();
        $product['product_name'] = $data['product_name'];
        $product['category_id'] = $data['product_category'];
        $product['product_desc'] = $data['product_desc'];
        $product['product_price'] = $data['product_price'];
        $product['product_unit'] = $data['product_unit'];
        $product['product_unit_sold'] = $data['product_unit_sold'];
        $get_image = $request->file('product_image');
        if ($get_image) {
            $get_image_name = $get_image->getClientOriginalName(); /* Lấy Tên File */
            $image_name = current(explode('.', $get_image_name)); /* VD Tên File Là nhan.jpg thì hàm explode dựa vào dấm . để phân tách thành 2 chuổi là nhan và jpg , còn hàm current để chuổi đầu , hàm end thì lấy cuối */
            $new_image = $image_name . rand(0, 99) . '.' . $get_image->getClientOriginalExtension(); /* getClientOriginalExtension() hàm lấy phần mở rộng của ảnh */
            $get_image->move('public\fontend\assets\img\product', $new_image);
            $data['product_image'] = $new_image;
            $product['product_image'] = $data['product_image'];
        }
        $this->message("success","Cập Nhật Sản Phẩm Thành Công!");
        ManipulationActivity::noteManipulationAdmin( "Cập Nhật Sản Phẩm ". $product['product_name']."( ID : ". $data['product_id'].")");
        $product->save();
        return Redirect('admin/product/all-product');
    }
    public function delete_product(Request $request)
    {
        /*  destroy([1,2,3,4]) có thể xóa nhiều dòng theo mảng gồm nhiều id */
        $product_id = $request->product_id;
        $product = new Product();
        $flashsale = new Flashsale();
        $productdetails = new ProductDetails();

        $product_delete = $product->find_product_byId($product_id);
        $product_details_delete = $productdetails->find_product_details_byId($product_id);
        $flashsale_product_delete = $flashsale->find_product_flashsale_byProductID($product_id);
        if ($product_details_delete != null) {
            $product_details_delete->delete();
        }
        if ($flashsale_product_delete != null) {
            $flashsale_product_delete->delete();
        }
        $product_delete->delete();
        ManipulationActivity::noteManipulationAdmin( "Xóa Sản Sản Phẩm Vào Thùng Rác ( ID : ". $product_id.")");

    }

    public function list_soft_deleted_product()
    {
        $all_product = Product::take(7)->onlyTrashed()->orderby('created_at', 'desc')->get();
        ManipulationActivity::noteManipulationAdmin( "Xem Sản Phẩm Trong Thùng Rác");
        $all_product_details = ProductDetails::get();
        return view('admin.Product.soft_deleted_product')->with(compact('all_product', 'all_product_details'));
    }

    public function trash_delete(Request $request)
    {
        /*  destroy([1,2,3,4]) có thể xóa nhiều dòng theo mảng gồm nhiều id */
        $product_id = $request->product_id;
        $product = Product::withTrashed()->find($product_id);
        $productdetails = ProductDetails::withTrashed()->where('product_id', $product_id)->first();
        $flashsale = FlashSale::withTrashed()->where('product_id', $product_id)->first();

        $product->forceDelete();
        if ($productdetails != null) {
            $productdetails->forceDelete();
        }
        if ($flashsale != null) {
            $flashsale->forceDelete();
        }
        ManipulationActivity::noteManipulationAdmin( "Xóa Vĩnh Viển Sản Phẩm ( ID : ". $product_id.")");
        $this->message("success","Xóa Sản Phẩm Thành Công!");
        return Redirect('admin/product/all-product');
    }

    public function un_trash(Request $request)
    {
        $product_id = $request->product_id;

        $product = Product::withTrashed()->find($product_id);
        $productdetails = ProductDetails::withTrashed()->where('product_id', $product_id)->first();
        $flashsale = FlashSale::withTrashed()->where('product_id', $product_id)->first();

        $product->restore();
        if ($productdetails != null) {
            $productdetails->restore();
        }
        if ($flashsale != null) {
            $flashsale->restore();
        }
        ManipulationActivity::noteManipulationAdmin( "Khôi Phục Sản Phẩm ( ID : ". $product_id.")");
        $this->message("success","Khôi Phục Sản Phẩm Thành Công!");
        return Redirect('admin/product/all-product');
    }
    public function update_status_product(Request $request){
        $product = Product::where('product_id',  $request->product_id)->first();
        $product['product_status'] = $request->status;
        $product->save();
        if($request->status == 1){
            ManipulationActivity::noteManipulationAdmin( "Kích Hoạt Sản Phẩm ( ID : ".$request->product_id.")");
        }else if($request->status == 0){
            ManipulationActivity::noteManipulationAdmin( "Vô Hiệu Sản Phẩm ( ID : ".$request->product_id.")");
        }
    }

    public function sort_all(Request $request)
    {
        $type = $request->type;
        $check = $request->check;
        if($check == 'false'){
            if($type == 'product'){
                $all_product_z_a = Product::orderBy('product_name', 'DESC')->get();
                $output = $this->output_product($all_product_z_a);
            }
            if($type == 'category'){
                $all_category_z_a = Product::join('tbl_category','tbl_category.category_id','=','tbl_product.category_id')->orderBy('tbl_category.category_name', 'DESC')->get();
                $output = $this->output_product($all_category_z_a);
            }
            if($type == 'price'){
                $all_price_9_0 = Product::orderBy('product_price', 'DESC')->get();
                $output = $this->output_product($all_price_9_0);
            }
            if($type == 'quantity'){
                $all_quantity_9_0 = Product::orderBy('product_unit_sold', 'DESC')->get();
                $output = $this->output_product($all_quantity_9_0);
            }
        }else if($check == 'true'){
            if($type == 'product'){
                $all_product_a_z = Product::orderBy('product_name', 'ASC')->get();
                $output = $this->output_product($all_product_a_z);
            }
            if($type == 'category'){
                $all_category_a_z = Product::join('tbl_category','tbl_category.category_id','=','tbl_product.category_id')->orderBy('tbl_category.category_name', 'ASC')->get();
                $output = $this->output_product($all_category_a_z);
            }
            if($type == 'price'){
                $all_price_0_9 = Product::orderBy('product_price', 'ASC')->get();
                $output = $this->output_product($all_price_0_9);
            }
            if($type == 'quantity'){
                $all_quantity_0_9 = Product::orderBy('product_unit_sold', 'ASC')->get();
                $output = $this->output_product($all_quantity_0_9);
            }
        }
        echo $output;
    }
    public function all_product_sreach(Request $request)
    {
        $searchbyname_format = '%' . $request->key_sreach . '%';
        $all_product = Product::where('product_name', 'like', $searchbyname_format)->get();
        $output = $this->output_product($all_product);
        echo $output;
    }
    public function sort_product_by_category(Request $request){
        $category_id =  $request->category_id;
        if($category_id == -1){
            $all_product = Product::get();
        }else{
            $all_product = Product::where('category_id', 'like', $request->category_id)->get();
        }
        $output = $this->output_product($all_product);
        echo $output;
    }
    /* Cải Tiến */
    /* Product Details */
    public function product_details(Request $request)
    {
        $productdetails = new ProductDetails();
        $product = $productdetails->find_product_details_byId($request->product_id);
        ManipulationActivity::noteManipulationAdmin( "Xem Sản Phẩm Chi Tiết( ID : ".$request->product_id.")");
        return view('admin.Product.productdetails')->with(compact('product'));
    }

    public function add_product_details(Request $request)
    {
        $product_id = $request->product_id;
        $product = new Product();
        $product_byId = $product->find_product_byId($product_id);
        return view('admin.Product.add_product_details')->with('product', $product_byId);

    }
    public function save_product_details(Request $request)
    {
        $data = $request->all();
        $productdetails = new ProductDetails();
        $productdetails['product_id'] = $data['product_id'];
        $productdetails['product_details_content'] = $data['product_details_content'];
        $productdetails['product_details_quantity'] = $data['product_details_quantity'];
        $productdetails['product_details_deliveryway'] = $data['product_details_deliveryway'];
        $productdetails['product_details_origin'] = $data['product_details_origin'];
        $productdetails['product_details_delicious_foods'] = $data['product_details_delicious_foods'];
        $productdetails->save();
        ManipulationActivity::noteManipulationAdmin( "Thêm Thông Tin Chi Tiết Sản Phẩm ( ID : ".$data['product_id'].")");
        $this->message("success","Thêm Chi Tiết Sản Phẩm Thành Công!");
        return Redirect('admin/product/all-product');

    }
    public function edit_product_details(Request $request)
    {
        // $data = $request->all();
        $product_detail_id = $request->product_id;
        $product_detail = ProductDetails::where('product_id', $product_detail_id)->first();

        return view('admin.Product.edit_product_details')->with('product_detail', $product_detail);
    }

    public function update_product_details(Request $request)
    {
        $data = $request->all();
        // dd($request->all());

        $productdetail = ProductDetails::where('product_details_id', $request->product_detail_id)->first();
        $productdetail['product_details_content'] = $data['product_details_content'];
        $productdetail['product_details_quantity'] = $data['product_details_quantity'];
        $productdetail['product_details_deliveryway'] = $data['product_details_deliveryway'];
        $productdetail['product_details_origin'] = $data['product_details_origin'];
        $productdetail['product_details_delicious_foods'] = $data['product_details_delicious_foods'];
        $productdetail->save();
        ManipulationActivity::noteManipulationAdmin( "Cập Nhật Chi Tiết Sản Phẩm ( ID SP Chi Tiết : ".$request->product_detail_id.")");
        $this->message("success","Cập Nhật Chi Tiết Sản Phẩm Thành Công!");
        return Redirect('admin/product/all-product');
    }

    /* Đưa Toàn Bộ Dữ Liệu Ra Bảng Bằng Ajax */
    public function all_product_ajax()
    {
        $all_product = Product::orderby('created_at', 'desc')->paginate(6);
        $output = $this->output_product($all_product);
        echo $output;
    }

    public function output_product($all_product){
        $all_product_details = ProductDetails::get();
        $output = '';
        foreach ($all_product as $key => $products){
            $output .= '
            <tr>
            <td>'. $products->product_name.' </td>
            <td>'.$products->category->category_name.'</td>
            ';
            $product_unit = '';
            switch ($products->product_unit) {
                case '0':
                    $product_unit = 'Con';
                    break;
                case '1':
                    $product_unit = 'Phần';
                    break;
                case '2':
                    $product_unit = 'khay';
                    break;
                case '3':
                    $product_unit = 'Túi';
                    break;
                case '4':
                    $product_unit = 'Kg';
                    break;
                case '5':
                    $product_unit = 'Gam';
                    break;
                case '6':
                    $product_unit = 'Combo';
                    break;
                default:
                    $product_unit = 'Bug Rùi :<';
                    break;
            }
            $output .= '
            <td>'. $products->product_unit_sold . ' ' . $product_unit.'</td>
            <td> '.number_format($products->product_price).'</td>
            <td><img style="object-fit: cover" width="40px" height="20px"
                    src="'.URL('public/fontend/assets/img/product/' . $products->product_image).'"
                    alt=""></td>
            <td>
            ';
                if ($products->product_status == 1){
                    $output .= '
                    <span class = "update-status" data-product_id = "'.$products->product_id.'" data-status = "0">
                    <i style="color: rgb(52, 211, 52); font-size: 30px"
                        class="mdi mdi-toggle-switch"></i>
                    </span>
                    ';
                }else{
                    $output .= '
                    <span class = "update-status" data-product_id = "'.$products->product_id.'" data-status = "1" >
                        <i style="color: rgb(196, 203, 196);font-size: 30px"
                            class="mdi mdi-toggle-switch-off"></i>
                    </span>
                    ';
                }
             $output .= '  
            </td>

            <td>
                <a
                    href="'. URL('admin/product/product-details?product_id=' . $products->product_id).'">
                    <i style="font-size: 20px;padding-right: 5px; color: rgb(230, 168, 24)"
                        class=" mdi mdi-clipboard-outline"></i>
                </a>
            ';
               
                $check = '';
                foreach ($all_product_details as $product_details){
                    if($product_details->product_id == $products->product_id){
                        $check = 1;
                    }
                }
                  
              

                if ($check == 1){
                    $output .= '  
                    <a
                    href="'. URL('admin/product/edit-product-details?product_id=' . $products->product_id).'">
                    <i style="font-size: 20px;padding-right: 5px; color: rgb(24, 230, 51)"
                        class="mdi mdi-table-edit"></i>
                    </a>
                    ';
                }else{
                    $output .= '  
                    <a
                        href="'.URL('admin/product/add-product-details?product_id=' . $products->product_id).'">
                        <i style="font-size: 20px;padding-right: 5px; color: rgb(208, 30, 202)"
                            class="mdi mdi-calendar-plus"></i>
                    </a>
                    ';
                }
                $output .= '  
                <a
                    href="'.URL('admin/product/edit-product?product_id=' . $products->product_id).'">
                    <i style="font-size: 20px" class="mdi mdi-lead-pencil"></i>
                </a>
                <span class = "btn-delete-product" data-product_id = "'.$products->product_id.'"
                    style="margin-left: 4px">
                    <i style="font-size: 22px" class="mdi mdi-delete-sweep text-danger "></i>
                </span>              
            </td>
        </tr>
        ';
        }
      return $output;
    }
    /* Thư Viện Ảnh - Gallery */
    public function loading_gallery(Request $request)
    {

        $galleryproduct = new GalleryProduct();
        $gallerys = $galleryproduct->listGalleryProducbyId($request->product_id);
        $output = '';
        $i = 0;

        foreach ($gallerys as $gallery) {
            $output .= '
            <tr>
                <td>  ' . ++$i . ' </td>
                <td> ' . $gallery->product_id . ' </td>
                <td contentEditable class="update_gallery_product_name"  data-gallery_id = "' . $gallery->gallery_product_id . '"> <div style="width: 100px;overflow: hidden;">  '. $gallery->gallery_product_name.' </div>  </td>
                <td>

                <form>
                ' . csrf_field() . '
                <input hidden id="up_load_file' . $gallery->gallery_product_id . '" class="up_load_file"  type="file" name="file_image" accept="image/*" data-gallery_id = "' . $gallery->gallery_product_id . '">
                <label class="up_load_file" for="up_load_file' . $gallery->gallery_product_id . '" > <img style="object-fit: cover" width="40px" height="20px"
                src=' . URL('public/fontend/assets/img/product/' . $gallery->gallery_product_image) . ' alt=""></label>
                </form>
               </td>
                <td  contentEditable  class="edit_gallery_product_content"  data-gallery_id = "' . $gallery->gallery_product_id . '"><div style="width: 200px;overflow: hidden">  '. $gallery->gallery_product_content .' </div>  </td>
                <td>';
                // if(hasanyroles(["admin","manager"])){
                    $output .= '
                    <button  style="border: none" class="delete_gallery_product" data-gallery_id = "' . $gallery->gallery_product_id . '"><i style="font-size: 22px" class="mdi mdi-delete-sweep text-danger "></i></button>
                    ';
                // }
                $output .= '
                </td>
            </a>
            </tr>
            ';
        }
        echo $output;
    }

    public function insert_gallery(Request $request)
    {
        /* Bên kia input name="file[]" nên gửi qua là 1 mảng chứa toàn bộ ảnh , sử dụng dd() để rõ hơn*/
        $product_id = $request->product_id;
        $get_images = $request->file('file');
        
        if ($get_images) {
            foreach ($get_images as $get_image) {
                $get_image_name = $get_image->getClientOriginalName(); /* Lấy Tên File */
                $image_name = current(explode('.', $get_image_name)); /* VD Tên File Là nhan.jpg thì hàm explode dựa vào dấm . để phân tách thành 2 chuổi là nhan và jpg , còn hàm current để chuổi đầu , hàm end thì lấy cuối */
                $new_image = $image_name . rand(0, 99) . '.' . $get_image->getClientOriginalExtension(); /* getClientOriginalExtension() hàm lấy phần mở rộng của ảnh */
                $get_image->move('public\fontend\assets\img\product', $new_image);

                $gallery = new GalleryProduct();
                $gallery['product_id'] = $product_id;
                $gallery['gallery_product_name'] = $image_name;
                $gallery['gallery_product_image'] = $new_image;
                $gallery['gallery_product_content'] = "Ảnh này chưa có nội dung !";
                $gallery->save();
            }
        }
        ManipulationActivity::noteManipulationAdmin( "Thêm Vào ".count($get_images)." Hình Ảnh Vào Thư Viện ( ID : ".$request->product_id.")");
        $this->message("success","Thêm Vào ".count($get_images)." Hình Ảnh Vào Thư Viện Thành Công !");
        return redirect()->back();
    }
    public function delete_gallery(Request $request)
    {
        $gallery_product_id = $request->gallery_id;
        $gallery = new GalleryProduct();
        $gallery_product_delete = $gallery->listGallerybyId($gallery_product_id);
        ManipulationActivity::noteManipulationAdmin( "Xóa Ảnh Ở Thư Viện ( ID Ảnh : ".$request->gallery_id.")");
        $gallery_product_delete->delete();
        echo "true";
        //unlink('public/fontend/assets/img/product/', $gallery_product_delete->gallery_product_image); /* Xóa ảnh ở trong thư mục */

    }
    public function update_content_gallery(Request $request)
    {
        $gallery_product_id = $request->gallery_id;
        $gallery_product_content = $request->gallery_content;
        $gallery_product_update = GalleryProduct::where('gallery_product_id', $gallery_product_id)->first();
        $gallery_product_update['gallery_product_content'] = $gallery_product_content;
        ManipulationActivity::noteManipulationAdmin( "Cập Nhật Nội Dung Ảnh ( ID Ảnh : ".$request->gallery_id.")");
        $gallery_product_update->save();
    }
    public function update_name_gallery(Request $request)
    {
        $gallery_product_id = $request->gallery_id;
        $gallery_product_name = $request->gallery_name;
        $gallery_product_update = GalleryProduct::where('gallery_product_id', $gallery_product_id)->first();
        $gallery_product_update['gallery_product_name'] = $gallery_product_name;
        ManipulationActivity::noteManipulationAdmin( "Cập Nhật Tên Ảnh ( ID Ảnh : ".$request->gallery_id.")");
        $gallery_product_update->save();
    }
    public function update_image_gallery(Request $request)
    {
        $gallery_product_id = $request->gallery_id;
        $get_image = $request->file('file');

        if ($get_image) {
            $get_image_name = $get_image->getClientOriginalName(); /* Lấy Tên File */
            $image_name = current(explode('.', $get_image_name)); /* VD Tên File Là nhan.jpg thì hàm explode dựa vào dấm . để phân tách thành 2 chuổi là nhan và jpg , còn hàm current để chuổi đầu , hàm end thì lấy cuối */
            $new_image = $image_name . rand(0, 99) . '.' . $get_image->getClientOriginalExtension(); /* getClientOriginalExtension() hàm lấy phần mở rộng của ảnh */
            $get_image->move('public/fontend/assets/img/product', $new_image);
            $gallery = GalleryProduct::where('gallery_product_id', $gallery_product_id)->first();
            echo $gallery->gallery_product_image;
            //unlink('public/fontend/assets/img/product/', $gallery->gallery_product_image); /* Xóa ảnh ở trong thư mục */
            $gallery['gallery_product_image'] = $new_image;
            $gallery->save();
        }
        ManipulationActivity::noteManipulationAdmin( "Cập Nhật Ảnh ( ID Ảnh : ".$request->gallery_id.")");
    }

    /* Font - End */
    public function san_pham_chi_tiet_flash_sale(Request $request)
    {
        $meta = array(
            'title' => 'Trang Chủ - Thế Giới Hải Sản',
            'description' => 'Thế Giới Hải Sản - Trang Tìm Kiếm Và Đặt Hải Sản Hàng Đầu Việt Nam',
            'keywords' => 'Hải Sản Đà Nẵng , Hải Sản Giá Rẻ , Hải Sản Tươi Sống , Hải Sản Giao Nhanh , Tôm Hùng , Cua , Ghẹ , .....',
            'canonical' => request()->url(),
            'sitename' => 'sepnguyenvanhanbro.thegioihaisan.laravel.vn',
            'image' => '',
        );

        /* Lấy Thông Tin Sản Phẩm */
        $flashsale = new Flashsale();
        $productdetails = new ProductDetails();
        $product = new Product();
        $galleryproduct = new GalleryProduct();

        $flashsale_product = $flashsale->find_product_flashsale_byID($request->flashsale_id);
        $product_id = $flashsale_product->product_id;
        $products = $productdetails->find_product_details_byId($product_id);

        /* Lấy Toàn Bộ Sản Phẩm Cùng Danh Mục */
        $category_id = $flashsale_product->product->category_id;
        $all_product_by_category = $product->find_all_product_byCategory($category_id,$product_id);
         /* Lấy Toàn Bộ Ảnh Của Sản Phẩm */
         $all_gallery_product = $galleryproduct->listGalleryProducbyId($product_id);

        /* Thiết Lập Sản Phẩm Xem Gần Đây */
        $data = array();
        
        $data['product_id'] = $products->product->product_id;
        $data['product_name'] = $products->product->product_name;
        $data['product_image'] = $products->product->product_image;
        $data['category_name'] = $products->product->category->category_name;

        $this->Recentlyviewed($data);
        ManipulationActivity::noteManipulationCustomer( "Xem Chi Tiết Sản Phẩm ".$products->product->product_name." Của Flashsale ( ID Flashsale : ".$request->flashsale_id.")");
        return view('pages.home.sanphamchitiet')->with(compact('all_product_by_category', 'flashsale_product', 'products','all_gallery_product','meta'));

    }

 

    public function san_pham_chi_tiet(Request $request)
    {
        $meta = array(
            'title' => 'Trang Chủ - Thế Giới Hải Sản',
            'description' => 'Thế Giới Hải Sản - Trang Tìm Kiếm Và Đặt Hải Sản Hàng Đầu Việt Nam',
            'keywords' => 'Hải Sản Đà Nẵng , Hải Sản Giá Rẻ , Hải Sản Tươi Sống , Hải Sản Giao Nhanh , Tôm Hùng , Cua , Ghẹ , .....',
            'canonical' => request()->url(),
            'sitename' => 'sepnguyenvanhanbro.thegioihaisan.laravel.vn',
            'image' => '',
        );
        $product_id = $request->product_id;
        $product = new Product();
        $product_by_id = $product->find_product_byId($product_id);
        $product_by_id['product_viewer'] = $product_by_id['product_viewer'] + 1;
        $product_by_id->save();
        /* Check Xem Sản Phẩm Thuộc Flash Sale Không - Thuộc Thì Chuyển Hướng */
        $flashsale = new Flashsale();
        $flashsale_product = $flashsale->find_product_flashsale_byProductID($product_id);
        if($flashsale_product != NULL){
            $flashsale_id = $flashsale_product->flashsale_id;
            return Redirect('/san-pham/san-pham-chi-tiet-flash-sale?flashsale_id='.$flashsale_id.'');
        }
        /* Lấy Thông Tin Sản Phẩm */
        $flashsale_product = null;
        $productdetails = new ProductDetails();
        $galleryproduct = new GalleryProduct();
        $products = $productdetails->find_product_details_byId($product_id);
        /* Lấy Toàn Bộ Sản Phẩm Cùng Danh Mục */
        $category_id = $products->product->category_id;
        $all_product_by_category = $product->find_all_product_byCategory($category_id,$product_id);
        /* Lấy Toàn Bộ Ảnh Của Sản Phẩm */
        $all_gallery_product = $galleryproduct->listGalleryProducbyId($product_id);

        /* Thiết Lập Sản Phẩm Xem Gần Đây */
        /* Dòng chảy dữ liệu */
        /*
        - Tạo Session_ID và Mỗi recentlyviewed Sẽ Chứa 1 Session_ID riêng
        - Đầu Tiên Lấy Toàn Bộ Dữ Liệu Card Ở Session recentlyviewed
        - Tồn Tại recentlyviewed Thì Kiểm Tra Dữ Liệu recentlyviewed(ID Product) Đưa Xem Có Trùng Với recentlyviewed Cũ(ID Product) Không
        - Trùng Thì Không Thêm Dữ Liệu Vào Session recentlyviewed
        - Không Trùng Thì Thêm Dô
        -Trường Hợp Chưa Có recentlyviewed Thì Khởi Tạo recentlyviewed[] Rồi Thêm Vào
         */
        $data = array();
        $data['product_id'] = $product_id;
        $data['product_name'] = $products->product->product_name;
        $data['product_image'] = $products->product->product_image;
        $data['category_name'] = $products->product->category->category_name;
        $this->Recentlyviewed($data);
        ManipulationActivity::noteManipulationCustomer("Xem Chi Tiết Sản Phẩm ".$products->product->product_name." ( ID Product : ".$product_id.")");
        return view('pages.home.sanphamchitiet')->with(compact('all_product_by_category', 'products', 'flashsale_product' ,'all_gallery_product','meta'));

    }

    public function Recentlyviewed($data){
        $session_id = substr(md5(microtime()), rand(0, 26), 5);
        $recentlyviewed = Session::get('recentlyviewed');
        if ($recentlyviewed == true) {
            $is_avaiable = 0;
            foreach ($recentlyviewed as $key => $value) {
                if ($value['product_id'] == $data['product_id']) {
                    $is_avaiable++;
                }
            }
            if ($is_avaiable == 0) {
                $recentlyviewed[] = array(
                    'session_id' => $session_id,
                    'product_id' => $data['product_id'],
                    'product_name' => $data['product_name'],
                    'product_image' => $data['product_image'],
                    'category_name' => $data['category_name'],

                );
                session()->put('recentlyviewed', $recentlyviewed);
            }
        } else {
            $recentlyviewed[] = array(
                'session_id' => $session_id,
                'product_id' => $data['product_id'],
                'product_name' => $data['product_name'],
                'product_image' => $data['product_image'],
                'category_name' => $data['category_name'],
            );
        }
        session()->put('recentlyviewed', $recentlyviewed);
        Session::save();


    }

    public function message($type,$content){
        $message = array(
            "type" => "$type",
            "content" => "$content",
        ); 
        Session::put('message', $message);
    }
}
