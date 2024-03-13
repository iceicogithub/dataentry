<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stschedule extends Model
{
    use HasFactory;
    protected $primaryKey = 'stschedule_id';
    protected $table = 'stschedule';
    protected $fillable = ['stschedule_rank','stschedule_no','act_id','maintype_id','appendix_id','schedule_id', 'chapter_id', 'subtypes_id', 'parts_id','priliminary_id', 'stschedule_title', 'stschedule_content','is_append', 'serial_no'];

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

    public function subStscheduleModel()
    {
        return $this->hasMany(SubStschedule::class, 'stschedule_id', 'stschedule_id');
    }

    public function subtype()
    {
        return $this->belongsTo(SubType::class, 'subtype_id');
    }
    public function footnoteModel()
    {
        return $this->hasMany(Footnote::class, 'stschedule_id', 'stschedule_id');
    }

}
