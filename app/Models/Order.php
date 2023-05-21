<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'customer_id', 'shipping_id', 'payment_id', 'order_status', 'order_code', 'product_fee', 'product_coupon', 'product_price_coupon' ,'total_price', 'total_quantity', 'order_date'/* Trường Trong Bảng */
    ];
    protected $primaryKey = 'order_id'; /* Khóa Chính */
    protected $table = 'tbl_order'; /* Tên Bảng */
    public function payment()
    {
        return $this->belongsTo('App\Models\Payment', 'payment_id');
    }
    public function shipping()
    {
        return $this->belongsTo('App\Models\Shipping', 'shipping_id');
    }
    public function customer(){
        return $this->belongsTo('App\Models\Customers', "customer_id");
    }
}
