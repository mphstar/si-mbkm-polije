<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Validasilaporan extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'mbkm_reports';
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
    public function regMbkm()
    {
        return $this->belongsTo(RegisterMbkm::class);
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
    public function Download($crud = false)
    {
        $url = str_replace(' ', '', $this->file);
$url = str_replace('/ ', '/', $url);
return '<a class="btn btn-sm btn-link" target="_blank" href="/' . $url . '" data-toggle="tooltip" title="Just a demo custom button."><i class="fa fa-search"></i> Download</a>';

    }
    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */
    public function getStatusSpan() {
        $status = $this->attributes['status'];
        
        if ($status == 'accepted') {
            return '<p class="badge bg-success">Diterima</p>';
        } elseif ($status == 'rejected') {
            return '<p class="badge bg-danger">Tidak Diterima</p>';
        } else {
            return '<p class="badge bg-warning">Menunggu</p>';
        }
    }
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
