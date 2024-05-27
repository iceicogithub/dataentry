<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainRegulationFootnote extends Model
{
    use HasFactory;
    protected $primaryKey = 'regulation_footnote_id';
    protected $table = 'main_regulation_footnote';
    protected $fillable = ['regulations_id','regulation_sub_id', 'new_regulation_id', 'footnote_title', 'footnote_content'];

    
}
