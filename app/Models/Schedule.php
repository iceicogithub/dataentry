<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;
    protected $primaryKey = 'schedule_id';
    protected $table = 'schedule';
    protected $fillable = ['act_id','maintype_id', 'schedule_title'];

    public function ScheduleType()
    {
        return $this->belongsTo(MainType::class, 'maintype_id', 'maintype_id');
    }
}
