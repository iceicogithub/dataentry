<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;
    protected $primaryKey = 'schedule_id';
    protected $table = 'schedule';
    protected $fillable = ['act_id','maintype_id', 'schedule_title','serial_no'];

    public function ScheduleType()
    {
        return $this->belongsTo(MainType::class, 'maintype_id', 'maintype_id');
    }

    public function Sections()
    {
        return $this->hasMany(Section::class, 'schedule_id', 'schedule_id');
    }

    public function Articles()
    {
        return $this->hasMany(Article::class,'schedule_id', 'schedule_id');
    }

    public function Rules()
    {
        return $this->hasMany(Rules::class,'schedule_id', 'schedule_id');
    }

    public function Regulation()
    {
        return $this->hasMany(Regulation::class, 'schedule_id', 'schedule_id');
    }

    public function Lists()
    {
        return $this->hasMany(Lists::class,'schedule_id', 'schedule_id');
    }

    public function Part()
    {
        return $this->hasMany(Part::class,'schedule_id', 'schedule_id');
    }
    public function Appendices()
    {
        return $this->hasMany(Appendices::class, 'schedule_id', 'schedule_id');
    }

    public function Order()
    {
        return $this->hasMany(Orders::class,'schedule_id', 'schedule_id');
    }

    public function Annexure()
    {
        return $this->hasMany(Annexure::class,'schedule_id', 'schedule_id');
    }
    public function Stschedule()
    {
        return $this->hasMany(Stschedule::class,'schedule_id', 'schedule_id');
    }

    protected static function boot()
    {
        parent::boot();

        // Define the deleting event
        static::deleting(function ($parts) {
            // Delete related subtypes
            $parts->Sections()->delete();
            $parts->Articles()->delete();
            $parts->Rules()->delete();
            $parts->Regulation()->delete();
            $parts->Lists()->delete();
            $parts->Part()->delete();
            $parts->Appendices()->delete();
            $parts->Order()->delete();
            $parts->Annexure()->delete();
            $parts->Stschedule()->delete();
        });
    }


}
