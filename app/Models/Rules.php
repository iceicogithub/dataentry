<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rules extends Model
{
    use HasFactory;
    protected $primaryKey = 'rule_id';
    protected $table = 'rules';
    protected $fillable = ['rule_no', 'rule_rank', 'act_id', 'maintype_id', 'chapter_id', 'subtypes_id', 'parts_id','schedule_id', 'rule_title', 'rule_content','appendices_id','priliminary_id'];

    public function MainTypeModel()
    {
        return $this->belongsTo(MainType::class, 'maintype_id', 'maintype_id');
    }

    public function Schedulemodel()
    {
        return $this->belongsTo(Schedule::class, 'schedule_id', 'schedule_id');
    }

    public function subruleModel()
    {
        return $this->hasMany(SubRules::class, 'rule_id', 'rule_id');
    }

    public function footnoteModel()
    {
        return $this->hasMany(Footnote::class, 'rule_id', 'rule_id');
    }
}
