<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdinanceTable extends Model
{
    use HasFactory;
    protected $primaryKey = 'ordinances_id';
    protected $table = 'ordinance_table';
    protected $fillable = ['new_ordinance_id','ordinance_main_id', 'ordinances_rank', 'act_id', 'ordinance_subtypes_id','ordinances_no','ordinances_title','ordinances_content'];
    

    public function subTypeOrdinance()
    {
        return $this->hasMany(SubTypeOrdinance::class, 'ordinance_subtypes_id', 'ordinance_subtypes_id');
    }

    public function mainOrdinance()
    {
       return $this->belongsTo(OrdinanceMain::class, 'ordinance_main_id', 'ordinance_main_id');
    }

   public function ordinanceFootnoteModel()
   {
       return $this->hasMany(MainOrdinanceFootnote::class, 'ordinances_id', 'ordinances_id');
   }

   public function ordinanceSub()
   {
       return $this->hasMany(OrdinanceSub::class, 'ordinances_id', 'ordinances_id');
   }

   public function newOrdinance()
   {
      return $this->belongsTo(NewOrdinance::class, 'new_ordinance_id', 'new_ordinance_id');
   }
    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($ordinancetbl) {
            $ordinancetbl->ordinanceSub()->delete();
        });
    }
}
