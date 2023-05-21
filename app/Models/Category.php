<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public $timestamps = false;
    protected $fillable = [
    'category_name' ,  'category_desc' , 'category_image' ,  'category_status' , /* Trường Trong Bảng */
   ]; 
   protected $primaryKey =  'category_id'; /* Khóa Chính */
   protected $table =   'tbl_category'; /* Tên Bảng */
}