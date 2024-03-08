<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubLists extends Model
{
    use HasFactory;
    protected $primaryKey = 'sub_list_id';
    protected $table = 'sub_lists';
    protected $fillable = ['sub_list_no', 'list_id', 'list_no', 'act_id','appendix_id','schedule_id', 'chapter_id', 'parts_id','priliminary_id', 'sub_list_title', 'sub_list_content'];

    public function footnoteModel()
    {
        return $this->hasMany(Footnote::class, 'sub_list_id', 'sub_list_id');
    }
}
