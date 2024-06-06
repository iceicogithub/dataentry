<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $primaryKey = 'notifications_id';
    protected $table = 'notification';
    protected $fillable = [ 'act_id','notifications_title', 'notifications_pdf','notifications_date','ministry','notifications_no','act_summary_id'];

    public function actSummary(){
        return $this->belongsTo(ActSummary::class, 'act_summary_id','id');
    }
}
