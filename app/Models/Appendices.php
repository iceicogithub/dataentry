<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appendices extends Model
{
    use HasFactory;
    protected $primaryKey = 'appendices_id';
    protected $table = 'appendices';
    protected $fillable = ['act_id','maintype_id', 'appendices_title'];

    public function AppendicesType()
    {
        return $this->belongsTo(MainType::class, 'maintype_id', 'maintype_id');
    }
}
