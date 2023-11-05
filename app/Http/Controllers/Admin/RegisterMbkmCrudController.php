<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\RegisterMbkmRequest;
use App\Models\RegisterMbkm;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\CRUD\app\Library\Validation\Rules\ValidUpload;
use Illuminate\Http\Request;
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

        $this->crud->addClause('where', 'mbkm.partner_id', '=', $id_partner);
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
        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']); 
         */
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
            // tambahkan kolom lain sesuai kebutuhan


        ];
        $id =  $request->input('id');
        RegisterMbkm::where('id', $id)->update($data);
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
