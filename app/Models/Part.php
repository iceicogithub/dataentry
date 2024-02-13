<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    use HasFactory;
    protected $primaryKey = 'part_id';
    protected $table = 'part';
    protected $fillable = ['part_no','part_rank' , 'act_id', 'maintype_id', 'chapter_id', 'subtypes_id', 'parts_id', 'part_title', 'part_content' ,'appendices_id','schedule_id','priliminary_id'];

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
        return $this->hasMany(Footnote::class, 'part_id', 'part_id');
    }
    public function subtype()
    {
        return $this->belongsTo(SubType::class, 'subtype_id');
    }

}





