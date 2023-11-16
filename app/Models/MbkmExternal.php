<?php

namespace App\Models;

use App\DetailMbkmExternalSementara;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class MbkmExternal extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'pendaftaran_exmbkm_sementara';
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
    public function lihatDetail($crud = false)
    {

        $btn = '<a href="/admin/mbkm-external/'. $this->id . '/detail"><button class="btn btn-block btn-sm btn-link text-left px-0 active" type="button" aria-pressed="true">Lihat Detail</button></a>';

        return $btn;
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function detail(){
        return $this->hasMany(DetailMbkmExternalSementara::class, 'exmbkm_id', 'id');
    }

    public function student(){
        return $this->belongsTo(Students::class, 'student_id', 'id');
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
}
