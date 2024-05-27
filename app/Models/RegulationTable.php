<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegulationTable extends Model
{
   
    use HasFactory;
    protected $primaryKey = 'regulations_id';
    protected $table = 'regulation_table';
    protected $fillable = ['new_regulation_id','regulation_main_id', 'regulations_rank', 'act_id', 'regulation_subtypes_id','regulations_no','regulations_title','regulations_content'];
    
    public function subTypeRegulation()
    {
        return $this->hasMany(SubTypeRegulation::class, 'regulation_subtypes_id', 'regulation_subtypes_id');
    }

    public function mainRegulation()
    {
       return $this->belongsTo(RegulationMain::class, 'regulation_main_id', 'regulation_main_id');
    }

   public function regulationFootnoteModel()
   {
       return $this->hasMany(MainRegulationFootnote::class, 'regulations_id', 'regulations_id');
   }

   public function regulationSub()
   {
       return $this->hasMany(RegulationSub::class, 'regulations_id', 'regulations_id');
   }

   public function newRegulation()
   {
      return $this->belongsTo(NewRegulation::class, 'new_regulation_id', 'new_regulation_id');
   }
   protected static function boot()
    {
        parent::boot();
        static::deleting(function ($regulationtbl) {
            $regulationtbl->regulationSub()->delete();
            $regulationtbl->regulationFootnoteModel()->delete();
        });
    }

}
