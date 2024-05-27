<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchemeGuidelinesTable extends Model
{
    use HasFactory;
    protected $primaryKey = 'scheme_guidelines_id';
    protected $table = 'scheme_guidelines_table';
    protected $fillable = ['new_scheme_guidelines_id','scheme_guidelines_main_id', 'scheme_guidelines_rank', 'act_id', 'scheme_guidelines_subtypes_id','scheme_guidelines_no','scheme_guidelines_title','scheme_guidelines_content'];
    

    public function subTypeSchemeGuidelines()
    {
        return $this->hasMany(SubTypeSchemeGuidelines::class, 'scheme_guidelines_subtypes_id', 'scheme_guidelines_subtypes_id');
    }

    public function mainschemeGuidelines()
    {
       return $this->belongsTo(SchemeGuidelinesMain::class, 'scheme_guidelines_main_id', 'scheme_guidelines_main_id');
    }

   public function schemeGuidelinesFootnoteModel()
   {
       return $this->hasMany(MainSchemeGuidelinesFootnote::class, 'scheme_guidelines_id', 'scheme_guidelines_id');
   }

   public function schemeGuidelinesSub()
   {
       return $this->hasMany(SchemeGuidelinesSub::class, 'scheme_guidelines_id', 'scheme_guidelines_id');
   }

   public function newSchemeGuidelines()
   {
      return $this->belongsTo(NewSchemeGuidelines::class, 'new_scheme_guidelines_id', 'new_scheme_guidelines_id');
   }
   protected static function boot()
    {
        parent::boot();
        static::deleting(function ($schemeGuidelinestbl) {
            $schemeGuidelinestbl->schemeGuidelinesSub()->delete();
        });
    }
}
