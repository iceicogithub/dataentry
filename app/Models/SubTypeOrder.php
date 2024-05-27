<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubTypeOrder extends Model
{
    use HasFactory;
    protected $primaryKey = 'order_subtypes_id';
    protected $table = 'ordersubtypes';
    protected $fillable = ['type'];
}
