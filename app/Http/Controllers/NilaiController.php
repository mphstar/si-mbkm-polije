<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Nilaimbkm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Prologue\Alerts\Facades\Alert;

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

        return view('custom_view.cetak_nilai', [
            "data" => $data,
            "jurusan" => $resJurusan,
            "prodi" => $resProdi,
        ]);
    }

    public function unduhtemplate($nama)
    {
        // dd($nama);
        // Cari data template berdasarkan nama file
        $DataTemplate = DB::table('template')
            ->where('nama', $nama)
            ->first();
        // dd($DataTemplate);
        if ($DataTemplate) {
            try {
                //code...
                $filePath = 'uploads/' . $DataTemplate->file;

                // Mendapatkan nama asli file
                $originalName = pathinfo($DataTemplate->file, PATHINFO_FILENAME);

                // Membangun response untuk mengirimkan file ke pengguna
                return response()->download(storage_path("app/{$filePath}"), "{$originalName}.pdf");
            } catch (\Throwable $th) {
                Alert::error('Gagal download', 'Gagal')->flash();
                return back();
            }
        } else {
            // Handle case when template data is not found
            return response('File not found', 404);
        }
    }
}
