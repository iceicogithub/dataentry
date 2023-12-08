<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubSection extends Model
{
    use HasFactory;
    protected $primaryKey = 'sub_section_id';
    protected $table = 'sub_section';
    protected $fillable = ['section_id','section_no','act_id', 'chapter_id','parts_id','sub_section_title','sub_section_content'];
}
