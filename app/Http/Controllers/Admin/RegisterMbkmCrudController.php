<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\RegisterMbkmRequest;
use App\Mail\pesertadiacc;
use App\Mail\pesertaditolak;
use App\Models\RegisterMbkm;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\CRUD\app\Library\Validation\Rules\ValidUpload;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Prologue\Alerts\Facades\Alert;

/**
 * Class RegisterMbkmCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class RegisterMbkmCrudController extends CrudController
{
    // use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    // // use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
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
        CRUD::setRoute(config('backpack.base.route_prefix') . '/register-mbkm');
        CRUD::setEntityNameStrings('register mbkm', 'Validasi Peserta MBKM');

        $id_partner = backpack_auth()->user()->with('partner')->whereHas('partner', function ($query) {
            return $query->where('users_id', backpack_auth()->user()->id);
        })->first();

        $this->crud->addClause('where', 'mbkm.partner_id', '=', $id_partner->partner->id);
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->setColumns(['student.name', 'mbkm.info', [
            'name'  => 'status',
            'label' => 'Status ACC', // Table column heading
            'type'  => 'model_function',
            'function_name' => 'getStatusSpan'
        ]]);
        $this->crud->addButtonFromModelFunction('line', 'download', 'Download', 'beginning');
        
    }
    public function validasipendaftar()
    {
        $id_partner = backpack_auth()->user()->with('partner')->whereHas('partner', function ($query) {
            return $query->where('users_id', backpack_auth()->user()->id);
        })->first();



        $pendaftar = RegisterMbkm::with(['student', 'mbkm'])->whereHas('mbkm', function($query) use($id_partner){
            return $query->where('partner_id', '=', $id_partner->partner->id);
        })->get();

        // return $pendaftar;

        $crud = $this->crud;
        return view('vendor/backpack/crud/validasipeserta', compact('pendaftar', 'crud'));
    }

    public function validasipeserta(Request $request)
    {
        $data = [
            'status' => $request->input('status')
        ];
        $id =  $request->input('id');
        $userEmail = User::join('students', 'users.id', '=', 'students.users_id')
        ->join('reg_mbkms', 'students.id', '=', 'reg_mbkms.student_id')
        ->where('reg_mbkms.id', $id)
        ->value('users.email');
        $namaMBKM=RegisterMbkm::with('mbkm.partner')->where('id',$request->id)->first()->mbkm->program_name;
       
        RegisterMbkm::where('id', $id)->update($data);
        try {
                if ($request->input("status")==="accepted") {
                    Mail::to($userEmail)->send(new pesertadiacc($namaMBKM));
                }elseif($request->input("status")==="rejected"){
                    Mail::to($userEmail)->send(new pesertaditolak($namaMBKM));
            }
        } catch (\Throwable $th) {
            Alert::warning('gagal send email')->flash();
            }

     
     
     
     
        session()->flash('status', 'success');
        Alert::success('Berhasil Validasi Peserta')->flash();
        return back();
    }
    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(RegisterMbkmRequest::class);




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
            'name' => 'status',
            'type' => 'select_from_array',
            'label' => 'Status ACC',
            'options' => ["accepted" => 'Accepted', "rejected" => 'Rejected'],

        ]);
    }
}
