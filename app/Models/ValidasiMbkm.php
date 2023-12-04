<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class ValidasiMbkm extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'mbkms';
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
    public function partner()
    {
        return $this->belongsTo(\App\Models\Partner::class, 'partner_id');
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
    public function getIsactiveSpan() {
        $status = $this->attributes['is_active'];
        
        if ($status == 'active') {
            return '<p class="badge bg-success">Aktif</p>';
        } elseif ($status == 'inactive') {
            return '<p class="badge bg-danger">Tidak Aktif</p>';
        } else {
            return '<p class="badge bg-warning">Menunggu</p>';
        }
    }
    public function getStatusSpan() {
        $status = $this->attributes['status_acc'];
        
        if ($status == 'accepted') {
            return '<p class="badge bg-success">Diterima</p>';
        } elseif ($status == 'rejected') {
            return '<p class="badge bg-danger">Ditolak</p>';
        } else {
            return '<p class="badge bg-warning">Menunggu</p>';
        }
    }
    
}
