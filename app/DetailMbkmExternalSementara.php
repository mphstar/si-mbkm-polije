<?php

namespace App;

use App\Models\Mbkm;
use Illuminate\Database\Eloquent\Model;

class DetailMbkmExternalSementara extends Model
{
    protected $table = 'detail_pendaftaran_exmbkm_sementara';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = [];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];

    public function mbkm(){
        return $this->belongsTo(Mbkm::class, 'mbkm_id', 'id');
    }
}
