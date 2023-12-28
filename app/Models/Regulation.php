<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Regulation extends Model
{
    use HasFactory;
    protected $primaryKey = 'regulation_id';
    protected $table = 'regulations';
    protected $fillable = ['regulation_no', 'act_id', 'maintype_id', 'chapter_id', 'subtypes_id', 'parts_id', 'regulation_title', 'regulation_content'];

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
        return $this->hasMany(Footnote::class, 'regulation_id', 'regulation_id');
    }
    public function subtype()
    {
        return $this->belongsTo(SubType::class, 'subtype_id');
    }
}
