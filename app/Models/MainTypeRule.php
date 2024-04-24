<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainTypeRule extends Model
{
    use HasFactory;
    protected $primaryKey = 'rule_maintype_id';
    protected $table = 'rulemaintypes';
    protected $fillable = ['type'];
}
