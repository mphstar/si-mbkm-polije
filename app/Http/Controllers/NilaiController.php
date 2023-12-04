<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Nilaimbkm;
use Illuminate\Http\Request;

class NilaiController extends Controller
{
    public function cetak($id){
        $data = Nilaimbkm::with(['students', 'involved.course', 'mbkm', 'partner', 'lecturers'])->where('id', $id)->first();
        $ap = new ClassApi;
        $jurusan = $ap->getJurusan(request());
        $prodi = $ap->getProdi(request());

        $resJurusan = '-';
        $resProdi = '-';

        foreach ($jurusan as $key => $value) {
            if($value->uuid == $data->students->jurusan){
                $resJurusan = $value->unit;
                break;
            }
        }
        foreach ($prodi as $key => $value) {
            if($value->uuid == $data->students->program_studi){
                $resProdi = $value->unit;
                break;
            }
        }

        // return $resJurusan;

        // return $data;
        return view('custom_view.cetak_nilai', [
            "data" => $data,
            "jurusan" => $resJurusan,
            "prodi" => $resProdi,
        ]);
    }
}
