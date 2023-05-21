<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductDetails;
use App\Models\OrderDetails;
use App\Models\Coupon;
use App\Models\Slider;
use App\Models\Category;
use App\Models\Flashsale;
use Session;
use DB;
use Carbon\Carbon;
use App\Models\ManipulationActivity;
use App\Models\Product as ModelsProduct;

session_start();
class HomeController extends Controller
{
    public function index(Request $request)
    {
        $meta = array(
            'title' => 'Trang Chủ - Thế Giới Hải Sản',
            'description' => 'Thế Giới Hải Sản - Trang Tìm Kiếm Và Đặt Hải Sản Hàng Đầu Việt Nam',
            'keywords' => 'Hải Sản Đà Nẵng , Hải Sản Giá Rẻ , Hải Sản Tươi Sống , Hải Sản Giao Nhanh , Tôm Hùng , Cua , Ghẹ , .....',
            'canonical' => request()->url(),
            'sitename' => 'sepnguyenvanhanbro.thegioihaisan.laravel.vn',
            'image' => '',
        );
        /* Thuật Toán Sản Phẩm Flashsale - Dựa Vào Flashsale Status */
        $flashsale = Flashsale::where('flashsale_status', '1')->take(6)->get();

        /* Thuật Toán Sản Phẩm Bán Chạy - Dựa Vào OrderDetails */
        $list_id_product_order = array();
        $orderDetails = OrderDetails::get();
        foreach($orderDetails as $key => $v_orderDetails){
            $list_id_product_order[$key] = $v_orderDetails->product_id;
        }
        $list_id_product_order = array_unique($list_id_product_order);
        $list_id_product_order_5 = array();
        foreach($list_id_product_order as $key => $v_list_id_product_order){
            $list_id_product_order_5[$key] = $v_list_id_product_order;
            if($key == 4){
                break;
            }
        }
        $best_sale_product = Product::where('product_status', 1)->where('flashsale_status', '0')->wherein('product_id', $list_id_product_order_5)->get();
        /* Kết Thúc Thuật Toán */

        /* Thuật Toán Sản Phẩm Đang Thịnh Hành - Dựa Vào Viewer */
        $viewer_product = Product::where('product_status', 1)->where('flashsale_status', '0')->wherenotin('product_id', $list_id_product_order_5)->orderby('product_viewer', 'DESC')->take(5)->get();
        $list_id_viewer_product = array();
        foreach($viewer_product as $key => $v_viewer_product){
            $list_id_viewer_product[$key] = $v_viewer_product->product_id;
        }
        /* Kết Thúc Thuật Toán */

        /* Thuật Toán Sản Phẩm Mới - Dựa Vào Product_id */
        $new_product = Product::where('product_status', 1)->where('flashsale_status', '0')->orderby('product_id', 'DESC')->wherenotin('product_id', $list_id_product_order_5)->wherenotin('product_id', $list_id_viewer_product)->take(5)->get();
        $list_id_new_product = array();
        foreach($new_product as $key => $v_new_product){
            $list_id_new_product[$key] = $v_new_product->product_id;
        }
        /* Lấy Sản Phẩm Còn Lại - Sắp Xếp Dựa Theo Giá*/
        $best_price_product= Product::where('product_status', 1)->where('flashsale_status', '0')->orderby('product_price', 'ASC')->wherenotin('product_id', $list_id_new_product)->wherenotin('product_id', $list_id_product_order_5)->wherenotin('product_id', $list_id_viewer_product)->take(8)->get();
        /* Lấy Random 2 Mã Giảm Giá*/
        $TimeNow = Carbon::now('Asia/Ho_Chi_Minh')->format('Y-m-d');
        $coupons = Coupon::inRandomOrder()->where('coupon_end_date', '>=', $TimeNow)->where('coupon_start_date', '<=', $TimeNow)->where('coupon_qty_code', '>', 0)->take(2)->get();
        ManipulationActivity::noteManipulationCustomer("Vào Trang Chủ");
        return view('pages.home.trangchu')->with(compact('best_price_product','flashsale','coupons','new_product','meta','best_sale_product','viewer_product'));
    }

































