<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryProduct extends Model
{
   public $timestamps = false;
   protected $fillable = [
    'product_id' ,  'gallery_product_name' ,   'gallery_product_image' , 'gallery_product_content' ,/* Trường Trong Bảng */
   ]; 
   protected $primaryKey =  'gallery_product_id'; /* Khóa Chính */
   protected $table =   'tbl_gallery_product'; /* Tên Bảng */

   public function listGalleryProducbyId($product_id){
    $result = GalleryProduct::where('product_id',$product_id)->get();
    return $result;
   }

   public function listGallerybyId($gallery_id){
      $result = GalleryProduct::where('gallery_product_id',$gallery_id)->first();
      return $result;
     }
}