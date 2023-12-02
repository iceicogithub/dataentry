<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainType extends Model
{
    use HasFactory;
    protected $primaryKey = 'maintype_id';
    protected $table = 'maintypes';
    protected $fillable = ['type'];
}
