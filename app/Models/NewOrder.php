<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewOrder extends Model
{
    use HasFactory;
    protected $primaryKey = 'new_order_id';
    protected $table = 'new_order';
    protected $fillable = ['act_id','category_id', 'state_id', 'new_order_title', 'new_order_content','ministry','new_order_no','new_order_date','enactment_date','enforcement_date','new_order_description','new_order_footnote_title','new_order_footnote_description','new_order_summary'];


    public function orderMain()
    {
        return $this->hasMany(OrderMain::class, 'new_order_id', 'new_order_id');
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
        static::deleting(function ($neworder) {
            $neworder->orderMain()->delete();
        });
    }
}
