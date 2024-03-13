<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Annexure extends Model
{
    use HasFactory;

    protected $primaryKey = 'annexure_id';
    protected $table = 'annexure';
    protected $fillable = ['annexure_no', 'annexure_rank' , 'act_id', 'maintype_id', 'chapter_id', 'subtypes_id', 'parts_id', 'annexure_title', 'annexure_content' ,'appendix_id','schedule_id','priliminary_id','main_order_id','is_append', 'serial_no'];

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
    public function MainOrderModel()
    {
        return $this->belongsTo(MainOrder::class, 'main_order_id', 'main_order_id');
    }
    public function footnoteModel()
    {
        return $this->hasMany(Footnote::class, 'annexure_id', 'annexure_id');
    }

    public function subAnnexureModel()
    {
        return $this->hasMany(SubAnnexure::class, 'annexure_id', 'annexure_id');
    }
    public function subtype()
    {
        return $this->belongsTo(SubType::class, 'subtype_id');
    }
}
