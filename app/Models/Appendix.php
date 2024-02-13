<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appendix extends Model
{
    use HasFactory;

    protected $primaryKey = 'appendix_id';
    protected $table = 'appendix';
    protected $fillable = ['appendix_no','appendix_rank' , 'act_id', 'maintype_id', 'chapter_id', 'subtypes_id', 'parts_id', 'appendix_title', 'appendix_content' ,'appendices_id','schedule_id','priliminary_id'];

    public function MainTypeModel()
    {
        return $this->belongsTo(MainType::class, 'maintype_id', 'maintype_id');
    }

    public function Partmodel()
    {
        return $this->belongsTo(Parts::class, 'parts_id', 'parts_id');
    }

    public function ChapterModel()
    {
        return $this->belongsTo(Chapter::class, 'chapter_id', 'chapter_id');
    }
    public function footnoteModel()
    {
        return $this->hasMany(Footnote::class, 'appendix_id', 'appendix_id');
    }
    public function subtype()
    {
        return $this->belongsTo(SubType::class, 'subtype_id');
    }
}
