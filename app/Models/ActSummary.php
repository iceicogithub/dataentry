<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActSummary extends Model
{
    use HasFactory;
    protected $table = 'act_summary';
    protected $fillable = ['title'];

    public function act()
    {
        return $this->hasMany(Act::class, 'act_summary_id', 'id');
    }

    public function rule()
    {
        return $this->hasMany(NewRule::class, 'act_summary_id', 'id');
    }

    public function acts()
    {
        return $this->belongsToMany(Act::class, 'act_summary_relation', 'act_summary_id', 'act_id');
    }

   
}
