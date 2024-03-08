<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubRegulation extends Model
{
    use HasFactory;
    protected $primaryKey = 'sub_regulation_id';
    protected $table = 'sub_regulations';
    protected $fillable = ['sub_regulation_no', 'regulation_id', 'regulation_no', 'act_id','appendix_id','schedule_id', 'chapter_id', 'parts_id','priliminary_id', 'sub_regulation_title', 'sub_regulation_content'];

    public function footnoteModel()
    {
        return $this->hasMany(Footnote::class, 'sub_regulation_id', 'sub_regulation_id');
    }
}
