<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'section_id';
    protected $table = 'section';
    protected $fillable = ['section_no','act_id','maintype_id', 'chapter_id', 'subtypes_id', 'parts_id', 'section_title', 'section_content'];

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

    public function subsectionModel()
    {
        return $this->hasMany(SubSection::class, 'section_id', 'section_id');
    }

    public function footnoteModel()
    {
        return $this->hasMany(Footnote::class, 'section_id', 'section_id');
    }
    
}
