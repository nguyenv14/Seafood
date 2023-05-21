<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Customers;
use App\Models\Product;
use App\Models\ManipulationActivity;
use App\Models\Order;
use App\Models\OrderDetails;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Session;

session_start();

class APICommentController extends Controller
{

    // public function getCommentProduct(Request $request){
    //     $product_id = $request->product_id;
    //     $all_comments = Comment::where("comment_product_id", $product_id)->get();
    //     if($all_comments->count() > 0){
    //         foreach($all_comments as $key => $comment){
    //             $data[] = array(
    //                 "comment_id" => $comment->comment_id,
    //                 "comment_title" => $comment->comment_title,
    //                 "comment_content" => $comment->comment_content,
    //                 "comment_customer_id" => $comment->comment_customer_id,
    //                 "comment_customer_name" => $comment->comment_customer_name,
    //                 "comment_product_id" => $comment->comment_product_id,
    //                 "comment_rate_star" => $comment->comment_rate_star,
    //                 "comment_date" => $comment->comment_date
    //             );
    //         }
    //         return response()->json([
    //             "status_code" => 200,
    //             "message" => "ok",
    //             "data" => $data
    //         ]);
    //     }else{
    //         return response()->json([
    //             "status_code" => 404,
    //             "message" => "ok",
    //             "data" => null,
    //         ]);
    //     }
    // }

    public function getEvaluateProduct(Request $request){
        $Comments = Comment::where('comment_product_id',$request->product_id)->orderby("comment_id", "ASC")->get();
        return $this->fetchEvaluate($Comments);
    }

    public function getEvaluateOrderCode(Request $request){
        $order_code = $request->order_code;
        $comment = Comment::where("order_code", $order_code)->where("comment_status", 1)->take(1)->get();
        return $this->fetchEvaluate($comment);
    }

