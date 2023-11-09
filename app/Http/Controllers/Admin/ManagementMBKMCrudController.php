<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ManagementMBKMRequest;
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
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
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
        CRUD::setEntityNameStrings('management MBKM', 'management MBKMS');

        $id_partner = backpack_auth()->user()->with('partner')->whereHas('partner', function($query){
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

        $this->crud->setColumns([[
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
            'name'  => 'status_acc',
            'label' => 'Status ACC', // Table column heading
            'type'  => 'model_function',
            'function_name' => 'getStatusSpan'
        ], [
            'name'  => 'is_active',
            'label' => 'Status Active', // Table column heading
            'type'  => 'model_function',
            'function_name' => 'getIsactiveSpan'
        ],]);
        // return backpack_auth()->user()->with('partner')->whereHas('partner', function($query){
        //     return $query->where('users_id', backpack_auth()->user()->id);
        // })->first();
        $this->crud->addButtonFromView('top', 'tambah_mbkm', 'tambah_mbkm', 'beginning');
        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']); 
         */
    }
    public function tambah_mbkm()
    {
        $crud = $this->crud;

        $mitra =  backpack_auth()->user()->with('partner')->whereHas('partner', function($query){
            return $query->where('users_id', backpack_auth()->user()->id);
        })->first();;

        $id_partner = backpack_auth()->user()->with('partner')->whereHas('partner', function($query){
            return $query->where('users_id', backpack_auth()->user()->id);
        })->first();

        if($id_partner->partner->status == 'pending'){
            Alert::warning('Aktifasi akun terlebih dahulu')->flash();
            return redirect()->back();
        }


        return view('vendor/backpack/crud/view_tambahmbkm', compact('mitra', 'crud', 'id_partner'));
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
        $this->crud->addField([
            'name' => 'partner_id', // Nama kolom dalam tabel "MBKM" yang akan menyimpan ID mitra
            'label' => 'Pilih Mitra',
            'type' => 'select',
            'entity' => 'partner', // Nama relasi dalam model "MBKM"
            'attribute' => 'partner_name', // Atribut yang ingin ditampilkan dalam combo box
            'model' => 'App\Models\Partner', // Model yang digunakan untuk mendapatkan data mitra
        ]);
     
        $this->crud->addfield([
            'name' => 'program_name',
            'label' => 'Nama Program',
        ]);
        $this->crud->addfield([
            'name' => 'start_date',
            'label' => 'Tanggal Mulai program MBKM',
        ]);
        $this->crud->addfield([
            'name' => 'end_date',
            'label' => 'Tanggal Selesai Program MBKM ',
        ]);
        $this->crud->addfield([
            'name' => 'start_reg',
            'label' => 'Tanggal awal pendaftaran',
        ]);
        $this->crud->addfield([
            'name' => 'end_reg',
            'label' => 'Tanggal terakhir pendaftaran',
        ]);
        $this->crud->addfield([
            'name' => 'info',
            'label' => 'Keterangan MBKM'
        ]);
        $this->crud->addfield([
            'name' => 'semester',
            'label' => 'Berlaku Bagi semester'
        ]);
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
