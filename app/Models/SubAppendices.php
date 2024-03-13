<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubAppendices extends Model
{
    use HasFactory;
    protected $primaryKey = 'sub_appendices_id';
    protected $table = 'sub_appendices';
    protected $fillable = ['sub_appendices_no', 'appendices_id', 'appendices_no', 'act_id','appendix_id','schedule_id', 'chapter_id', 'parts_id','priliminary_id','main_order_id', 'sub_appendices_title', 'sub_appendices_content'];

    public function footnoteModel()
    {
        return $this->hasMany(Footnote::class, 'sub_appendices_id', 'sub_appendices_id');
    }
}
