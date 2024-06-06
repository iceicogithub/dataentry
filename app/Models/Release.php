<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Release extends Model
{
    use HasFactory;
    protected $primaryKey = 'release_id';
    protected $table = 'releases';
    protected $fillable = ['act_id','release_title','release_pdf','release_no','release_date','ministry','act_summary_id'];

    public function actSummary(){
        return $this->belongsTo(ActSummary::class, 'act_summary_id','id');
    }
}
