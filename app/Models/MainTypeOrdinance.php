<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainTypeOrdinance extends Model
{
    use HasFactory;
    protected $primaryKey = 'ordinance_maintype_id';
    protected $table = 'ordinancemaintypes';
    protected $fillable = ['type'];
}
