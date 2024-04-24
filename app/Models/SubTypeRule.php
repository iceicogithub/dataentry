<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubTypeRule extends Model
{
    use HasFactory;
    protected $primaryKey = 'rule_subtypes_id';
    protected $table = 'rulesubtypes';
    protected $fillable = ['type'];
}
