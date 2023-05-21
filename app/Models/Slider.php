<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
   public $timestamps = false;
   protected $fillable = [
    'slider_name' ,  'slider_image' ,   'slider_status' ,   'slider_desc' /* Trường Trong Bảng */
   ]; 
   protected $primaryKey =  'slider_id'; /* Khóa Chính */
   protected $table =   'tbl_slider'; /* Tên Bảng */
}