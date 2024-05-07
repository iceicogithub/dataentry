<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RuleMain extends Model
{
    use HasFactory;
    protected $primaryKey = 'rule_main_id';
    protected $table = 'rule_main_table';
    protected $fillable = ['rule_main_rank', 'new_rule_id', 'act_id', 'rule_maintype_id', 'rule_main_title'];

    public function mainTypeRule()
    {
        return $this->hasMany(MainTypeRule::class, 'rule_maintype_id', 'rule_maintype_id');
    }

    public function ruletbl()
    {
        return $this->hasMany(RuleTable::class, 'rule_main_id', 'rule_main_id');
    }

    public function ruleFootnoteModel()
    {
        return $this->belongsTo(MainRuleFootnote::class, 'rule_main_id', 'rule_main_id');
    }

    public function newRule()
    {
       return $this->belongsTo(NewRule::class, 'new_rule_id', 'new_rule_id');
    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($ruleMain) {
            $ruleMain->ruletbl()->delete();
        });
    }

}
