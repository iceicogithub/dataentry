<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainOrdinanceFootnote extends Model
{
    use HasFactory;
    protected $primaryKey = 'ordinance_footnote_id';
    protected $table = 'main_ordinance_footnote';
    protected $fillable = ['ordinances_id','ordinance_sub_id', 'new_ordinance_id', 'footnote_title', 'footnote_content'];
}
