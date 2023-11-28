<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartsType extends Model
{
    use HasFactory;
    protected $table = 'partstype';
    protected $fillable = ['parts'];
}
