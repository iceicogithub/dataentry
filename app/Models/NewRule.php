<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewRule extends Model
{
    protected $primaryKey = 'new_rule_id';
    protected $table = 'new_rule';
    protected $fillable = ['act_id','category_id', 'state_id', 'new_rule_title', 'new_rule_content','ministry','new_rule_no','new_rule_date','enactment_date','enforcement_date','new_rule_description','new_rule_footnote_title','new_rule_footnote_description','new_rule_summary'];


    public function ruleMain()
    {
        return $this->hasMany(RuleMain::class, 'new_rule_id', 'new_rule_id');
    }

    // public function ruletbl()
    // {
    //     return $this->hasManyThrough(RuleTable::class, RuleMain::class, 'new_rule_id', 'rule_main_id');
    // }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($newRule) {
            $newRule->ruleMain()->delete();
        });
    }
}
