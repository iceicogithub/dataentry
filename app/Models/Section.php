<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'section_id';
    protected $table = 'section';
    protected $fillable = ['section_rank','section_no','act_id','maintype_id', 'chapter_id', 'subtypes_id', 'parts_id','priliminary_id','main_order_id', 'section_title', 'section_content','appendix_id','schedule_id','is_append','serial_no'];

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
    public function MainOrderModel()
    {
        return $this->belongsTo(MainOrder::class, 'main_order_id', 'main_order_id');
    }
    public function PriliminaryModel()
    {
        return $this->belongsTo(Priliminary::class, 'priliminary_id', 'priliminary_id');
    }

    public function subsectionModel()
    {
        return $this->hasMany(SubSection::class, 'section_id', 'section_id');
    }

    public function footnoteModel()
    {
        return $this->hasMany(Footnote::class, 'section_id', 'section_id');
    }
    public function subtype()
    {
        return $this->belongsTo(SubType::class, 'subtype_id');
    }
    
}
