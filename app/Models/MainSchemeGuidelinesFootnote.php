<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainSchemeGuidelinesFootnote extends Model
{
    use HasFactory;
    protected $primaryKey = 'scheme_guidelines_footnote_id';
    protected $table = 'main_scheme_guidelines_footnote';
    protected $fillable = ['scheme_guidelines_id','scheme_guidelines_sub_id', 'new_scheme_guidelines_id', 'footnote_title', 'footnote_content'];
}
