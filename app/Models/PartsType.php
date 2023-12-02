<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartsType extends Model
{
    use HasFactory;
    protected $primaryKey = 'partstype_id';
    protected $table = 'partstype';
    protected $fillable = ['parts'];
}
