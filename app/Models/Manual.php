<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manual extends Model
{
    use HasFactory;
    protected $primaryKey = 'manuals_id';
    protected $table = 'manuals';
    protected $fillable = [ 'act_id','manuals_title', 'manuals_pdf','manuals_date','ministry','manuals_no','act_summary_id'];

    public function actSummary(){
        return $this->belongsTo(ActSummary::class, 'act_summary_id','id');
    }
}
