<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubTypeSchemeGuidelines extends Model
{
    use HasFactory;
    protected $primaryKey = 'scheme_guidelines_subtypes_id';
    protected $table = 'schemeguidelinessubtypes';
    protected $fillable = ['type'];
}
