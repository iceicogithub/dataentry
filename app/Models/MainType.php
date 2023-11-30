<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainType extends Model
{
    use HasFactory;
    protected $table = 'maintypes';
    protected $fillable = ['type'];
}
