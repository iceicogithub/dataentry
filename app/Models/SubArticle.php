<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubArticle extends Model
{
    use HasFactory;
    protected $primaryKey = 'sub_article_id';
    protected $table = 'sub_articles';
    protected $fillable = ['sub_article_no', 'article_id', 'article_no', 'act_id','appendices_id','schedule_id', 'chapter_id', 'parts_id','priliminary_id', 'sub_article_title', 'sub_article_content'];

    public function footnoteModel()
    {
        return $this->hasMany(Footnote::class, 'sub_article_id', 'sub_article_id');
    }
}
