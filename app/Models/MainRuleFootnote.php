<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainRuleFootnote extends Model
{
    use HasFactory;

    protected $primaryKey = 'rule_footnote_id';
    protected $table = 'main_rule_footnote';
    protected $fillable = ['rules_id','rule_sub_id', 'new_rule_id', 'footnote_title', 'footnote_content'];
}
