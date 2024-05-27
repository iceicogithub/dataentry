<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderMain extends Model
{
    use HasFactory;
    protected $primaryKey = 'order_main_id';
    protected $table = 'order_main_table';
    protected $fillable = ['order_main_rank', 'new_order_id', 'act_id', 'order_maintype_id', 'order_main_title'];

    public function mainTypeOrder()
    {
        return $this->hasMany(MainTypeOrder::class, 'order_maintype_id', 'order_maintype_id');
    }

    public function ordertbl()
    {
        return $this->hasMany(OrderTable::class, 'order_main_id', 'order_main_id');
    }

    public function orderFootnoteModel()
    {
        return $this->belongsTo(MainOrderFootnote::class, 'order_main_id', 'order_main_id');
    }

    public function newOrder()
    {
       return $this->belongsTo(NewOrder::class, 'new_order_id', 'new_order_id');
    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($mainOrder) {
            $mainOrder->ordertbl()->delete();
        });
    }
}
