<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubAppendix extends Model
{
    use HasFactory;
    protected $primaryKey = 'sub_appendix_id';
    protected $table = 'sub_appendixs';
    protected $fillable = ['sub_appendix_no', 'appendix_id', 'appendix_no', 'act_id','appendices_id','schedule_id', 'chapter_id', 'parts_id','priliminary_id', 'sub_appendix_title', 'sub_appendix_content'];

    public function footnoteModel()
    {
        return $this->hasMany(Footnote::class, 'sub_appendix_id', 'sub_appendix_id');
    }
}
