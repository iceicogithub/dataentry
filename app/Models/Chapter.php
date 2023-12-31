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
}
