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
    protected $fillable = ['act_id','maintype_id', 'priliminary_title'];

    public function PriliminaryType()
    {
        return $this->belongsTo(MainType::class, 'maintype_id', 'maintype_id');
    }
    public function sections()
    {
        return $this->hasMany(Section::class, 'priliminary_id', 'priliminary_id');
    }
}
