<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Circular extends Model
{
    use HasFactory;
    protected $primaryKey = 'circulars_id';
    protected $table = 'circulars';
    protected $fillable = [ 'act_id','circulars_title', 'circulars_pdf','circulars_date','ministry','circulars_no','act_summary_id'];

    public function actSummary(){
        return $this->belongsTo(ActSummary::class, 'act_summary_id','id');
    }
}
