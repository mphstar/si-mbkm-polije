<?php

namespace App\Models;

use App\Http\Controllers\ClassApi;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ManagementMBKM extends Model
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
    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }
    public function jenismbkm()
    {
        return $this->belongsTo(JenisMbkm::class, 'id_jenis', 'id');
    }

    public function departmen()
    {
        return $this->belongsTo(Departmen::class, 'departments_id', 'id');
    }
    public function regs()
    {
        return $this->hasMany(RegisterMbkm::class);
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
        $status = $this->attributes['status_acc'];

        if ($status == 'accepted') {
            return '<span class="badge bg-success">Accept</span>';
        } elseif ($status == 'rejected') {
            return '<span class="badge bg-danger">Rejected</span>';
        } else {
            return '<span class="badge bg-warning">Pending</span>';
        }
    }
    public function getIsactiveSpan()
    {
        $status = $this->attributes['is_active'];

        if ($status == 'active') {
            return '<span class="badge bg-success">Active</span>';
        } elseif ($status == 'inactive') {
            return '<span class="badge bg-danger">Inactive</span>';
        } else {
            return '<span class="badge bg-warning">Pending</span>';
        }
    }

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
