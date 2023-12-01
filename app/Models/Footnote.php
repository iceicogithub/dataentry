<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Footnote extends Model
{
    use HasFactory;
    protected $primaryKey = 'footnote_id';
    protected $table = 'footnote';
    protected $fillable = ['section_id','act_id', 'chapter_id','footnote_title','footnote_content'];
}
