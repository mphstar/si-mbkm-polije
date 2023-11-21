<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Mbkm extends Model
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

    public function partner()
    {
        return $this->belongsTo(Partner::class, 'partner_id', 'id');
    }
    public function regs()
    {
        return $this->hasMany(RegisterMbkm::class);
    }

    public function jenismbkm(){
        return $this->belongsTo(JenisMbkm::class, 'id_jenis', 'id');
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
    public function getIsactiveSpan() {
        $status = $this->attributes['is_active'];
        
        if ($status == 'active') {
            return '<span class="badge bg-success">Active</span>';
        } elseif ($status == 'inactive') {
            return '<span class="badge bg-danger">Inactive</span>';
        } else {
            return '<span class="badge bg-warning">Pending</span>';
        }
    }
    public function getStatusSpan() {
        $status = $this->attributes['status_acc'];
        
        if ($status == 'accepted') {
            return '<span class="badge bg-success">Accept</span>';
        } elseif ($status == 'rejected') {
            return '<span class="badge bg-danger">Rejected</span>';
        } else {
            return '<span class="badge bg-warning">Pending</span>';
        }
    }
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
