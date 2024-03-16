<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainOrder extends Model
{
    use HasFactory;
    protected $primaryKey = 'main_order_id';
    protected $table = 'main_order';
    protected $fillable = ['act_id','maintype_id', 'main_order_title','serial_no'];

    public function MainOrderType()
    {
        return $this->belongsTo(MainType::class, 'maintype_id', 'maintype_id');
    }
    public function Sections()
    {
        return $this->hasMany(Section::class, 'main_order_id', 'main_order_id');
    }

    public function Articles()
    {
        return $this->hasMany(Article::class,'main_order_id', 'main_order_id');
    }

    public function Rules()
    {
        return $this->hasMany(Rules::class,'main_order_id', 'main_order_id');
    }

    public function Regulation()
    {
        return $this->hasMany(Regulation::class, 'main_order_id', 'main_order_id');
    }

    public function Lists()
    {
        return $this->hasMany(Lists::class,'main_order_id', 'main_order_id');
    }

    public function Part()
    {
        return $this->hasMany(Part::class,'main_order_id', 'main_order_id');
    }
    public function Appendices()
    {
        return $this->hasMany(Appendices::class, 'main_order_id', 'main_order_id');
    }

    public function Order()
    {
        return $this->hasMany(Orders::class,'main_order_id', 'main_order_id');
    }

    public function Annexure()
    {
        return $this->hasMany(Annexure::class,'main_order_id', 'main_order_id');
    }
    public function Stschedule()
    {
        return $this->hasMany(Stschedule::class,'main_order_id', 'main_order_id');
    }

}
