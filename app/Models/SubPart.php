<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubPart extends Model
{
    use HasFactory;
    protected $primaryKey = 'sub_part_id';
    protected $table = 'sub_part';
    protected $fillable = ['sub_part_no', 'part_id', 'part_no', 'act_id','appendix_id','schedule_id', 'chapter_id', 'parts_id','priliminary_id', 'sub_part_title', 'sub_part_content'];

    public function footnoteModel()
    {
        return $this->hasMany(Footnote::class, 'sub_part_id', 'sub_part_id');
    }
}
