<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class RegisterMbkm extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'reg_mbkms';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];
    public function student()
    {
        return $this->belongsTo(Students::class);
    }
    public function mbkm()
    {
        return $this->belongsTo(Mbkm::class, 'mbkm_id', 'id');
    }
    public function partner()
    {
        return $this->belongsTo(Partner::class, 'partner_id', 'id');
    }
    public function jenismbkm()
    {
        return $this->belongsTo(JenisMbkm::class, 'id_jenis', 'id');
    }
    public function lecturer()
    {
        return $this->belongsTo(Lecturer::class,'pembimbing','id');
    }
    public function reports()
    {
        return $this->belongsTo(MbkmReport::class);
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
    public function Download($crud = false)
    {
        $url = str_replace(' ', '', $this->requirements_files);
$url = str_replace('/ ', '/', $url);
return '<a class="btn btn-sm btn-link" target="_blank" href="/' . $url . '" data-toggle="tooltip" title="Just a demo custom button."><i class="fa fa-search"></i> Download</a>';

    }
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
    public function getStatusSpan() {
        $status = $this->attributes['status'];
        
        if ($status == 'accepted') {
            return '<div class="badge bg-success">Diterima</div>';
        } elseif ($status == 'rejected') {
            return '<div class="badge bg-danger">Ditolak</div>';
        } elseif($status == 'pending'){
            return '<div class="badge bg-warning">Menunggu</div>';
        }else {
            return '<div class="badge bg-success">Selesai</div>';
        }
    }
}
