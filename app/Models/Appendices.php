<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appendices extends Model
{
    use HasFactory;

    protected $primaryKey = 'appendices_id';
    protected $table = 'appendices';
    protected $fillable = ['appendices_no','appendices_rank' , 'act_id', 'maintype_id', 'chapter_id', 'subtypes_id', 'parts_id', 'appendices_title', 'appendices_content' ,'appendix_id','schedule_id','priliminary_id'];

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
    public function footnoteModel()
    {
        return $this->hasMany(Footnote::class, 'appendices_id', 'appendices_id');
    }

    public function subAppendicesModel()
    {
        return $this->hasMany(SubAppendices::class, 'appendices_id', 'appendices_id');
    }
    public function subtype()
    {
        return $this->belongsTo(SubType::class, 'subtype_id');
    }
}
