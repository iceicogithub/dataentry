<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Act extends Model
{
    use HasFactory;
    protected $primaryKey = 'act_id';
    protected $table = 'acts';
    protected $fillable = ['category_id', 'state_id', 'act_title', 'act_content','ministry','act_no','act_date','enactment_date','enforcement_date','act_description','act_footnote_title','act_footnote_description','act_summary'];

    public function CategoryModel(){
        return $this->belongsTo(Category::class, 'category_id','category_id');
    }
    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    public function sectionAct(){
        return $this->hasMany(Section::class, 'act_id', 'act_id');
    }

    public function articleAct(){
        return $this->hasMany(Article::class, 'act_id', 'act_id');
    }

    public function ruleAct(){
        return $this->hasMany(Rules::class, 'act_id', 'act_id');
    }

    public function regulationAct(){
        return $this->hasMany(Regulation::class, 'act_id', 'act_id');
    }

    public function listAct(){
        return $this->hasMany(Lists::class, 'act_id', 'act_id');
    }

    public function partAct(){
        return $this->hasMany(Part::class, 'act_id', 'act_id');
    }
    public function appendiceAct(){
        return $this->hasMany(Appendices::class, 'act_id', 'act_id');
    }
    public function orderAct(){
        return $this->hasMany(Orders::class, 'act_id', 'act_id');
    }
    public function annexureAct(){
        return $this->hasMany(Annexure::class, 'act_id', 'act_id');
    }

    public function scheduleAct(){
        return $this->hasMany(Stschedule::class, 'act_id', 'act_id');
    }


}
