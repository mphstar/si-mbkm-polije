<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class PenilaianMitra extends Model
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

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function student()
    {
        return $this->belongsTo(Students::class,'student_id','id');
    }
    public function mbkm()
    {
        return $this->belongsTo(Mbkm::class, 'mbkm_id', 'id');
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
    // public function setImageAttribute($value)
    // {
    //     $attribute_name = "partner_grade";
    //     $disk = "public";
    //     $destination_path = "upload";

    //     $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);

    // // return $this->attributes[{$attribute_name}]; // uncomment if this is a translatable field
    // }
   
        public function setPartnergradeAttribute($value)
        {
            $attribute_name = "partnergrade";
            $disk = "public";
            $destination_path = 'uploads';
    
            $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);
    
        // return $this->attributes[{$attribute_name}]; // uncomment if this is a translatable field
        }
        // $attribute_name = "partner_grade";
        // $disk = "public";
        // $destination_path = "uploads";

        // $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);

    // return $this->attributes[{$attribute_name}]; // uncomment if this is a translatable field
    
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
