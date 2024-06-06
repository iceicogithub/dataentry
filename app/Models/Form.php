<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    use HasFactory;
    protected $primaryKey = 'forms_id';
    protected $table = 'forms';
    protected $fillable = ['act_id','forms_title','forms_pdf','forms_no','forms_date','ministry','act_summary_id'];

    public function actSummary(){
        return $this->belongsTo(ActSummary::class, 'act_summary_id','id');
    }
}
