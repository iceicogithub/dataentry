<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherMainAct extends Model
{
    use HasFactory;
    protected $primaryKey = 'other_act_id';
    protected $table = 'other_main_act';
    protected $fillable = ['act_id','introduction', 'effective_date', 'object_reasons', 'legislative_history','financial_implication'];
    
    public function acts()
    {
        return $this->belongsTo(Act::class, 'act_id', 'act_id');
    }
}
