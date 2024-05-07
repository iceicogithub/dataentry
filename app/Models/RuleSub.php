<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RuleSub extends Model
{
    use HasFactory;
    protected $primaryKey = 'rule_sub_id';
    protected $table = 'rule_sub_table';
    protected $fillable = ['rule_main_id', 'rules_id', 'rule_sub_rank','new_rule_id', 'rule_subtypes_id','rule_sub_no','rule_sub_title','rule_sub_content'];

    public function ruleSubFootnoteModel()
    {
        return $this->hasMany(MainRuleFootnote::class, 'rule_sub_id', 'rule_sub_id');
    }

}
