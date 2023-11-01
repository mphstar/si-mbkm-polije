<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\MbkmRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Models\Mbkm;
use App\Models\RegisterMbkm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

/**
 * Class MbkmCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class MbkmCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    // use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    // use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
private function getFieldsData()  {
    
}
    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Mbkm::class);
        CRUD::setRoute('admin/mbkm');
        CRUD::setEntityNameStrings('MBKM', 'Program MBKM');
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
            'name' => 'partner.partner_name',
            'label' => 'Nama Mitra',
        ], [
            'name' => 'start_date',
            'label' => 'Tanggal Mulai',
        ], [
            'name' => 'end_date',
            'label' => 'Tanggal Selesai',
        ],[
            'name' => 'capacity',
            'label' => 'Kuota',
        ], 'info']);
        $this->crud->addColumn([
            'name'  => 'status_acc',
   'label' => 'Status ACC', // Table column heading
   'type'  => 'model_function',
   'function_name' => 'getStatusSpan'
        
        ])->afterColumn('capacity');
        $this->crud->addColumn([
            'name'  => 'is_active',
   'label' => 'Status Active', // Table column heading
   'type'  => 'model_function',
   'function_name' => 'getIsactiveSpan'
        
        ])->afterColumn('status_acc');
        $this->crud->addButtonFromView('line', 'reg_mbkm', 'reg_mbkm', 'end');

        CRUD::addClause('where', 'capacity', '>', '0');
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
        CRUD::setValidation(MbkmRequest::class);

        // $this->crud->addField([
        //     'name' => 'partner_id', // Nama kolom dalam tabel "MBKM" yang akan menyimpan ID mitra
        //     'label' => 'Pilih Mitra',
        //     'type' => 'select',
        //     'entity' => 'partner', // Nama relasi dalam model "MBKM"
        //     'attribute' => 'partner_name', // Atribut yang ingin ditampilkan dalam combo box
        //     'model' => 'App\Models\Partner', // Model yang digunakan untuk mendapatkan data mitra
        // ]);
        // $this->crud->addField([
        //     'name' => 'program_name', // Nama kolom dalam tabel "MBKM" yang akan menyimpan ID mitra
        //     'label' => 'Masukkan Nama Program',
        //     'type' => 'text',
        // ]);
        // $this->crud->addField([
        //     'name' => 'capacity',
        //     'type' => 'number',
        //     'label' => "Masukkan Kapasitas mbkm"
        //   ]);
        //   $this->crud->addField([
        //       'name' => 'start_date',
        //       'type' => 'date',
        //       'label' => "Masukkan tanggal awal mbkm"
        //     ]);
            
        //     $this->crud->addField([
        //         'name' => 'end_date',
        //         'type' => 'date',
        //         'label' => "Masukkan tanggal awal mbkm"
        //     ]);
        //     $this->crud->addField([
        //         'name' => 'info',
        //         'type' => 'text',
        //         'label' => "Masukkan keterangan mbkm"
        //       ]);
        
        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number'])); 
         */
    }
    public function register($id) 
    {
        $user = backpack_auth()->user();
        $accReg = RegisterMbkm::where('student_id', backpack_auth()->user()->id)->where('status',  'accepted')->get();
        $pendingReg = RegisterMbkm::where('student_id', backpack_auth()->user()->id)->where('status', 'pending', )->get();
        // return dd($sudahReg);
        if($accReg->count() > 0){
            \Alert::error('Anda sudah daftar')->flash();
            return back();
        }
        if($pendingReg->count() > 0){
            \Alert::warning('Anda tidak dapat mendaftar jika status pendaftaran sebelumnya masih pending')->flash();
            return back();
        }
        $mbkm = Mbkm::with('partner')->where('mbkms.id', $id)->get();
        $crud = $this->crud;
        return view('vendor.backpack.crud.register_mbkm', compact('mbkm', 'crud', 'user'));
    }

    public function addreg(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:zip,rar'
        ]);

        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            \Alert::error($messages[0])->flash();
            return back()->withInput();
        }
        $input = $request->all();

        $file = $request->file('file')->getClientOriginalName();
        $fileName = time().'.'.$request->file('file')->getClientOriginalExtension();
 
        $request->file('file')->move(public_path('storage/uploads'), $fileName);
        $input['requirements_files'] = "storage/uploads/$fileName";
        $user = RegisterMbkm::create($input);
        \Alert::success('Berhasil Mendaftar!')->flash();
        return redirect('admin/mbkm');
    }
    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    // protected function setupUpdateOperation()
    // {
    //     $this->setupCreateOperation();
    // }
    protected function setupShowOperation()
    {
        // by default the Show operation will try to show all columns in the db table,
        // but we can easily take over, and have full control of what columns are shown,
        // by changing this config for the Show operation 
        $this->crud->set('show.setFromDb', false);

      
 
        $this->crud->addColumn( [
            'name' => 'partner.partner_name',
            'label' => 'Nama Mitra',
        ]);
        $this->crud->addColumn( [
            'name' => 'program_name',
            'label' => 'Nama Program',
        ]);
        $this->crud->addColumn( [
            'name' => 'start_date',
            'label' => 'Tanggal Mulai program MBKM',
        ]);
        $this->crud->addColumn( [
            'name' => 'end_date',
            'label' => 'Tanggal Selesai Program MBKM ',
        ]);
        $this->crud->addColumn( [
            'name' => 'start_reg',
            'label' => 'Tanggal awal pendaftaran',
        ]);
        $this->crud->addColumn( [
            'name' => 'end_reg',
            'label' => 'Tanggal terakhir pendaftaran',
        ]);
        $this->crud->addColumn( [
            'name' => 'info',
            'label' => 'Keterangan MBKM'
        ]);
        $this->crud->addColumn( [
            'name' => 'semester',
            'label' => 'Berlaku Bagi semester'
        ]);
        $this->crud->addColumn( [
            'name'  => 'status_acc',
            'label' => 'Status ACC', // Table column heading
            'type'  => 'model_function',
            'function_name' => 'getStatusSpan'
        ]);
        $this->crud->addColumn( [
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
