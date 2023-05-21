<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductDetails extends Model
{
   use SoftDeletes;

   public $timestamps = false;
   protected $dates = [
      'deleted_at' ,
     ]; 
   protected $fillable = [
    'product_id' ,  'product_details_content' ,   'product_details_quantity' ,   'product_details_deliveryway' ,
    'product_details_origin' ,   'product_details_delicious_foods' , /* Trường Trong Bảng */
   ]; 
   protected $primaryKey =  'product_details_id'; /* Khóa Chính */
   protected $table =   'tbl_product_details'; /* Tên Bảng */

   public function product(){
      return $this->belongsTo('App\Models\Product', 'product_id');
  }
   public function find_product_details_byId($product_id){
        $result = ProductDetails::where('product_id',$product_id)->first();
        return $result;
   }
}