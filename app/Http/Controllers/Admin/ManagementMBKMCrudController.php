<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ClassApi;
use App\Http\Requests\ManagementMBKMRequest;
use App\Models\Departmen;
use App\Models\JenisMbkm;
use App\Models\ManagementMBKM;
use App\Models\Partner;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Prologue\Alerts\Facades\Alert;

/**
 * Class ManagementMBKMCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ManagementMBKMCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    // use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    // use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\ManagementMBKM::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/management-m-b-k-m');
        CRUD::setEntityNameStrings('Data MBKM', 'Data MBKM');

        $id_partner = backpack_auth()->user()->with('partner')->whereHas('partner', function ($query) {
            return $query->where('users_id', backpack_auth()->user()->id);
        })->first();

        $this->crud->addClause('where', 'partner_id', '=', $id_partner->partner->id);
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {

        $this->crud->setColumns([
            [
                'name' => 'program_name',
                'label' => 'Nama Program',
            ], [
                'name' => 'start_date',
                'label' => 'Tanggal Mulai',
            ], [
                'name' => 'end_date',
                'label' => 'Tanggal Selesai',
            ], [
                'name' => 'capacity',
                'label' => 'Kuota',
            ], [

                'name' => 'jenismbkm.jenismbkm',
                'label' => 'Jenis MBKM',
            ], [
                'name'  => 'status_acc',
                'label' => 'Status Akun', // Table column heading
                'type'  => 'model_function',
                'function_name' => 'getStatusSpan'
            ], [
                'name'  => 'is_active',
                'label' => 'Status Aktif', // Table column heading
                'type'  => 'model_function',
                'function_name' => 'getIsactiveSpan'
            ],
        ]);
        // $this->crud->addClause('join', 'departments', function ($join) {
        //     $join->on('mbkm.id_department', '=', 'jurusan.id');
        // });    // return backpack_auth()->user()->with('partner')->whereHas('partner', function($query){
        //     return $query->where('users_id', backpack_auth()->user()->id);
        // })->first();
        $this->crud->addButtonFromView('top', 'tambah_mbkm', 'tambah_mbkm', 'beginning');
        $this->crud->addButtonFromView('line', 'ubahmbkm', 'ubahmbkm', 'beginning');
        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']); 
         */
    }
    public function tambah_mbkm(Request $request)
    {
        $jenis_mbkm = JenisMbkm::where('kategori_jenis', 'internal')->get();
        $crud = $this->crud;

        $mitra =  backpack_auth()->user()->with('partner')->whereHas('partner', function ($query) {
            return $query->where('users_id', backpack_auth()->user()->id);
        })->first();;

        $id_partner = backpack_auth()->user()->with('partner')->whereHas('partner', function ($query) {
            return $query->where('users_id', backpack_auth()->user()->id);
        })->first();

        if ($id_partner->partner->status == 'pending') {
            Alert::warning('Aktifasi akun terlebih dahulu')->flash();
            return redirect()->back();
        }

        $api = new ClassApi;
        $jurusan = $api->getJurusan($request);

        session()->flash('status', 'success');
        return view('vendor/backpack/crud/view_tambahmbkm', compact('mitra', 'crud', 'id_partner', 'jurusan', 'jenis_mbkm'));
    }
    public function ubahmbkm($id, Request $request)
    {
        $crud = $this->crud;
        $api = new ClassApi;
        $jurusan = $api->getJurusan($request);
        $jenis_mbkm = JenisMbkm::where('kategori_jenis', 'internal')->get();

        $data = ManagementMBKM::with(['jenismbkm', 'departmen'])->where('id', $id)->first();
        return view('vendor.backpack.crud.ubahMBKM', compact('crud', 'jenis_mbkm', 'jurusan', 'data'));
    }
    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {




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



        // $this->crud->addField([
        //     'name' => 'id_jenis', // Nama kolom dalam tabel "MBKM" yang akan menyimpan ID jenis
        //     'label' => 'Pilih jenid MBKM',
        //     'type' => 'select',
        //     'entity' => 'jenismbkmitrn', // Nama relasi dalam model "MBKM"
        //     'attribute' => 'jenismbkm', // Atribut yang ingin ditampilkan dalam combo box
        //     'model' => ManagementMBKM::class

        // ]);
    }

    public function updatembkm(Request $request)
    {

        $data = [
            "jurusan" => $request->input("jurusan"),
            "program_name" => $request->input("program_name"),
            "capacity" => $request->input("capacity"),
            "start_date" => $request->input("start_date"),
            "end_date" => $request->input("end_date"),
            "start_reg" => $request->input("start_reg"),
            "end_reg" => $request->input("end_reg"),
            "task_count" => $request->input("task_count"),
            "semester" => $request->input("semester"),
            "nama_penanggung_jawab" => $request->input("nama_penanggung_jawab"),
            "jumlah_sks" => $request->input("jumlah_sks"),
            "info" => $request->input("info"),
            "id_jenis" => $request->input("id_jenis")
        ];

        $id = $request->input('id');
        ManagementMBKM::where('id', $id)->update($data);

        session()->flash('status', 'success');
        Alert::success('Berhasil ubah data')->flash();
        return redirect("admin/management-m-b-k-m");
    }
    public function storeData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'partner_id' => 'required',
            'program_name' => 'required',
            'capacity' => 'required',
            'task_count' => 'required',
            'semester' => 'required',
            'start_reg' => 'required',
            'end_reg' => 'required',
            'start_date' => 'required',
            'info' => 'required',
            'jumlah_sks' => 'required',
            'nama_penanggung_jawab' => 'required',
        ]);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            session()->flash('status', 'error');
            Alert::warning($messages[0])->flash();
            return back()->withErrors($validator)->withInput();
        }
        // Simpan data ke database
        ManagementMBKM::create($request->all());
        session()->flash('status', 'success');
        Alert::success('Berhasil Tambah data berhasil')->flash();
        return redirect("admin/management-m-b-k-m");
    }
    protected function setupShowOperation()
    {
        // by default the Show operation will try to show all columns in the db table,
        // but we can easily take over, and have full control of what columns are shown,
        // by changing this config for the Show operation 
        $this->crud->set('show.setFromDb', false);



        $this->crud->addColumn([
            'name' => 'partner.partner_name',
            'label' => 'Nama Mitra',
        ]);
        $this->crud->addColumn([
            'name' => 'program_name',
            'label' => 'Nama Program',
        ]);
        $this->crud->addColumn([
            'name' => 'start_date',
            'label' => 'Tanggal Mulai program MBKM',
        ]);
        $this->crud->addColumn([
            'name' => 'end_date',
            'label' => 'Tanggal Selesai Program MBKM ',
        ]);
        $this->crud->addColumn([
            'name' => 'start_reg',
            'label' => 'Tanggal awal pendaftaran',
        ]);
        $this->crud->addColumn([
            'name' => 'end_reg',
            'label' => 'Tanggal terakhir pendaftaran',
        ]);
        $this->crud->addColumn([
            'name' => 'info',
            'label' => 'Keterangan MBKM'
        ]);
        $this->crud->addColumn([
            'name' => 'semester',
            'label' => 'Berlaku Bagi semester'
        ]);
        $this->crud->addColumn([
            'name' => 'nama_penanggung_jawab',
            'label' => 'Nama Penanggung Jawab'
        ]);
        $this->crud->addColumn([
            'name' => 'jenismbkm.jenismbkm',
            'label' => 'Jenis MBKM'
        ]);
        $this->crud->addColumn([
            'name' => 'jenismbkm.jenismbkm',
            'label' => 'Jenis MBKM'
        ]);
        // $this->crud->addColumn([
        //     'name' => 'jurusan',
        //     'label' => 'Jurusan'
        // ]);

        $this->crud->addColumn([
            'name'  => 'jurusan',
            'label' => 'Jurusan', // Table column heading
            'type'  => 'model_function',
            'function_name' => "getTextJurusan"
        ]);


        $this->crud->addColumn([
            'name'  => 'status_acc',
            'label' => 'Status ACC', // Table column heading
            'type'  => 'model_function',
            'function_name' => 'getStatusSpan'
        ]);
        $this->crud->addColumn([
            'name'  => 'is_active',
            'label' => 'Status Active', // Table column heading
            'type'  => 'model_function',
            'function_name' => 'getIsactiveSpan'
        ]);

        // $this->crud->removeColumn('date');
        // $this->crud->removeColumn('extras');

        // Note: if you HAVEN'T set show.setFromDb to false, the removeColumn() calls won't work
        // because setFromDb() is called AFTER setupShowOperation(); we know this is not intuitive at all
        // and we plan to change behaviour in the next version; see this Github issue for more details
        // https://github.com/Laravel-Backpack/CRUD/issues/3108
    }
}
