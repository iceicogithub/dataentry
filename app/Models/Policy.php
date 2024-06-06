<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Policy extends Model
{
    use HasFactory;
    protected $primaryKey = 'policy_id';
    protected $table = 'polices';
    protected $fillable = [ 'act_id','policy_title', 'policy_pdf','policy_date','ministry','policy_no','act_summary_id'];

    public function actSummary(){
        return $this->belongsTo(ActSummary::class, 'act_summary_id','id');
    }
}
