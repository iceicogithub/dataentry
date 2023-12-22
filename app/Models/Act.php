<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Act extends Model
{
    use HasFactory;
    protected $primaryKey = 'act_id';
    protected $table = 'acts';
    protected $fillable = ['category_id', 'state_id', 'act_title', 'act_content','act_no','act_date','act_description','act_footnote_title','act_footnote_description','act_summary'];

    public function CategoryModel(){
        return $this->belongsTo(Category::class, 'category_id','category_id');
    }
    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }
}
