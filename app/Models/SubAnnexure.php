<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubAnnexure extends Model
{
    use HasFactory;
    protected $primaryKey = 'sub_annexure_id';
    protected $table = 'sub_annexures';
    protected $fillable = ['sub_annexure_no', 'annexure_id', 'annexure_no', 'act_id','appendix_id','schedule_id', 'chapter_id', 'parts_id','priliminary_id', 'sub_annexure_title', 'sub_annexure_content'];

    public function footnoteModel()
    {
        return $this->hasMany(Footnote::class, 'sub_annexure_id', 'sub_annexure_id');
    }
}
