<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubType extends Model
{
    use HasFactory;
    protected $primaryKey = 'subtypes_id';
    protected $table = 'subtypes';
    protected $fillable = ['type'];
}
