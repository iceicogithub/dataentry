<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Footnote extends Model
{
    use HasFactory;
    protected $primaryKey = 'footnote_id';
    protected $table = 'footnote';
    protected $fillable = ['footnote_no','section_id','section_no','sub_section_id','regulation_id','regulation_no','rule_id','rule_no','act_id', 'chapter_id','parts_id','priliminary_id','footnote_title','footnote_content'];
}
