<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Product;
use App\Models\ManipulationActivity;
use Illuminate\Http\Request;
use Session;

session_start();

class CommentController extends Controller
{
    /* Back - End */
    public function all_comment()
    {
        $all_comment = Comment::orderby('comment_id', 'DESC')->Paginate(6);
        $check_comment = Comment::where('comment_status', '0')->get();
        $check_comment = $check_comment->count();
        ManipulationActivity::noteManipulationAdmin("Xem Danh Sách Bảng Bình Luận");
        $this->message("warning", "Có $check_comment Bình Luận Chờ Xét Duyệt ! ");
        return view('admin.Comment.all_comment')->with(compact('all_comment'));
    }
    public function loading_table_comment()
    {

        $output = '';
        $all_comment = Comment::orderby('comment_id', 'DESC')->Paginate(6);
        foreach ($all_comment as $key => $comments) {
            $output .= '
            <tr> ';
            if ($comments->comment_status == 0) {
                $output .= '
                <td> <button class="agree-comment btn-sm btn-gradient-info btn-fw" data-comment_id="' . $comments->comment_id . '" data-status="1" >Duyệt</button>
                    <button class="refuse-comment btn-sm btn-gradient-danger btn-fw" data-comment_id="' . $comments->comment_id . '" data-status="2" >Từ Chối</button>
                </td>';
            } else if ($comments->comment_status == 1) {
                $output .= '
                <td> <button class="btn-sm btn-gradient-success btn-fw un-permit" data-comment_id="' . $comments->comment_id . '" data-status="2">Đã Được Duyệt</button> </td>';
            } else if ($comments->comment_status == 2) {
                $output .= '
                <td> <button class="btn-sm btn-gradient-dark btn-fw un-permit" data-comment_id="' . $comments->comment_id . '" data-status="1">Bị Từ Chối</button> </td>';
            }
            $output .= '

            <td>' . $comments->comment_customer_name . '</td>
            <td>' . $comments->comment_title . '</td>
            <td>' . $comments->comment_content . '</td>
            <td>' . $comments->comment_date . '</td>
            <td><a href="' . URL('/san-pham/san-pham-chi-tiet?product_id=' . $comments->comment_product_id . '') . '" target="_blank">' . $comments->product->product_name . '</a></td>
            ';
            // if ($comments->comment_status == 1) {
            //     $output .= '
            // <td contenteditable>Nội dung cmt</td>';
            // }
            // if ($comments->comment_status == 0 || $comments->comment_status == 2) {
            //     $output .= '
            // <td>Nội dung cmt</td>';
            // }
            if ($comments->comment_status == 1) {
                $output .= '
            <td>
            <button class="btn-sm btn-gradient-success btn-fw btn-reply-cmt"  data-comment_id="' . $comments->comment_id . '"  data-comment_product_id="' . $comments->comment_product_id . '" data-bs-toggle="modal" data-bs-target="#exampleModal" >Trả Lời</button> ';
            }
            if ($comments->comment_status == 0 || $comments->comment_status == 2) {
                $output .= '
            <td>
            <button class="btn-sm btn-gradient-secondary btn-fw repply-disable">Trả Lời</button>';
            }
            $output .= '
            <button class="btn-sm btn-gradient-dark btn-fw"> <i class="mdi mdi-delete-sweep" data-delete_comment="' . $comments->comment_id . '"></i></button>
            </td>


        </tr>
        ';
        }
        echo $output;

    }

    public function set_status(Request $request)
    {
        $data = $request->all();
        $comment = Comment::where('comment_id', $data['comment_id'])->first();
        $comment->comment_status = $data['comment_status'];
        $comment->save();
        ManipulationActivity::noteManipulationAdmin("Duyệt Bình Luận ( ID : " . $data['comment_id'] . ")");
    }

    //delete comment
    public function delete_comment(Request $request)
    {
        $comment_id = $request->delete_id;
        $comment = Comment::find($comment_id);
        $comment->delete();
    }

    /* Font - End */
    public function load_comment(Request $request)
    {
        $comment_by_product = Comment::where('comment_product_id', $request->product_id)->where('comment_status', '1')->get();
        $output = '';

        foreach ($comment_by_product as $comment) {
            $result = (explode(' ', $comment->comment_customer_name));
            $result = end($result);
            /* Tên Khách Hàng Là Lê Khả Nhân Thì Lấy Ra Ký Tự N */

            $output .= '
            <div class="userswrite">
            <div class="userswrite-boxone">
            <div class="userswrite-boxone-imgusers">
                <div class="userswrite-boxone-imgusers-element">
                    <span>' . $result[0] . '</span>
                </div>
            </div>
            <div class="userswrite-boxone-infousers">
                <div class="userswrite-boxone-infousers-box">
                    <div class="userswrite-boxone-infousers-title">
                        <span>' . $comment->comment_customer_name . '</span>
                    </div>
                    <div class="userswrite-boxone-infousers-item">
                        <i class="fa-solid fa-pen"></i>
                        <span class="userswrite-boxone-infousers-item-text">' . $comment->comment_date . '</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="userswrite-boxtwo">
            <div class="userswrite-boxtwo-title">
                <span>' . $comment->comment_title . '</span>
            </div>
            <div class="userswrite-boxtwo-content">
                <span>' . $comment->comment_content . '</span>
            </div>
        </div>
        <div class="userswrite-boxthree">

        </div>
        </div>
        ';
        }

        echo $output;
    }

    public function insert_comment(Request $request)
    {
        $data = $request->all();
        $comment = new Comment();

        $comment->comment_title = $data['comment_title'];
        $comment->comment_content = $data['comment_content'];
        $comment->comment_customer_id = session()->get('customer_id');
        $comment->comment_customer_name = session()->get('customer_name');
        $comment->comment_product_id = $data['product_id'];
        $comment->save();

        $product = Product::find( $data['product_id']);
        ManipulationActivity::noteManipulationCustomer("Bình Luận Sản Phẩm  $product->product_name ");
    }

    public function message($type, $content)
    {
        $message = array(
            "type" => "$type",
            "content" => "$content",
        );
        Session::put('message', $message);
    }

}