    public function print_danh_sach_san_pham($dataproduct)
    {
        $output = '';
        foreach ($dataproduct as $products) {
            $url = '/DoAnCNWeb/san-pham/san-pham-chi-tiet?product_id='.$products->product_id; 
            $output .=
            '
            <div class="col-lg-4 col-md-6 col-sm-6">
    <div class="flashsalehotel_boxcontent flashsalehotel_boxcontent_hover item">
   <div class="flashsalehotel_boxcontent_img_text">
        <a
            href="'.$url.'">
                <div class="flashsalehotel_img-box">
         <img class="flashsalehotel_img" width="300px" height="200px"
            style="object-fit: cover;"
            src="public/fontend/assets/img/product/' .
                    $products->product_image .
                    '"
            alt="">
        </div>
    
      <div class="flashsalehotel_text">
         <div class="flashsalehotel_text-title">
            ' .
                    $products->product_name .    '
         </div>
         </a>
         <div class="flashsalehotel_place">
            <div>
               <i class="fa-solid fa-certificate"></i>
               ' .
            $products->category->category_name .
            '
            </div>
         </div>
         <div class="flashsalehotel_text-evaluate">
            <div class="flashsalehotel_text-evaluate-icon">
               <i class="fa-solid fa-star"></i>8.5
            </div>
            <div class="flashsalehotel_text-evaluate-text">
               Tuyệt vời <span style=" color:#4a5568;">(425 đánh giá)</span>
            </div>
         </div>
         <div class="flashsalehotel_text-time">
            Trạng Thái Như Thế Nào ?
         </div>
         <div class="flashsalehotel_text-box-price">';
         if($products->flashsale){
            $output .= '
            <div class="product_price_sale">
                    <span>'.number_format($products->product_price, '0', ',', '.') . 'đ' .'</span>
            </div>
            ';
         }else{
            $output .='<br>';
         }
            $output .= '<div style="display: flex;">
               <div class="flashsalehotel_text-box-price-two">';
               if($products->flashsale){
                $output .='<span>' .
                number_format($products->flashsale->flashsale_product_price, '0', ',', '.') .'đ' .
                    '</span>';
               } else{
                $output .='<span>' .
                number_format($products->product_price, '0', ',', '.') .'đ' .
                    '</span>';
               }
               $output .='</div>
               <div class="flashsalehotel_text-box-price-one">
                  <span>/</span>
               </div>
               <div class="flashsalehotel_text-box-price-one">';
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
            $output .=
            '<span>' .
            $products->product_unit_sold .
                $product_unit .
                '</span>
               </div>
            </div>
            <div class="flashsalehotel_text-box-price-three bordernhay">
               <div style="margin-left: 8px;"
                  class="flashsalehotel_text-box-price-three-l chunhay">
                  <div class="cart-hover">
                     <i class="fa-solid fa-heart"></i>
                     <span style="font-size: 14px;">Yêu Thích</span>
                  </div>
               </div>
               <div class="flashsalehotel_text-box-price-three-r chunhay">
                  <div class="cart-hover">
                     <i class="fa-solid fa-cart-shopping"></i>
                     <span style="font-size: 14px;" data-product_id="'. $products->product_id.'" class="button_cart">Đặt Hàng</span>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
</div>
';
        }
        return $output;
    }


    public function danh_sach_san_pham(){
        $meta = array(
            'title' => 'Tìm Kiếm - Thế Giới Hải Sản',
            'description' => 'Thế Giới Hải Sản - Trang Tìm Kiếm Và Đặt Hải Sản Hàng Đầu Việt Nam',
            'keywords' => 'Hải Sản Đà Nẵng , Hải Sản Giá Rẻ , Hải Sản Tươi Sống , Hải Sản Giao Nhanh , Tôm Hùng , Cua , Ghẹ , .....',
            'canonical' => request()->url(),
            'sitename' => 'sepnguyenvanhanbro.thegioihaisan.laravel.vn',
            'image' => '',
        );
        $all_product = Product::paginate(6);
        // $product_price_min = Product::orderBy('product_price', 'ASC')->first();
        // $product_price_max = Product::orderBy('product_price', "DESC")->first();
        // dd($product_price_max);
        $min_price_product = Product::min('product_price');
        $price_min = substr( $min_price_product, 0, -3); /* Cắt chuỗi 3 số sau cùng */
        $max_price_product = Product::max('product_price');
        $price_max = substr( $max_price_product, 0, -3);  /* Cắt chuỗi 3 số sau cùng */
        // $price_max = $product_price_max->product_price;
        // $price_min = $product_price_min->product_price; 
        
        $dataCategory = Category::get();
        return view('pages.home.danhsachsanpham')->with(compact('all_product', 'dataCategory', 'price_max', 'price_min', 'meta'));
    }

    public function load_danh_sach_san_pham(){
        $all_product = Product::where('product_status', 1)->orderBy('product_id', 'ASC')->get();
        $output = $this->print_danh_sach_san_pham($all_product);
        echo $output;  
    }

