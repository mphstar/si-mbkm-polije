<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvolvedCourse extends Model
{
    protected $table = 'involved_course';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = [];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];

    public function course(){
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }
}
