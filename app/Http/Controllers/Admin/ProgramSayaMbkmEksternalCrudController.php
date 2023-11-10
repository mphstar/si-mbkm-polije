<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ProgramSayaMbkmEksternalRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ProgramSayaMbkmEksternalCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ProgramSayaMbkmEksternalCrudController extends CrudController
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
        CRUD::setRoute(config('backpack.base.route_prefix') . '/mbkm-eksternal');
        CRUD::setEntityNameStrings('program saya mbkm eksternal', 'Program Saya');
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
            'name' => 'nama_mitra',
            'label' => 'Nama Mitra',
        ],[
            'name' => 'jenis_mbkm',
            'label' => 'Jenis MBKM',
        ], [
            'name' => 'semester',
            'label' => 'Semester',
        ],[
            'name'  => 'status',
            'label' => 'Status', // Table column heading
            'type'  => 'model_function',
            'function_name' => 'getStatusSpan'
        ],]);

        $id_student = backpack_auth()->user()->with('student')->whereHas('student', function ($query) {
            return $query->where('users_id', backpack_auth()->user()->id);
        })->first();

        CRUD::addClause('where', 'student_id', '=', $id_student->student->id);
        CRUD::addClause('where', 'mbkm_id', '=', null);

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
        CRUD::setValidation(ProgramSayaMbkmEksternalRequest::class);

        

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
