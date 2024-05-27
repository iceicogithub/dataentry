<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchemeGuidelinesMain extends Model
{
    use HasFactory;
    protected $primaryKey = 'scheme_guidelines_main_id';
    protected $table = 'scheme_guidelines_main_table';
    protected $fillable = ['scheme_guidelines_main_rank', 'new_scheme_guidelines_id', 'act_id', 'scheme_guidelines_maintype_id', 'scheme_guidelines_main_title'];

    public function mainTypeSchemeGuidelines()
    {
        return $this->hasMany(MainTypeSchemeGuidelines::class, 'scheme_guidelines_maintype_id', 'scheme_guidelines_maintype_id');
    }

    public function schemeGuidelinestbl()
    {
        return $this->hasMany(SchemeGuidelinesTable::class, 'scheme_guidelines_main_id', 'scheme_guidelines_main_id');
    }

    public function schemeGuidelinesFootnoteModel()
    {
        return $this->belongsTo(MainSchemeGuidelinesFootnote::class, 'scheme_guidelines_main_id', 'scheme_guidelines_main_id');
    }

    public function newschemeGuidelines()
    {
       return $this->belongsTo(NewSchemeGuidelines::class, 'new_scheme_guidelines_id', 'new_scheme_guidelines_id');
    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($mainSchemeGuidelines) {
            $mainSchemeGuidelines->schemeGuidelinestbl()->delete();
        });
    }
}
