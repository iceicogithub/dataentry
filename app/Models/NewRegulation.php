<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewRegulation extends Model
{
    protected $primaryKey = 'new_regulation_id';
    protected $table = 'new_regulation';
    protected $fillable = ['act_id','category_id', 'state_id', 'new_regulation_title', 'new_regulation_content','ministry','new_regulation_no','new_regulation_date','enactment_date','enforcement_date','new_regulation_description','new_regulation_footnote_title','new_regulation_footnote_description','act_summary_id'];

    public function actSummary(){
        return $this->belongsTo(ActSummary::class, 'act_summary_id','id');
    }


    public function regulationMain()
    {
        return $this->hasMany(RegulationMain::class, 'new_regulation_id', 'new_regulation_id');
    }

    

    public function act(){
        return $this->belongsTo(Act::class, 'act_id', 'act_id'); 
    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($newRegulation) {
            $newRegulation->regulationMain()->delete();
        });
    }
}