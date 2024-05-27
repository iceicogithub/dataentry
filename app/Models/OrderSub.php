<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderSub extends Model
{
    use HasFactory;
    protected $primaryKey = 'order_sub_id';
    protected $table = 'order_sub_table';
    protected $fillable = ['order_main_id', 'orders_id', 'order_sub_rank','new_order_id', 'order_subtypes_id','order_sub_no','order_sub_title','order_sub_content'];

    public function orderSubFootnoteModel()
    {
        return $this->hasMany(MainOrderFootnote::class, 'order_sub_id', 'order_sub_id');
    }
}
