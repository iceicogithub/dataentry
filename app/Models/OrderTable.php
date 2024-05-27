<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderTable extends Model
{
    use HasFactory;
    protected $primaryKey = 'orders_id';
    protected $table = 'order_table';
    protected $fillable = ['new_order_id','order_main_id', 'orders_rank', 'act_id', 'order_subtypes_id','orders_no','orders_title','orders_content'];
    

    public function subTypeOrder()
    {
        return $this->hasMany(SubTypeOrder::class, 'order_subtypes_id', 'order_subtypes_id');
    }

    public function mainOrder()
    {
       return $this->belongsTo(OrderMain::class, 'order_main_id', 'order_main_id');
    }

   public function orderFootnoteModel()
   {
       return $this->hasMany(MainOrderFootnote::class, 'orders_id', 'orders_id');
   }

   public function orderSub()
   {
       return $this->hasMany(OrderSub::class, 'orders_id', 'orders_id');
   }

   public function newOrder()
   {
      return $this->belongsTo(NewOrder::class, 'new_order_id', 'new_order_id');
   }
    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($ordertbl) {
            $ordertbl->orderSub()->delete();
        });
    }
}
