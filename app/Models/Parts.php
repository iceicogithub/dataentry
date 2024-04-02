<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parts extends Model
{
    use HasFactory;
    protected $primaryKey = 'parts_id';
    protected $table = 'parts';
    protected $fillable = ['maintype_id', 'partstype_id', 'parts_title','serial_no'];

    public function PartsType()
    {
        return $this->belongsTo(MainType::class, 'maintype_id', 'maintype_id');
    }
    public function partsTypepdf()
    {
        return $this->belongsTo(PartsType::class, 'partstype_id', 'partstype_id');
    }
    public function Sections()
    {
        return $this->hasMany(Section::class, 'parts_id', 'parts_id');
    }
    public function Articles()
    {
        return $this->hasMany(Article::class,'parts_id', 'parts_id');
    }

    public function Rules()
    {
        return $this->hasMany(Rules::class,'parts_id', 'parts_id');
    }
    public function Regulation()
    {
        return $this->hasMany(Regulation::class, 'parts_id', 'parts_id');
    }

    public function Lists()
    {
        return $this->hasMany(Lists::class,'parts_id', 'parts_id');
    }

    public function Part()
    {
        return $this->hasMany(Part::class,'parts_id', 'parts_id');
    }
    public function Appendices()
    {
        return $this->hasMany(Appendices::class, 'parts_id', 'parts_id');
    }

    public function Order()
    {
        return $this->hasMany(Orders::class,'parts_id', 'parts_id');
    }

    public function Annexure()
    {
        return $this->hasMany(Annexure::class,'parts_id', 'parts_id');
    }
    public function Stschedule()
    {
        return $this->hasMany(Stschedule::class,'parts_id', 'parts_id');
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