    // public function print_danh_sach_san_pham($datas){

    //     $output = '';
    //     foreach($datas as $key => $data){
    //         $output .= '
    //         <div class="col-lg-4 col-md-6 col-sm-6">
    //             <div class="flashsalehotel_boxcontent item">
    //                 <div class="flashsalehotel_boxcontent_img_text">
    //                     <div class="flashsalehotel_img-box">
    //                         <a href="san-pham/san-pham-chi-tiet?product_id='.$data->product_id.'" class="flashsalehotel_boxcontent_hover">
    //                             <img class="flashsalehotel_img" width="284px" height="160px" style="object-fit: cover;"
    //                             src="public/fontend/assets/img/product/'.$data->product_image.'" alt="">
    //                         </a>
                   
    //                     </div>
    //                     <div class="flashsalehotel_text">
    //                         <div class="flashsalehotel_text-title">
    //                             '.$data->product_name.'
    //                         </div>
    //                         <div class="flashsalehotel_place">
    //                             <div>
    //                                 <i class="fa-solid fa-certificate"></i>
    //                                 '.$data->category->category_name.'
    //                             </div>
    //                         </div>
    //                         ';
    //                         if(isset($data->flashsale)){
    //                             $output .= '<div class="flashsalehotel_text-time">
    //                             Giảm giá
    //                         </div>';
    //                         }else{
    //                             $output .= '
    //                             <div class="flashsalehotel_text-time">
    //                                 Sản phẩm
    //                             </div>';
    //                         }
    //                         $output .= '
    //                         <div class="flashsalehotel_text-box-price">
                           
    //                             ';
    //                             if(!isset($data->flashsale)){
    //                                 $output .= '
    //                                 <div class="flashsalehotel_text-box-price-two">
    //                                 <span>'. number_format($data->product_price, 0,',','.').'đ</span>
    //                                 </div>';
    //                             }else{
    //                                 $output .= '
    //                                 <div style="display: flex; justify-content:right">
    //                                 <div class="flashsalehotel_text-box-price-two">
    //                                 <span>'. number_format($data->flashsale->flashsale_price_sale, 0,',','.').'đ</span>
    //                                 </div>
    //                                 <div class="flashsalehotel_text-box-price-one">
    //                                     <span>/</span>
    //                                 </div>
    //                                 <div class="flashsalehotel_text-box-price-one">
    //                                     <span style="text-decoration: line-through">'.number_format($data->product_price, 0,',','.') .'đ</span>
    //                                 </div>
    //                                 </div>
    //                                 ';
    //                             }
    //                             $output .= '
                                
    //                             <div class="flashsalehotel_text-box-price-three bordernhay">
    //                                 <div style="margin-left: 8px;"
    //                                     class="flashsalehotel_text-box-price-three-l chunhay">
    //                                     <div class="cart-hover">
    //                                         <i class="fa-solid fa-heart"></i>
    //                                         <span style="font-size: 14px;">Yêu Thích</span>
    //                                     </div>
    //                                 </div>
    //                                 <div class="flashsalehotel_text-box-price-three-r chunhay">
    //                                     <div class="cart-hover" data-toggle="modal" data-target="#shopping" data-product_id="'.$data->product_id.'">
    //                                         <i class="fa-solid fa-cart-shopping"></i>
    //                                         <span style="font-size: 14px;">Đặt Hàng</span>
    //                                     </div>
    //                                 </div>
    //                             </div>
    //                         </div>
    //                     </div>
    //                 </div>
    //             </div>
           
    //         </div>
    //         ';
    //     }
    //     echo $output;
    // }

    // filter
    public function search_san_pham(Request $request){
        // dd($request->list_id);
        $list_category = $request->list_id;
        $text = $request->text;
        $value_option = $request->value_option;
        $type_option = $request->type_option;
        $price_min = $request->price_min.'000';
        // $request->price_one."000";
        $price_max = $request->price_max.'000';
        if($request->list_id){
            $products = Product::where('product_price', '>=', $price_min)->whereIn('category_id', $list_category)->where('product_price', '<=', $price_max)->where('product_name', 'like', '%' . $text . '%')->orderBy($type_option, $value_option)->get();
        }else{
            $products = Product::where('product_price', '>=', $price_min)->where('product_price', '<=', $price_max)->where('product_name', 'like', '%' . $text . '%')->orderBy($type_option, $value_option)->get();
        }
        // dd($price_min + $price_max);
        $output = $this->print_danh_sach_san_pham($products);
        echo $output;
    }

}


