<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Coupon;
use App\Models\Flashsale;
use App\Models\GalleryProduct;
use App\Models\OrderDetails;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use ProductDetails;
use Session;

use Carbon\Carbon;

session_start();
class APIProductController extends Controller
{
    public function getEvaluateProduct(Request $request){
        $Comments = Comment::where('comment_product_id',$request->product_id)->get();
        if( $Comments->count() > 0){
            foreach ($Comments as $value) {
                $data[] = array(
                    'comment_id' =>  $value->comment_id,
                    'comment_title' => $value->comment_title,
                    'comment_content' => $value->comment_content,
                    'comment_customer_id' => $value->comment_customer_id,
                    'comment_customer_name' => $value->comment_customer_name,
                    'comment_product_id' => $value->comment_product_id,
                    'comment_rate_star' => $value->comment_rate_star,
                );
            }

            return response()->json([
                'status_code' => 200,
                'message' => 'Ok',
                'data' => $data,
            ]);

        }else{
            return response()->json([
                'status_code' => 404,
                'message' => 'Không có dữ liệu trả về !',
                'data' => null,
            ]);
        }
    }

    public function getGalleryProduct(Request $request){
        $galleryProduct =  GalleryProduct::where('product_id' , $request->product_id)->get();
        if($galleryProduct->count() > 0){
            foreach ($galleryProduct as $value) {
                $result_arr[] = array(
                    'gallery_product_id' => $value->gallery_product_id,    
                    'product_id' => $value->product_id,    
                    'gallery_product_name' => $value->gallery_product_name,  
                    'gallery_product_image' => 'http://192.168.1.7/DoAnCNWeb/public/fontend/assets/img/product/' . $value->gallery_product_image,
                    'gallery_product_content' => $value->gallery_product_content,
                );
            }
            return response()->json([
                'status_code' => 200,
                'message' => 'ok',
                'data' => $result_arr,
            ]);
        }else {
            return response()->json([
                'status_code' => 404,
                'message' => 'Không có dữ liệu trả về !',
                'data' => null,
            ]);
        }
    }


    public function getProduct(Request $request){
        $product = Product::paginate(4);
        return $this->fetchJsonProduct($product);
    }



    public function getProductByCategory(Request $request){
        $product_by_category = Product::where('category_id',$request->category_id)->WhereNotIn('product_id',[$request->product_id])->where('product_status', 1)->get();
        return $this->fetchJsonProduct($product_by_category);
    }

    public function getProductByCategoryId(Request $request){
        $product_by_category = Product::where('category_id',$request->category_id)->where('product_status', 1)->get();
        return $this->fetchJsonProduct($product_by_category);
    }

    public function getOrderProduct()
    {
        /* Thuật Toán Sản Phẩm Flashsale - Dựa Vào Flashsale Status */
        $flashsale = Flashsale::where('flashsale_status', '1')->take(6)->get();
        /* Thuật Toán Sản Phẩm Bán Chạy - Dựa Vào OrderDetails */
        $list_id_product_order = array();
        $orderDetails = OrderDetails::get();
        foreach ($orderDetails as $key => $v_orderDetails) {
            $product_not_flashsale = Product::where('product_id', $v_orderDetails->product_id)->where('flashsale_status', 0)->first();
            if ($product_not_flashsale) {
                $list_id_product_order[$key] = $v_orderDetails->product_id;
            }
        }
        $i = 0;
        $list_id_product_order = array_unique($list_id_product_order);
        $list_id_product_order_5 = array();
        foreach ($list_id_product_order as $key => $v_list_id_product_order) {
            $list_id_product_order_5[$key] = $v_list_id_product_order;
            $i++;
            if ($i == 4) {
                break;
            }
        }
        $best_sale_product = Product::wherein('product_id', $list_id_product_order_5)->where('product_status', 1)->where('flashsale_status', '0')->get();
        /* Kết Thúc Thuật Toán */
        return $this->fetchJsonProduct($best_sale_product);

    }

