<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubStschedule extends Model
{
    use HasFactory;
    protected $primaryKey = 'sub_stschedule_id';
    protected $table = 'sub_stschedules';
    protected $fillable = ['sub_stschedule_no', 'stschedule_id', 'stschedule_no', 'act_id','appendix_id','schedule_id', 'chapter_id', 'parts_id','priliminary_id', 'sub_stschedule_title', 'sub_stschedule_content'];

    public function footnoteModel()
    {
        return $this->hasMany(Footnote::class, 'sub_stschedule_id', 'sub_stschedule_id');
    }
}
