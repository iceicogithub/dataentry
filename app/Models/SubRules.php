<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubRules extends Model
{
    use HasFactory;
    protected $primaryKey = 'sub_rule_id';
    protected $table = 'sub_rules';
    protected $fillable = ['sub_rule_no', 'rule_id', 'rule_no', 'act_id', 'schedule_id','sub_rule_title', 'sub_rule_content'];

    public function footnoteModel()
    {
        return $this->hasMany(Footnote::class, 'sub_rule_id', 'sub_rule_id');
    }
}
