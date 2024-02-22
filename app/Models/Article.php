<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;
    protected $primaryKey = 'article_id';
    protected $table = 'articles';
    protected $fillable = ['article_rank','article_no','act_id','maintype_id','appendices_id','schedule_id', 'chapter_id', 'subtypes_id', 'parts_id','priliminary_id', 'article_title', 'article_content'];

    public function MainTypeModel()
    {
        return $this->belongsTo(MainType::class, 'maintype_id', 'maintype_id');
    }

    public function Schedulemodel()
    {
        return $this->belongsTo(Schedule::class, 'schedule_id', 'schedule_id');
    }

    public function Appendicesmodel()
    {
        return $this->belongsTo(Appendices::class, 'appendices_id', 'appendices_id');
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

    public function subArticleModel()
    {
        return $this->hasMany(SubArticle::class, 'article_id', 'article_id');
    }

    public function subtype()
    {
        return $this->belongsTo(SubType::class, 'subtype_id');
    }
    public function footnoteModel()
    {
        return $this->hasMany(Footnote::class, 'article_id', 'article_id');
    }

}
