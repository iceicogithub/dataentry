<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainTypeOrder extends Model
{
    use HasFactory;
    protected $primaryKey = 'order_maintype_id';
    protected $table = 'ordermaintypes';
    protected $fillable = ['type'];
}
