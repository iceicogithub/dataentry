<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainTable extends Model
{
    use HasFactory;
    protected $primaryKey = 'main_id';
    protected $table = 'main_table';
    protected $fillable = ['main_id','main_rank','act_id', 'maintype_id', 'serial_no','chapter_id','parts_id','priliminary_id','schedule_id','main_order_id','appendix_id'];


    public function MainType()
    {
        return $this->belongsTo(MainType::class, 'maintype_id', 'maintype_id');
    }

    public function chapters()
    {
        return $this->hasMany(Chapter::class, 'chapter_id', 'chapter_id');
    }

    public function parts()
    {
        return $this->hasMany(Parts::class, 'parts_id', 'parts_id');
    }

    public function priliminarys()
    {
        return $this->hasMany(Priliminary::class, 'priliminary_id', 'priliminary_id');
    }

    public function mainOrders()
    {
        return $this->hasMany(MainOrder::class, 'main_order_id', 'main_order_id');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'schedule_id', 'schedule_id');
    }

    public function appendixes()
    {
        return $this->hasMany(Appendix::class, 'appendix_id', 'appendix_id');
    }

}
