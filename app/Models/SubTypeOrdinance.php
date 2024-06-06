<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubTypeOrdinance extends Model
{
    use HasFactory;
    protected $primaryKey = 'ordinance_subtypes_id';
    protected $table = 'ordinancesubtypes';
    protected $fillable = ['type'];
}
