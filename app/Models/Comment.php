<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public $timestamps = false;
    protected $fillable = [
    "order_code" ,'comment_title' ,  'comment_content' ,  'comment_customer_id' ,  'comment_customer_name','comment_product_id', 'comment_status', 'comment_reply', 'comment_date'
   ]; 
   protected $primaryKey =  'comment_id'; /* Khóa Chính */
   protected $table =   'tbl_comment'; /* Tên Bảng */

   public function product(){
    return $this->belongsTo('App\Models\Product','comment_product_id');
 }
}
    