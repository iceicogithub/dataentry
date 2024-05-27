<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubTypeRegulation extends Model
{
    protected $primaryKey = 'regulation_subtypes_id';
    protected $table = 'regulationsubtypes';
    protected $fillable = ['type'];
}
