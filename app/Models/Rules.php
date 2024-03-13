<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rules extends Model
{
    use HasFactory;
    protected $primaryKey = 'rule_id';
    protected $table = 'rules';
    protected $fillable = ['rule_no', 'rule_rank', 'act_id', 'maintype_id', 'chapter_id', 'subtypes_id', 'parts_id','schedule_id', 'rule_title', 'rule_content','appendix_id','priliminary_id','is_append','serial_no'];

    public function MainTypeModel()
    {
        return $this->belongsTo(MainType::class, 'maintype_id', 'maintype_id');
    }

    public function Schedulemodel()
    {
        return $this->belongsTo(Schedule::class, 'schedule_id', 'schedule_id');
    }
    public function Appendixmodel()
    {
        return $this->belongsTo(Appendix::class, 'appendix_id', 'appendix_id');
    }
    public function Partmodel()
    {
        return $this->belongsTo(Parts::class, 'parts_id', 'parts_id');
    }

    public function ChapterModel()
    {
        return $this->belongsTo(Chapter::class, 'chapter_id', 'chapter_id');
    }
    public function PriliminaryModel()
    {
        return $this->belongsTo(Priliminary::class, 'priliminary_id', 'priliminary_id');
    }
    public function subruleModel()
    {
        return $this->hasMany(SubRules::class, 'rule_id', 'rule_id');
    }

    public function footnoteModel()
    {
        return $this->hasMany(Footnote::class, 'rule_id', 'rule_id');
    }
    public function subtype()
    {
        return $this->belongsTo(SubType::class, 'subtype_id');
    }
}
