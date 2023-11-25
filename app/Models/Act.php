<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Act extends Model
{
    use HasFactory;
    protected $table = 'acts';
    protected $fillable = ['category_id', 'state_id', 'act_title', 'act_content'];
}