    public function fetchEvaluate($Comments){
        if( $Comments->count() > 0){
            foreach ($Comments as $value) {
                $data[] = array(
                    'comment_id' =>  $value->comment_id,
                    'order_code' => $value->order_code,
                    'comment_title' => $value->comment_title,
                    'comment_content' => $value->comment_content,
                    'comment_customer_id' => $value->comment_customer_id,
                    'comment_customer_name' => $value->comment_customer_name,
                    'comment_product_id' => $value->comment_product_id,
                    'comment_rate_star' => $value->comment_rate_star,
                    "comment_date" => $value->comment_date
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

    public function insertEvaluateToOrder(Request $request){
        $order_code = $request->order_code;
        $comment_title = $request->comment_title;
        $comment_content = $request->comment_content;
        $comment_star = $request->comment_star;
        $order_details = OrderDetails::where("order_code", $order_code)->get();
        $order = Order::where("order_code", $order_code)->first();
        
        foreach($order_details as $key => $value){
            $comment = new Comment();
            $comment->order_code = $order_code;
            $comment->comment_content = $comment_content;
            $comment->comment_title = $comment_title;
            $comment->comment_customer_id = $order->customer_id;
            $comment->comment_customer_name = $order->customer->customer_name;
            $comment->comment_product_id = $value->product_id;
            $comment->comment_rate_star = $comment_star;
            $comment->comment_status = 1;
            $comment->comment_date = Carbon::now('Asia/Ho_Chi_Minh')->format('Y-m-d'); 
            $comment->save();
        }
        $order->order_status = 4;
        $order->save();
        $comments = Comment::where("order_code", $order_code)->take(1)->get();
        return $this->fetchEvaluate($comments);
    }

    /* Back - End */
    // public function all_comment()
    // {
    //     $all_comment = Comment::orderby('comment_id', 'DESC')->Paginate(6);
    //     $check_comment = Comment::where('comment_status', '0')->get();
    //     $check_comment = $check_comment->count();
    //     ManipulationActivity::noteManipulationAdmin("Xem Danh Sách Bảng Bình Luận");
    //     $this->message("warning", "Có $check_comment Bình Luận Chờ Xét Duyệt ! ");
    //     return view('admin.Comment.all_comment')->with(compact('all_comment'));
    // }
    // public function loading_table_comment()
    // {

    //     $output = '';
    //     $all_comment = Comment::orderby('comment_id', 'DESC')->Paginate(6);
    //     foreach ($all_comment as $key => $comments) {
    //         $output .= '
    //         <tr> ';
    //         if ($comments->comment_status == 0) {
    //             $output .= '
    //             <td> <button class="agree-comment btn-sm btn-gradient-info btn-fw" data-comment_id="' . $comments->comment_id . '" data-status="1" >Duyệt</button>
    //                 <button class="refuse-comment btn-sm btn-gradient-danger btn-fw" data-comment_id="' . $comments->comment_id . '" data-status="2" >Từ Chối</button>
    //             </td>';
    //         } else if ($comments->comment_status == 1) {
    //             $output .= '
    //             <td> <button class="btn-sm btn-gradient-success btn-fw un-permit" data-comment_id="' . $comments->comment_id . '" data-status="2">Đã Được Duyệt</button> </td>';
    //         } else if ($comments->comment_status == 2) {
    //             $output .= '
    //             <td> <button class="btn-sm btn-gradient-dark btn-fw un-permit" data-comment_id="' . $comments->comment_id . '" data-status="1">Bị Từ Chối</button> </td>';
    //         }
    //         $output .= '

    //         <td>' . $comments->comment_customer_name . '</td>
    //         <td>' . $comments->comment_title . '</td>
    //         <td>' . $comments->comment_content . '</td>
    //         <td>' . $comments->comment_date . '</td>
    //         <td><a href="' . URL('/san-pham/san-pham-chi-tiet?product_id=' . $comments->comment_product_id . '') . '" target="_blank">' . $comments->product->product_name . '</a></td>
    //         ';
    //         // if ($comments->comment_status == 1) {
    //         //     $output .= '
    //         // <td contenteditable>Nội dung cmt</td>';
    //         // }
    //         // if ($comments->comment_status == 0 || $comments->comment_status == 2) {
    //         //     $output .= '
    //         // <td>Nội dung cmt</td>';
    //         // }
    //         if ($comments->comment_status == 1) {
    //             $output .= '
    //         <td>
    //         <button class="btn-sm btn-gradient-success btn-fw btn-reply-cmt"  data-comment_id="' . $comments->comment_id . '"  data-comment_product_id="' . $comments->comment_product_id . '" data-bs-toggle="modal" data-bs-target="#exampleModal" >Trả Lời</button> ';
    //         }
    //         if ($comments->comment_status == 0 || $comments->comment_status == 2) {
    //             $output .= '
    //         <td>
    //         <button class="btn-sm btn-gradient-secondary btn-fw repply-disable">Trả Lời</button>';
    //         }
    //         $output .= '
    //         <button class="btn-sm btn-gradient-dark btn-fw"> <i class="mdi mdi-delete-sweep" data-delete_comment="' . $comments->comment_id . '"></i></button>
    //         </td>


    //     </tr>
    //     ';
    //     }
    //     echo $output;

    // }

    // public function set_status(Request $request)
    // {
    //     $data = $request->all();
    //     $comment = Comment::where('comment_id', $data['comment_id'])->first();
    //     $comment->comment_status = $data['comment_status'];
    //     $comment->save();
    //     ManipulationActivity::noteManipulationAdmin("Duyệt Bình Luận ( ID : " . $data['comment_id'] . ")");
    // }

    //delete comment
    // public function delete_comment(Request $request)
    // {
    //     $comment_id = $request->delete_id;
    //     $comment = Comment::find($comment_id);
    //     $comment->delete();
    // }

    /* Font - End */
    // public function load_comment(Request $request)
    // {
    //     $comment_by_product = Comment::where('comment_product_id', $request->product_id)->where('comment_status', '1')->get();
    //     $output = '';

    //     foreach ($comment_by_product as $comment) {
    //         $result = (explode(' ', $comment->comment_customer_name));
    //         $result = end($result);
    //         /* Tên Khách Hàng Là Lê Khả Nhân Thì Lấy Ra Ký Tự N */

    //         $output .= '
    //         <div class="userswrite">
    //         <div class="userswrite-boxone">
    //         <div class="userswrite-boxone-imgusers">
    //             <div class="userswrite-boxone-imgusers-element">
    //                 <span>' . $result[0] . '</span>
    //             </div>
    //         </div>
    //         <div class="userswrite-boxone-infousers">
    //             <div class="userswrite-boxone-infousers-box">
    //                 <div class="userswrite-boxone-infousers-title">
    //                     <span>' . $comment->comment_customer_name . '</span>
    //                 </div>
    //                 <div class="userswrite-boxone-infousers-item">
    //                     <i class="fa-solid fa-pen"></i>
    //                     <span class="userswrite-boxone-infousers-item-text">' . $comment->comment_date . '</span>
    //                 </div>
    //             </div>
    //         </div>
    //     </div>
    //     <div class="userswrite-boxtwo">
    //         <div class="userswrite-boxtwo-title">
    //             <span>' . $comment->comment_title . '</span>
    //         </div>
    //         <div class="userswrite-boxtwo-content">
    //             <span>' . $comment->comment_content . '</span>
    //         </div>
    //     </div>
    //     <div class="userswrite-boxthree">

    //     </div>
    //     </div>
    //     ';
    //     }

    //     echo $output;
    // }

    // public function insert_comment(Request $request)
    // {
    //     $data = $request->all();
    //     $comment = new Comment();

    //     $comment->comment_title = $data['comment_title'];
    //     $comment->comment_content = $data['comment_content'];
    //     $comment->comment_customer_id = session()->get('customer_id');
    //     $comment->comment_customer_name = session()->get('customer_name');
    //     $comment->comment_product_id = $data['product_id'];
    //     $comment->save();

    //     $product = Product::find( $data['product_id']);
    //     ManipulationActivity::noteManipulationCustomer("Bình Luận Sản Phẩm  $product->product_name ");
    // }

    public function message($type, $content)
    {
        $message = array(
            "type" => "$type",
            "content" => "$content",
        );
        session()->put('message', $message);
    }

}
