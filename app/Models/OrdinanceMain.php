<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdinanceMain extends Model
{
    use HasFactory;
    protected $primaryKey = 'ordinance_main_id';
    protected $table = 'ordinance_main_table';
    protected $fillable = ['ordinance_main_rank', 'new_ordinance_id', 'act_id', 'ordinance_maintype_id', 'ordinance_main_title'];

    public function mainTypeOrdinance()
    {
        return $this->hasMany(MainTypeOrdinance::class, 'ordinance_maintype_id', 'ordinance_maintype_id');
    }

    public function ordinancetbl()
    {
        return $this->hasMany(OrdinanceTable::class, 'ordinance_main_id', 'ordinance_main_id');
    }

    public function ordinanceFootnoteModel()
    {
        return $this->belongsTo(MainOrdinanceFootnote::class, 'ordinance_main_id', 'ordinance_main_id');
    }

    public function newOrdinance()
    {
       return $this->belongsTo(NewOrdinance::class, 'new_ordinance_id', 'new_ordinance_id');
    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($mainOrdinance) {
            $mainOrdinance->ordinancetbl()->delete();
        });
    }
}
