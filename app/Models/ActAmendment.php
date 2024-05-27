<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActAmendment extends Model
{
    use HasFactory;
    protected $primaryKey = 'act_amendment_id';
    protected $table = 'act_amendment';
    protected $fillable = [ 'act_id','act_amendment_title', 'act_amendment_pdf'];

}
