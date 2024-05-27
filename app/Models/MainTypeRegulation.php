<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainTypeRegulation extends Model
{
    use HasFactory;
    protected $primaryKey = 'regulation_maintype_id';
    protected $table = 'regulationmaintypes';
    protected $fillable = ['type'];
}
