<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    use HasFactory;
    protected $primaryKey = 'chapter_id';
    protected $table = 'chapter';
    protected $fillable = ['act_id','maintype_id', 'chapter_title'];

    public function ChapterType()
    {
        return $this->belongsTo(MainType::class, 'maintype_id', 'maintype_id');
    }

    public function Sections()
    {
        return $this->hasMany(Section::class, 'chapter_id', 'chapter_id');
    }

    public function Articles()
    {
        return $this->hasMany(Article::class,'chapter_id', 'chapter_id');
    }

    public function Rules()
    {
        return $this->hasMany(Rules::class,'chapter_id', 'chapter_id');
    }

    public function Regulation()
    {
        return $this->hasMany(Regulation::class, 'chapter_id', 'chapter_id');
    }

    public function Lists()
    {
        return $this->hasMany(Lists::class,'chapter_id', 'chapter_id');
    }

    public function Part()
    {
        return $this->hasMany(Part::class,'chapter_id', 'chapter_id');
    }
    public function Appendices()
    {
        return $this->hasMany(Appendices::class, 'chapter_id', 'chapter_id');
    }

    public function Order()
    {
        return $this->hasMany(Orders::class,'chapter_id', 'chapter_id');
    }

    public function Annexure()
    {
        return $this->hasMany(Annexure::class,'chapter_id', 'chapter_id');
    }
    public function Stschedule()
    {
        return $this->hasMany(Stschedule::class,'chapter_id', 'chapter_id');
    }
}
