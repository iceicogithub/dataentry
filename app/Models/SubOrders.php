<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubOrders extends Model
{
    use HasFactory;
    protected $primaryKey = 'sub_order_id';
    protected $table = 'sub_orders';
    protected $fillable = ['sub_order_no', 'order_id', 'order_no', 'act_id','appendix_id','schedule_id', 'chapter_id', 'parts_id','priliminary_id', 'sub_order_title', 'sub_order_content'];

    public function footnoteModel()
    {
        return $this->hasMany(Footnote::class, 'sub_order_id', 'sub_order_id');
    }
}
