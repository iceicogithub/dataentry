<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewOrdinance extends Model
{
    use HasFactory;
    protected $primaryKey = 'new_ordinance_id';
    protected $table = 'new_ordinance';
    protected $fillable = ['act_id','category_id', 'state_id', 'new_ordinance_title', 'new_ordinance_content','ministry','new_ordinance_no','new_ordinance_date','enactment_date','enforcement_date','new_ordinance_description','new_ordinance_footnote_title','new_ordinance_footnote_description','act_summary_id'];


    public function actSummary(){
        return $this->belongsTo(ActSummary::class, 'act_summary_id','id');
    }
    public function ordinanceMain()
    {
        return $this->hasMany(OrdinanceMain::class, 'new_ordinance_id', 'new_ordinance_id');
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
        static::deleting(function ($newOrdinance) {
            $newOrdinance->ordinanceMain()->delete();
        });
    }

}
