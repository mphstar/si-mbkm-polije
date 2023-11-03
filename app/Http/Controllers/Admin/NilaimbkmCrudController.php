<?php

namespace App\Http\Controllers\Admin;

use App\Course;
use App\Http\Requests\NilaimbkmRequest;
use App\InvolvedCourse;
use App\Models\Nilaimbkm;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Http\Request;
use Prologue\Alerts\Facades\Alert;

/**
 * Class NilaimbkmCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class NilaimbkmCrudController extends CrudController
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
        CRUD::setRoute(config('backpack.base.route_prefix') . '/nilaimbkm');
        CRUD::setEntityNameStrings('nilaimbkm', 'Nilai MBKM');
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
        $this->crud->addClause('where', 'nilai_mitra', '!=', 'null');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->addButtonFromModelFunction('line', 'input_nilai', 'input_nilai', 'beginning');
        $this->crud->addButtonFromModelFunction('line', 'download_nilai', 'download_nilai', 'beginning');


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
        CRUD::setValidation(NilaimbkmRequest::class);



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

    protected function inputNilai($id)
    {
        $crud = $this->crud;
        $data = Nilaimbkm::with(['involved.course'])->where('id', $id)->first();
        // return $data;
        return view('vendor.backpack.crud.inputNilai', compact('crud', 'data'));
    }

    protected function prosesNilai(Request $request)
    {
        $data = $request->all();

        if (isset($data['_token'])) {
            unset($data['_token']);
        }

        foreach ($data as $key => $value) {
            InvolvedCourse::updateOrInsert(['reg_mbkm_id' => $key], ['grade' => $value]);
        }

        Alert::success('Berhasil Menyimpan')->flash();
        return redirect()->back();
    }
}
