<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainOrder extends Model
{
    use HasFactory;
    protected $primaryKey = 'main_order_id';
    protected $table = 'main_order';
    protected $fillable = ['act_id','maintype_id', 'main_order_title'];

    public function MainOrderType()
    {
        return $this->belongsTo(MainType::class, 'maintype_id', 'maintype_id');
    }
}
