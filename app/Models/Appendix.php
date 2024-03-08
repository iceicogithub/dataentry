<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appendix extends Model
{
    use HasFactory;
    protected $primaryKey = 'appendix_id';
    protected $table = 'appendix';
    protected $fillable = ['act_id','maintype_id', 'appendix_title'];

    public function AppendixType()
    {
        return $this->belongsTo(MainType::class, 'maintype_id', 'maintype_id');
    }
}
