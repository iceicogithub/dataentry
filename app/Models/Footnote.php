<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Footnote extends Model
{
    use HasFactory;
    protected $primaryKey = 'footnote_id';
    protected $table = 'footnote';
    protected $fillable = ['footnote_no','section_id','section_no','sub_section_id','regulation_id','regulation_no','sub_regulation_id','rule_id','rule_no','sub_rule_id','article_id' ,'article_no','sub_article_id' , 'list_id','list_no','sub_list_id','part_id','part_no','sub_part_id','order_id','order_no','sub_order_id' ,'annexture_id' ,'annexture_no','sub_annexture_id' ,'appendix_id' ,'appendix_no','sub_appendix_id','stschedule_id' ,'stschedule_no','sub_stschedule_id','act_id', 'chapter_id','parts_id','priliminary_id', 'schedule_id','appendices_id' ,'footnote_title','footnote_content'];
}
