<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegulationSub extends Model
{
    use HasFactory;
    protected $primaryKey = 'regulation_sub_id';
    protected $table = 'regulation_sub_table';
    protected $fillable = ['regulation_main_id', 'regulations_id', 'regulation_sub_rank','new_regulation_id', 'regulation_subtypes_id','regulation_sub_no','regulation_sub_title','regulation_sub_content'];

    public function regulationSubFootnoteModel()
    {
        return $this->hasMany(MainRegulationFootnote::class, 'regulation_sub_id', 'regulation_sub_id');
    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($regulationSub) {
            $regulationSub->regulationSubFootnoteModel()->delete();
        });
    }
}