    public function getTrendingProduct(){
        /* Thuật Toán Sản Phẩm Flashsale - Dựa Vào Flashsale Status */
        $flashsale = Flashsale::where('flashsale_status', '1')->take(6)->get();
        /* Thuật Toán Sản Phẩm Bán Chạy - Dựa Vào OrderDetails */
        $list_id_product_order = array();
        $orderDetails = OrderDetails::get();
        foreach ($orderDetails as $key => $v_orderDetails) {
            $product_not_flashsale = Product::where('product_id', $v_orderDetails->product_id)->where('flashsale_status', 0)->first();
            if ($product_not_flashsale) {
                $list_id_product_order[$key] = $v_orderDetails->product_id;
            }
        }
        $i = 0;
        $list_id_product_order = array_unique($list_id_product_order);
        $list_id_product_order_5 = array();
        foreach ($list_id_product_order as $key => $v_list_id_product_order) {
            $list_id_product_order_5[$key] = $v_list_id_product_order;
            $i++;
            if ($i == 4) {
                break;
            }
        }
        $best_sale_product = Product::wherein('product_id', $list_id_product_order_5)->where('product_status', 1)->where('flashsale_status', '0')->get();
        /* Kết Thúc Thuật Toán */
        /* Thuật Toán Sản Phẩm Đang Thịnh Hành - Dựa Vào Viewer */
        $viewer_product = Product::where('product_status', 1)->where('flashsale_status', '0')->wherenotin('product_id', $list_id_product_order_5)->orderby('product_viewer', 'DESC')->take(5)->get();
      
        /* Kết Thúc Thuật Toán */
        return $this->fetchJsonProduct($viewer_product);
    }

    public function getNewProduct(){
        /* Thuật Toán Sản Phẩm Flashsale - Dựa Vào Flashsale Status */
        $flashsale = Flashsale::where('flashsale_status', '1')->take(6)->get();
        /* Thuật Toán Sản Phẩm Bán Chạy - Dựa Vào OrderDetails */
        $list_id_product_order = array();
        $orderDetails = OrderDetails::get();
        foreach ($orderDetails as $key => $v_orderDetails) {
            $product_not_flashsale = Product::where('product_id', $v_orderDetails->product_id)->where('flashsale_status', 0)->first();
            if ($product_not_flashsale) {
                $list_id_product_order[$key] = $v_orderDetails->product_id;
            }
        }
        $i = 0;
        $list_id_product_order = array_unique($list_id_product_order);
        $list_id_product_order_5 = array();
        foreach ($list_id_product_order as $key => $v_list_id_product_order) {
            $list_id_product_order_5[$key] = $v_list_id_product_order;
            $i++;
            if ($i == 4) {
                break;
            }
        }
        $best_sale_product = Product::wherein('product_id', $list_id_product_order_5)->where('product_status', 1)->where('flashsale_status', '0')->get();
        /* Kết Thúc Thuật Toán */
        /* Thuật Toán Sản Phẩm Đang Thịnh Hành - Dựa Vào Viewer */
        $viewer_product = Product::where('product_status', 1)->where('flashsale_status', '0')->wherenotin('product_id', $list_id_product_order_5)->orderby('product_viewer', 'DESC')->take(5)->get();
        $list_id_viewer_product = array();
        foreach ($viewer_product as $key => $v_viewer_product) {
            $list_id_viewer_product[$key] = $v_viewer_product->product_id;
        }
        /* Kết Thúc Thuật Toán */
        /* Thuật Toán Sản Phẩm Mới - Dựa Vào Product_id */
        $new_product = Product::where('product_status', 1)->where('flashsale_status', '0')->wherenotin('product_id', $list_id_product_order_5)->wherenotin('product_id', $list_id_viewer_product)->take(5)->orderby('product_id', 'DESC')->get();
        return $this->fetchJsonProduct($new_product);
    }

