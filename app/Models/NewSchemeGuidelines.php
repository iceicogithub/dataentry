<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewSchemeGuidelines extends Model
{
    use HasFactory;
    protected $primaryKey = 'new_scheme_guidelines_id';
    protected $table = 'new_scheme_guidelines';
    protected $fillable = ['act_id','category_id', 'state_id', 'new_scheme_guidelines_title', 'new_scheme_guidelines_content','ministry','new_scheme_guidelines_no','new_scheme_guidelines_date','enactment_date','enforcement_date','new_scheme_guidelines_description','newscheme_guidelines_footnote_title','new_scheme_guidelines_footnote_description','new_scheme_guidelines_summary'];


    public function schemeGuidelinesMain()
    {
        return $this->hasMany(SchemeGuidelinesMain::class, 'new_scheme_guidelines_id', 'new_scheme_guidelines_id');
    }

    public function act(){
        return $this->belongsTo(Act::class, 'act_id', 'act_id'); 
    }

    // public function ruletbl()
    // {
    //     return $this->hasManyThrough(RuleTable::class, RuleMain::class, 'new_rule_id', 'rule_main_id');
    // }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($newschemeGuidelines) {
            $newschemeGuidelines->schemeGuidelinesMain()->delete();
        });
    }
}
