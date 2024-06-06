<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdinanceSub extends Model
{
    use HasFactory;
    protected $primaryKey = 'ordinance_sub_id';
    protected $table = 'ordinance_sub_table';
    protected $fillable = ['ordinance_main_id', 'ordinances_id', 'ordinance_sub_rank','new_ordinance_id', 'ordinance_subtypes_id','ordinance_sub_no','ordinance_sub_title','ordinance_sub_content'];

    public function ordinanceSubFootnoteModel()
    {
        return $this->hasMany(MainOrdinanceFootnote::class, 'ordinance_sub_id', 'ordinance_sub_id');
    }
}
