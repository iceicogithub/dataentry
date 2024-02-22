<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubAnnexture extends Model
{
    use HasFactory;
    protected $primaryKey = 'sub_annexture_id';
    protected $table = 'sub_annextures';
    protected $fillable = ['sub_annexture_no', 'annexture_id', 'annexture_no', 'act_id','appendices_id','schedule_id', 'chapter_id', 'parts_id','priliminary_id', 'sub_annexture_title', 'sub_annexture_content'];

    public function footnoteModel()
    {
        return $this->hasMany(Footnote::class, 'sub_annexture_id', 'sub_annexture_id');
    }
}
