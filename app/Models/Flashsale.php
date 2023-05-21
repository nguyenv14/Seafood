<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Flashsale extends Model
{
    use SoftDeletes;

    protected $dates = [
       'deleted_at' ,
      ]; 

    public $timestamps = false;
    protected $fillable = [
        'product_id', 'flashsale_condition', 'flashsale_price_sale', 'flashsale_product_price', 'flashsale_status', /* Trường Trong Bảng */
    ];
    protected $primaryKey = 'flashsale_id'; /* Khóa Chính */
    protected $table = 'tbl_flashsale'; /* Tên Bảng */

    public function product(){
        return $this->belongsTo('App\Models\Product', 'product_id');
    }

    public function all_product_flashsale(){
        $allFashsale = Flashsale::get();
        return $allFashsale;
    }

    public function find_product_flashsale_byID($flashsale_id){
        $allFashsale = Flashsale::where('flashsale_id',$flashsale_id)->first();
        return $allFashsale;
    }
    public function find_product_flashsale_byProductID($product_id){
        $result = Flashsale::where('product_id',$product_id)->first();
        return $result;
    }


  
}
