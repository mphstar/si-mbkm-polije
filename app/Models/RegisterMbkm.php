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
        return $this->belongsTo(Mbkm::class);
    }
    public function lecturer()
    {
        return $this->belongsTo(Lecturer::class);
    }
    public function reports()
    {
        return $this->hasMany(MbkmReport::class);
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
            return '<span class="badge bg-success">Accept</span>';
        } elseif ($status == 'rejected') {
            return '<span class="badge bg-danger">Rejected</span>';
        } elseif($status == 'pending'){
            return '<span class="badge bg-warning">Pending</span>';
        }else {
            return '<span class="badge bg-success">Done</span>';
        }
    }
}
