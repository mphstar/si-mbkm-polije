<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ProgressMahasiswaRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ProgressMahasiswaCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ProgressMahasiswaCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Nilaimbkm::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/progress-mahasiswa');
        CRUD::setEntityNameStrings('progress mahasiswa', 'progress mahasiswas');
        $this->crud->addColumns([
            [
                "name" => "lecturers.lecturer_name",
                "label" => "Nama Dosen"
            ],
            [
                "name" => "students.name",
                "label" => "Nama Mahasiswa"
            ],
            [
                "name" => "mbkm.program_name",
                "label" => "Nama Program"
            ],
        ]);

        $this->crud->addClause('where', 'pembimbing', '!=', 'null');

        $level = backpack_auth()->user()->level;
        if($level == 'dospem'){
            $id_dosen = backpack_auth()->user()->with('lecturer')->whereHas('lecturer', function ($query) {
                return $query->where('users_id', backpack_auth()->user()->id);
            })->first();
    
            $this->crud->addClause('whereHas', 'lecturers', function($query) use($id_dosen){
                return $query->where('users_id', $id_dosen->id);
            });
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
        $this->crud->addButtonFromModelFunction('line', 'lihatProgress', 'lihatProgress', 'beginning');

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
        CRUD::setValidation(ProgressMahasiswaRequest::class);

        

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
