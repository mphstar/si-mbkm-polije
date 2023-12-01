<?php

namespace App\Models;

use App\User;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use CrudTrait;
    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'partners';
    protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];

    //FUNCTIONS
    public function mbkms()
    {
        return $this->hasMany('App\Models\Mbkm', 'partner_id', 'id');
    }
    public function pengajuan_sub()
    {
        return $this->hasMany(PengajuanEXTRSub::class, 'partner_id', 'id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'users_id', 'id');
    }


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
    public function getStatusSpan()
    {
        $status = $this->attributes["status"];

        if ($status == 'accepted') {
            return '<div class="badge bg-success">Diterima</div>';
        } elseif ($status == 'rejected') {
            return '<div class="badge bg-danger">Ditolak</div>';
        } else {
            return '<div class="badge bg-warning">Menunggu</div>';
        }
    }
}
