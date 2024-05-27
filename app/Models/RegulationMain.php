<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegulationMain extends Model
{
    use HasFactory;
    protected $primaryKey = 'regulation_main_id';
    protected $table = 'regulation_main_table';
    protected $fillable = ['regulation_main_rank', 'new_regulation_id', 'act_id', 'regulation_maintype_id', 'regulation_main_title'];

    public function mainTypeRegulation()
    {
        return $this->hasMany(MainTypeRegulation::class, 'regulation_maintype_id', 'regulation_maintype_id');
    }

    public function regulationtbl()
    {
        return $this->hasMany(RegulationTable::class, 'regulation_main_id', 'regulation_main_id');
    }

    public function ruleFootnoteModel()
    {
        return $this->belongsTo(MainRuleFootnote::class, 'rule_main_id', 'rule_main_id');
    }

    public function newRegulation()
    {
       return $this->belongsTo(NewRegulation::class, 'new_regulation_id', 'new_regulation_id');
    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($regulationMain) {
            $regulationMain->regulationtbl()->delete();
        });
    }

}