    public function getProductBySearch(Request $request){
        $product_name = $request->product_name;
        $searchbyname_format = '%' . $product_name . '%';
        $category_name = $request->category_name;
        $number = $request->filter_number;
        $priceMin = $request->priceMin;
        $priceMax = $request->priceMax;
        $category = Category::where("category_name", $category_name)->first();
        if($number == 0){
            if($category != null){
                $all_product = Product::where('product_name', 'like', $searchbyname_format)->where("category_id", $category->category_id)->where('product_price', '>=', $priceMin)->where("product_price", '<=', $priceMax)->get();
            }else{
                $all_product = Product::where('product_name', 'like', $searchbyname_format)->where('product_price', '>=', $priceMin)->where("product_price", '<=', $priceMax)->get();
            }
        }else if($number == 1){
            if($category != null){
                $all_product = Product::where('product_name', 'like', $searchbyname_format)->where("category_id", $category->category_id)->where('product_price', '>=', $priceMin)->where("product_price", '<=', $priceMax)->orderby("product_price", "DESC")->get();
            }else{
                $all_product = Product::where('product_name', 'like', $searchbyname_format)->where('product_price', '>=', $priceMin)->where("product_price", '<=', $priceMax)->orderby("product_price", "DESC")->get();
            }
        }else if($number == 2){
            if($category != null){
                $all_product = Product::where('product_name', 'like', $searchbyname_format)->where("category_id", $category->category_id)->where('product_price', '>=', $priceMin)->where("product_price", '<=', $priceMax)->orderby("product_price", "ASC")->get();
            }else{
                $all_product = Product::where('product_name', 'like', $searchbyname_format)->where('product_price', '>=', $priceMin)->where("product_price", '<=', $priceMax)->orderby("product_price", "ASC")->get();
            }
        }else if($number == 3){
            if($category != null){
                $all_product = Product::where('product_name', 'like', $searchbyname_format)->where("category_id", $category->category_id)->where('product_price', '>=', $priceMin)->where("product_price", '<=', $priceMax)->orderby("product_name", "ASC")->get();
            }else{
                $all_product = Product::where('product_name', 'like', $searchbyname_format)->where('product_price', '>=', $priceMin)->where("product_price", '<=', $priceMax)->orderby("product_name", "ASC")->get();
            }
        }else if($number == 4){
            if($category != null){
                $all_product = Product::where('product_name', 'like', $searchbyname_format)->where("category_id", $category->category_id)->where('product_price', '>=', $priceMin)->where("product_price", '<=', $priceMax)->orderby("product_name", "DESC")->get();
            }else{
                $all_product = Product::where('product_name', 'like', $searchbyname_format)->where('product_price', '>=', $priceMin)->where("product_price", '<=', $priceMax)->orderby("product_name", "DESC")->get();
            }
        }
        return $this->fetchJsonProduct($all_product);
    }

    public function fetchJsonProduct($products){
        $post = "192.168.1.7";
        if($products->count() > 0){
            foreach ($products as $key => $value) {
                $Comments = Comment::where('comment_product_id',$value->product_id)->get();

                $time = "";
                Carbon::setLocale('vi');
                $order_details = OrderDetails::where('product_id',$value->product_id)->orderby('order_details_id','DESC')->first();
                if($order_details){
                    $create_product_order = Carbon::create($order_details->created_at, 'Asia/Ho_Chi_Minh');
                    $now = Carbon::now('Asia/Ho_Chi_Minh');
                    $time.='Đặt '.$create_product_order->diffForHumans($now);
                }

                $data[] = array(
                        "product_id" => $value->product_id,
                        "category_id" => $value->category_id,
                        "category_name" => $value->category->category_name, 
                        "product_name" => $value->product_name,
                        "product_desc" => $value->product_desc,
                        "product_price" => $value->product_price,
                        "product_image" => "http://".$post."/DoAnCNWeb/public/fontend/assets/img/product/".$value->product_image,
                        "product_unit" => $value->product_unit,
                        "product_unit_sold" => $value->product_unit_sold,
                        "product_status" => $value->product_status,
                        "product_viewer" => $value->product_viewer,
                        "product_content" => $value->detail->product_details_content,
                        "product_quantity" => $value->detail->product_details_quantity,
                        "product_deliveryway" => $value->detail->product_details_deliveryway,
                        "product_origin" => $value->detail->product_details_origin,
                        "product_delicious_foods" => $value->detail->product_details_delicious_foods,
                        "commentList" => $Comments->toArray(),
                        "status_order" => $time,
                        "flashsale_status" => $value->flashsale_status,
                );
            }
                return response()->json([
                    'status_code' => 200,
                    'message' => 'ok',
                    'data' => $data,
                ]);
        }else{
            return response()->json([
                'status_code' => 404,
                'message' => 'Không có dữ liệu trả về !',
                'data' => null,
            ]) ;
        }
    }

    public function getPriceMinPriceMax(){
        $productMin = Product::orderBy("product_price", "ASC")->first();
        $productMax = Product::orderBy("product_price", "DESC")->first();
        $data[] = $productMin->toArray();
        $data[] = $productMax->toArray();
        return response()->json([
            'status_code' => 200,
            'message' => 'Thành công !',
            'data' => $data,
        ]);
    }

    public function all_product()
    {  
        $all_product = Product::get(); 
        return $this->fetchJsonProduct($all_product);
    }

}