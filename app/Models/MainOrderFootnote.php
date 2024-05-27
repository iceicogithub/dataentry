<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainOrderFootnote extends Model
{
    use HasFactory;
    protected $primaryKey = 'order_footnote_id';
    protected $table = 'main_order_footnote';
    protected $fillable = ['orders_id','order_sub_id', 'new_order_id', 'footnote_title', 'footnote_content'];
}
