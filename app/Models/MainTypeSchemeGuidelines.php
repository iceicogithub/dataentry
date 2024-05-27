<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainTypeSchemeGuidelines extends Model
{
    use HasFactory;
    protected $primaryKey = 'scheme_guidelines_maintype_id';
    protected $table = 'schemeguidelinesmaintypes';
    protected $fillable = ['type'];
}
