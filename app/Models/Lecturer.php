<?php

namespace App\Models;

use App\Http\Controllers\ClassApi;
use App\ProgramStudy;
use App\User;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Lecturer extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'lecturers';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];

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
    public function user(){
        return $this->belongsTo(User::class, 'users_id', 'id');
    }

    public function program_studi(){
        return $this->belongsTo(ProgramStudy::class, 'study_program_id', 'id');
    }

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
    public function getTextJurusan()
    {
        $ap = new ClassApi;
        $jurusan = $ap->getJurusan(request());

        $res = 'Tidak diketahui';

        foreach ($jurusan as $key => $value) {
            if($value->uuid == $this->jurusan){
                $res = $value->unit;
                break;
            }
        }

        return $res;
    }
}
