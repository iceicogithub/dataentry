<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchemeGuidelinesSub extends Model
{
    use HasFactory;
    protected $primaryKey = 'scheme_guidelines_sub_id';
    protected $table = 'scheme_guidelines_sub_table';
    protected $fillable = ['scheme_guidelines_main_id', 'scheme_guidelines_id', 'scheme_guidelines_sub_rank','new_scheme_guidelines_id', 'scheme_guidelines_subtypes_id','scheme_guidelines_sub_no','scheme_guidelines_sub_title','scheme_guidelines_sub_content'];

    public function schemeGuidelinesSubFootnoteModel()
    {
        return $this->hasMany(MainSchemeGuidelinesFootnote::class, 'scheme_guidelines_sub_id', 'scheme_guidelines_sub_id');
    }
}
