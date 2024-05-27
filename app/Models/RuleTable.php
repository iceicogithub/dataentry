<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RuleTable extends Model
{
    use HasFactory;
    protected $primaryKey = 'rules_id';
    protected $table = 'rule_table';
    protected $fillable = ['new_rule_id','rule_main_id', 'rules_rank', 'act_id', 'rule_subtypes_id','rules_no','rules_title','rules_content'];
    protected $casts = [
        'rules_rank' => 'float',
    ];

    public function subTypeRule()
    {
        return $this->hasMany(SubTypeRule::class, 'rule_subtypes_id', 'rule_subtypes_id');
    }

    public function mainRule()
    {
       return $this->belongsTo(RuleMain::class, 'rule_main_id', 'rule_main_id');
    }

   public function ruleFootnoteModel()
   {
       return $this->hasMany(MainRuleFootnote::class, 'rules_id', 'rules_id');
   }

   public function ruleSub()
   {
       return $this->hasMany(RuleSub::class, 'rules_id', 'rules_id');
   }
   protected static function boot()
    {
        parent::boot();
        static::deleting(function ($ruletbl) {
            $ruletbl->ruleSub()->delete();
        });
    }

}
