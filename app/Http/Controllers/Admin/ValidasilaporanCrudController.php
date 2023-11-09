<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ValidasilaporanRequest;
use App\Mail\laporanditerima;
use App\Mail\laporanrevisi;
use App\Models\ManagementMBKM;
use App\Models\Mbkm;
use App\Models\MbkmReport;
use App\Models\RegisterMbkm;
use App\Models\Validasilaporan;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
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

        $id_partner = backpack_auth()->user()->with('partner')->whereHas('partner', function ($query) {
            return $query->where('users_id', backpack_auth()->user()->id);
        })->first();

        $this->crud->addClause('where', 'status', '!=', 'pending');
        $this->crud->addClause('whereHas', 'mbkm', function ($query) use ($id_partner) {
            return $query->where('partner_id', $id_partner->partner->id);
        });
        $this->crud->addClause('where', 'status', 'accepted');
        CRUD::setEntityNameStrings('validasilaporan', 'validasi Laporan Mahasiswa');
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
            'name' => 'mbkm.program_name',
            'label' => 'Program MBKM',
        ], [
            'name' => 'student.name',
            'label' => 'Nama Mahasiswa',
        ], [
            'name' => 'mbkm.info',
            'label' => 'Informasi MBKM',
        ]]);
      
        $this->crud->addButtonFromView('line', 'detail_laporan', 'detail_laporan', 'beginning');


        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']); 
         */
    }
    public function detail_laporan($id)
    {
        // $regmbkm = RegisterMbkm::where('id', $id)->get();
        $mbkmId = RegisterMbkm::with('mbkm')
            ->where('id', $id)
            ->where('status',  'accepted')
            ->whereHas('mbkm', function ($query) {
                $now = Carbon::now();
                $query->whereDate('start_date', '<=', $now)
                    ->whereDate('end_date', '>=', $now);
            })->orderBy('id', 'desc')->first();
        
        $laporan = MbkmReport::where('reg_mbkm_id', $id)->get();
        $acceptedCount = $laporan->where('status', 'accepted')->count();

        // dd($mbkmId);
        $targetCount = Mbkm::where('id', $mbkmId->mbkm_id)->value('task_count');

        if ($laporan->isEmpty()) {
            $count = 0;
        } elseif ($acceptedCount == 0) {
            $count = "0";
        } else {
            $count = ($acceptedCount / $targetCount) * 100;
            // return dd($count);

        }
        $today = Carbon::now()->toDateString();

        $crud = $this->crud;
        return view('vendor.backpack.crud.detail_laporanreg', compact('crud', 'laporan', 'count', 'today'));
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
    public function validasilaporan(Request $request)
    {

        $data = [
            'status' => $request->input('status'),
            'notes' => $request->input('notes')
            // tambahkan kolom lain sesuai kebutuhan


        ];
        $dataa=Validasilaporan::with(['regMbkm.student.users'])->where('id',$request->id)->first();
       
        $id =  $request->input('id');
        Validasilaporan::where('id', $id)->update($data);
        if ($request->input("status")==="accepted") {
            Mail::to($dataa->regMbkm->student->users->email)->send(new laporanditerima($dataa));
        }elseif($request->input("status") === "rejected"){
            Mail::to($dataa->regMbkm->student->users->email)->send(new laporanrevisi($dataa));
        }
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
