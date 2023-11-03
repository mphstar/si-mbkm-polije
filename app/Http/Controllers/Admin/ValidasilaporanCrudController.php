<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ValidasilaporanRequest;
use App\Models\Mbkm;
use App\Models\MbkmReport;
use App\Models\RegisterMbkm;
use App\Models\Validasilaporan;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Prologue\Alerts\Facades\Alert;

/**
 * Class ValidasilaporanCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ValidasilaporanCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
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
        CRUD::setModel(\App\Models\RegisterMbkm::class);
        // CRUD::setModel(\App\Models\RegisterMbkm::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/validasilaporan');
        CRUD::setEntityNameStrings('validasilaporan', 'validasilaporans');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->setColumns(['student.name','mbkm.program_name', 'mbkm.info']);
        $this->crud->addButtonFromView('line', 'detail_laporan', 'detail_laporan', 'beginning');
        
     
        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']); 
         */
    }
    public function detail_laporan($id) 
{
    $regmbkm = RegisterMbkm::where('id', $id)->get();
    $mbkmId = RegisterMbkm::with('mbkm')
    ->where('student_id', $regmbkm[0]->student_id)
    ->where('status',  'accepted')
    ->whereHas('mbkm', function ($query) {
        $now = Carbon::now();
        $query->whereDate('start_date', '<=', $now)
              ->whereDate('end_date', '>=', $now);
    })->orderBy('id', 'desc')->get();
    $laporan=MbkmReport::where('reg_mbkm_id',$id)->get();
    $acceptedCount = $laporan->where('status', 'accepted')->count();
    $targetCount = Mbkm::where('id', $mbkmId[0]->mbkm_id)->value('task_count');
    if ($laporan->isEmpty()) {
    $count=0;
    }elseif ($acceptedCount==0) {
        $count="0";
    }else{
        $count = ($acceptedCount / $targetCount) * 100;
        // return dd($count);
     
    }
    $today = Carbon::now()->toDateString();

    $crud = $this->crud;
    return view('vendor.backpack.crud.detail_laporanreg', compact( 'crud','laporan','count','today'));
// show a form that does something
}
    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(ValidasilaporanRequest::class);

        

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number'])); 
         */
    }
public function validasilaporan(Request $request) {
    
    $data = [
        'status' => $request->input('status'),
        'notes' => $request->input('notes')
        // tambahkan kolom lain sesuai kebutuhan

        
    ]; 
    $id=  $request->input('id');
    Validasilaporan::where('id', $id)->update($data);
    Alert::success('Berhasil Validasi Laporan')->flash();
    return back();
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
            'name' => 'status',
            'type' => 'select_from_array',
            'label' => 'Status ACC',
            'options' => ['accepted' => 'Accepted', 'rejected' => 'Rejected', 'pending' => 'Pending'],
           
        ]);
        $this->crud->addField([
            'name' => 'notes',
            'type' => 'text',
            'label' => "Masukkan Nama mitra"
          ]);
    }
}
