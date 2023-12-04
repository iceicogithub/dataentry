<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parts extends Model
{
    use HasFactory;
    protected $primaryKey = 'parts_id';
    protected $table = 'parts';
    protected $fillable = ['maintype_id','partstype_id','parts_title'];

    public function PartsType()
    {
         return $this->belongsTo(MainType::class, 'maintype_id', 'maintype_id');
    }
    
}
