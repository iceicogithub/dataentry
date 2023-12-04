<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActSummary extends Model
{
    use HasFactory;
    protected $table = 'act_summary';
    protected $fillable = ['title'];
}
