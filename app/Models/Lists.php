<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lists extends Model
{
    use HasFactory;

    protected $primaryKey = 'list_id';
    protected $table = 'lists';
    protected $fillable = ['list_no', 'list_rank' ,'act_id', 'maintype_id', 'chapter_id', 'subtypes_id', 'parts_id', 'list_title', 'list_content','appendix_id','schedule_id','priliminary_id','is_append', 'serial_no'];

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
        return $this->hasMany(Footnote::class, 'list_id', 'list_id');
    }
    public function subtype()
    {
        return $this->belongsTo(SubType::class, 'subtype_id');
    }
    public function subListModel()
    {
        return $this->hasMany(SubLists::class, 'list_id', 'list_id');
    }
}
