<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appendix extends Model
{
    use HasFactory;
    protected $primaryKey = 'appendix_id';
    protected $table = 'appendix';
    protected $fillable = ['act_id','maintype_id', 'appendix_title'];

    public function AppendixType()
    {
        return $this->belongsTo(MainType::class, 'maintype_id', 'maintype_id');
    }
    public function Sections()
    {
        return $this->hasMany(Section::class, 'appendix_id', 'appendix_id');
    }

    public function Articles()
    {
        return $this->hasMany(Article::class,'appendix_id', 'appendix_id');
    }

    public function Rules()
    {
        return $this->hasMany(Rules::class,'appendix_id', 'appendix_id');
    }

    public function Regulation()
    {
        return $this->hasMany(Regulation::class, 'appendix_id', 'appendix_id');
    }

    public function Lists()
    {
        return $this->hasMany(Lists::class,'appendix_id', 'appendix_id');
    }

    public function Part()
    {
        return $this->hasMany(Part::class,'appendix_id', 'appendix_id');
    }
    public function Appendices()
    {
        return $this->hasMany(Appendices::class, 'appendix_id', 'appendix_id');
    }

    public function Order()
    {
        return $this->hasMany(Orders::class,'appendix_id', 'appendix_id');
    }

    public function Annexure()
    {
        return $this->hasMany(Annexure::class,'appendix_id', 'appendix_id');
    }
    public function Stschedule()
    {
        return $this->hasMany(Stschedule::class,'appendix_id', 'appendix_id');
    }

}
