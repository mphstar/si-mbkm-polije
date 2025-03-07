<?php

namespace App\Models;

use App\ProgramStudy;
use App\User;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Students extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'students';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = [];
    // protected $fillable = ['name','address','phone','nim','jurusan','program_studi','semester','users_id','created_at','updated_at'];
    // protected $hidden = b[];
    // protected $dates = [];
    public function regs()
    {
        return $this->hasMany(RegisterMbkm::class);
    }
    public function pengajuansementara()
    {
        return $this->hasMany(PengajuanEXTR::class);
    }

    public function program_study(){
        return $this->belongsTo(ProgramStudy::class, 'study_program_id', 'id');
    }

    public function users(){
        return $this->belongsTo(User::class, 'users_id', 'id');
    }
    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
