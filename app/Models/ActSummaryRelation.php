<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActSummaryRelation extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $table = 'act_summary_relation';
    protected $fillable = ['act_summary_id','act_id'];

        public function actSummary()
    {
        return $this->belongsTo(ActSummary::class, 'act_summary_id');
    }
}
