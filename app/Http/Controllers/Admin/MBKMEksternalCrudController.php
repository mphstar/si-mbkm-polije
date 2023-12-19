<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\MBKMEksternalRequest;
use App\Models\JenisMbkm;
use App\Models\ManagementMBKM;
use App\Models\MBKMEksternal;
use App\Models\Partner;
use App\Models\PengajuanEXTR;
use App\Models\PengajuanEXTRSub;
use App\Models\RegisterMbkm;
use App\Models\Students;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Prologue\Alerts\Facades\Alert;

/**
 * Class MBKMEksternalCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class MBKMEksternalCrudController extends CrudController
{
    // use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    // use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    // use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    // use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    // use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\MBKMEksternal::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/m-b-k-m-eksternal');
        CRUD::setEntityNameStrings('m b k m eksternal', 'MBKM Luar');
    }

    public function daftareksternal()
    {
        // $id_student = backpack_auth()->user()->with('partner')->whereHas('partner', function($query){
        //         return $query->where('users_id', backpack_auth()->user()->id);
        //     })->first();
        $id = backpack_auth()->user()->id;
        $today = Carbon::now()->toDateString();
        $siswa = Students::where('users_id', $id)->value('id');
        $data_sis = Students::where('users_id', $id)->first();
        $jenjang = substr($data_sis->nim, 1, 1); // mengambil jenjang


        $crud = $this->crud;
        $jenis_mbkm = JenisMbkm::where('kategori_jenis', '=', 'external')->get();

        // $mbkm = ManagementMBKM::with(['departmen', 'jenismbkm','partner'])->whereHas('jenismbkm', function($query) {
        //     return $query->where('jenismbkm.kategori_jenis', '=', 'external');
        // })->where("end_reg",'>',$today)->get();

        //kode diabawah merupakan menampilkan data pengajuan progra mbkm di tabel pengajuan sementara
        $extenal_sementara = PengajuanEXTR::with(['jenismbkm', 'student'])->whereHas('jenismbkm', function ($query) {
            return $query->where('kategori_jenis', '=', 'external');
        })->where('student_id', $siswa)->get();
        $crud = $this->crud;
        if ($jenjang == 4) {
            if ($data_sis->semester < 5) {
                dd("tol");
                session()->flash('semester', 'error');
                Alert::error('Maaf Anda Belum Bisa Daftar MBKM Eksternal')->flash();
                return back();
            } else {
                return view('vendor/backpack/crud/mbkbmeksternal', compact('crud', 'siswa', 'id', 'extenal_sementara', 'jenis_mbkm'));
            }
        }elseif ($jenjang == 3) {
  
        
            if ($data_sis->semester < 3) {
               
                session()->flash('semester', 'error');
                Alert::error('Maaf Anda Belum Bisa Daftar MBKM Eksternal')->flash();
                return back();
            } else {
                return view('vendor/backpack/crud/mbkbmeksternal', compact('crud', 'siswa', 'id', 'extenal_sementara', 'jenis_mbkm'));
            }
        }
    }

    public function regexternal()
    {


        $id = backpack_auth()->user()->id;
        $today = Carbon::now()->toDateString();
        $siswa = Students::where('users_id', $id)->first();
        $cekdireg_acp = RegisterMbkm::where('student_id', $siswa->id)->whereIn('status', ['accepted', 'pending'])->get(); //mengecek select di reg mbkm apakah user sudah terdaftar dan statsunya masih acepetd
        $pengajuan = PengajuanEXTR::with(['detail_sementara', 'student'])->whereHas('detail_sementara', function ($query) {
            return $query->where('status', '=', 'diambil');
        })->where('student_id', $siswa->id)->where('semester', $siswa->semester + 1)->get();

        if (count($pengajuan) != 0) {
            $messages = "Maaf Anda pernah daftar  Pada Salah satu Program MBKM sebelumnya,silahkan daftar lagi semester berikutnya";

            Alert::error($messages)->flash();
            return redirect(backpack_url('m-b-k-m-eksternal'));
        } else {
            $crud = $this->crud;
            $jenis_mbkm = JenisMbkm::where('kategori_jenis', '=', 'external')->get();
            $partner = Partner::where('jenis_mitra', '=', 'luar kampus')->get();

            return view('vendor/backpack/crud/regmbkmeks', compact('crud', 'siswa', 'id', 'jenis_mbkm', 'partner'));
        }
    }
    public function storeData(Request $request)
    {

        // $cek=RegisterMbkm::where('student_id',$request->student_id)->whereIn("status",['accepted','pending'])->first();
        // if ($cek) {
        //     $messages ="Anda masih proses daftar atau sudah terdaftar dalam mbkm";
        //     Alert::warning($messages)->flash();
        //     return back()->withInput();
        // }else{



        $validator = Validator::make($request->all(), [
            'id_jenis' => 'required',
            'file_surat' => 'required|file|mimes:pdf|max:10000',
            'partner_id.*' => 'required',
            'nama_program.*' => 'required',
        ]);

        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            Alert::warning($messages[0])->flash();
            return back()->withInput();
        }
        $semester_dep = backpack_auth()->user()->student->semester + 1;
        $input = [
            "student_id" => $request->input("student_id"),
            "semester" => $semester_dep,
            "id_jenis" => $request->input("id_jenis"),
            'file_surat' => $request->file('file_surat')->getClientOriginalName()
        ];

        $fileName = time() . '.' . $request->file('file_surat')->getClientOriginalExtension();
        $request->file('file_surat')->move(public_path('storage/uploads'), $fileName);
        $input['file_surat'] = "storage/uploads/$fileName";

        $user = PengajuanEXTR::create($input);

        $partner_ids = $request->input('partner_id');
        $nama_programs = $request->input('nama_program');

        if (is_array($partner_ids) && is_array($nama_programs) && count($partner_ids) === count($nama_programs)) {
            $count = count($partner_ids);
            for ($i = 0; $i < $count; $i++) {
                $detailData = new PengajuanEXTRSub();
                $detailData->exmbkm_id = $user->id;
                $detailData->nama_program = $nama_programs[$i];
                $detailData->partner_id = $partner_ids[$i];
                $detailData->save();
            }
        } else {
            Alert::error('Data tidak valid.')->flash();
            return back()->withInput();
        }

        Alert::success('Berhasil daftar!')->flash();
        return redirect(backpack_url('m-b-k-m-eksternal'));
    }


    public function ambileks(Request $request)
    {

        if (($request->status == "diterima") || ($request->status == "ditolak")) {

            $status = [
                "status" => $request->input("status")
            ];
            PengajuanEXTRSub::where('id', $request->input('id'))->update($status);
            Alert::success('Berhasil Mengubah Status!')->flash();
            return back()->withInput();
        } else {

            $validator = Validator::make($request->all(), [

                'file_diterima' => 'required|file|mimes:pdf|max:10000',
                'status' => 'required'

            ]);
            if ($validator->fails()) {
                $messages = $validator->errors()->all();
                Alert::warning($messages[0])->flash();
                return back()->withInput();
            }

            $detail = [
                "status" => $request->input("status"),

                'file_diterima' => $request->file('file_diterima')->getClientOriginalName()
            ];
            $fileName = time() . '.' . $request->file('file_diterima')->getClientOriginalExtension();
            $request->file('file_diterima')->move(public_path('storage/uploads'), $fileName);
            $detail['file_diterima'] = "storage/uploads/$fileName";

            $up_bukti = PengajuanEXTRSub::where('id', $request->input('id'))->update($detail);
            $semster_depan = backpack_auth()->user()->student->semester + 1;
            $reg_mbkmeks = [
                "program_name" => $request->input('nama_program'),
                "id_jenis" => $request->input('id_jenis'),
                "partner_id" => $request->input('partner_id'),
                "student_id" => $request->input('student_id'),
                "status" => "accepted",
                "semester" => $semster_depan
            ];



            RegisterMbkm::create($reg_mbkmeks);
            Alert::success('Berhasil mengambil program!')->flash();

            return redirect(backpack_url('m-b-k-m-eksternal'));
        }
    }
    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {


        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(MBKMEksternalRequest::class);



        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number']));
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
