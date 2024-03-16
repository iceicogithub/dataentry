<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Priliminary extends Model
{
    use HasFactory;
    use HasFactory;
    protected $primaryKey = 'priliminary_id';
    protected $table = 'priliminary';
    protected $fillable = ['act_id','maintype_id', 'priliminary_title','serial_no'];

    public function PriliminaryType()
    {
        return $this->belongsTo(MainType::class, 'maintype_id', 'maintype_id');
    }
    public function Sections()
    {
        return $this->hasMany(Section::class, 'priliminary_id', 'priliminary_id');
    }

    public function Articles()
    {
        return $this->hasMany(Article::class,'priliminary_id', 'priliminary_id');
    }

    public function Rules()
    {
        return $this->hasMany(Rules::class,'priliminary_id', 'priliminary_id');
    }

    public function Regulation()
    {
        return $this->hasMany(Regulation::class, 'priliminary_id', 'priliminary_id');
    }

    public function Lists()
    {
        return $this->hasMany(Lists::class,'priliminary_id', 'priliminary_id');
    }

    public function Part()
    {
        return $this->hasMany(Part::class,'priliminary_id', 'priliminary_id');
    }
    public function Appendices()
    {
        return $this->hasMany(Appendices::class, 'priliminary_id', 'priliminary_id');
    }

    public function Order()
    {
        return $this->hasMany(Orders::class,'priliminary_id', 'priliminary_id');
    }

    public function Annexure()
    {
        return $this->hasMany(Annexure::class,'priliminary_id', 'priliminary_id');
    }
    public function Stschedule()
    {
        return $this->hasMany(Stschedule::class,'priliminary_id', 'priliminary_id');
    }
}
