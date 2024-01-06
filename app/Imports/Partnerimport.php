<?php

namespace App\Imports;

use App\Models\Partner;
use Maatwebsite\Excel\Concerns\ToModel;

class Partnerimport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Partner([
            
        ]);
    }
}
