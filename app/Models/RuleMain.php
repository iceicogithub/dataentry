<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RuleMain extends Model
{
    use HasFactory;
    protected $primaryKey = 'rule_main_id';
    protected $table = 'rule_main_table';
    protected $fillable = ['rule_main_rank', 'act_id', 'rule_maintype_id', 'rule_main_title'];

}
