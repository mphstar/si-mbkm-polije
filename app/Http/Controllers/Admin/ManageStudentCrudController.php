<?php

namespace App\Http\Controllers\Admin;

use App\Course;
use App\Http\Requests\ManageStudentRequest;
use App\InvolvedCourse;
use App\Models\Lecturer;
use App\Models\Nilaimbkm;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Prologue\Alerts\Facades\Alert;

/**
 * Class ManageStudentCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ManageStudentCrudController extends CrudController
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
        CRUD::setRoute(config('backpack.base.route_prefix') . '/manage-student');
        CRUD::setEntityNameStrings('manage student', 'manage students');
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

        $this->crud->addClause('where', 'status', '=', 'done');
        // $this->crud->addClause('where', 'nilai_mitra', '!=', 'null');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->addButtonFromModelFunction('line', 'manageStudent', 'manageStudent', 'beginning');


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
        CRUD::setValidation(ManageStudentRequest::class);



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

    protected function formEdit($id)
    {
        $crud = $this->crud;
        $dosen = Lecturer::all();

        $data = Nilaimbkm::with(['involved.course.semester', 'students.program_study', 'mbkm'])->where('id', $id)->first();
        $course = Course::where('program_id', $data->students->study_program_id)->whereHas('semester', function ($query) use ($data) {
            return $query->where('semester', $data->mbkm->semester);
        })->get();

        // return $data;



        return view('vendor.backpack.crud.editManageStudent', compact('crud', 'dosen', 'data', 'course'));
    }

    protected function editDosen(Request $request, $id)
    {
        Nilaimbkm::where('id', $id)->update([
            "pembimbing" => $request->dosen
        ]);

        Alert::success('Berhasil Menyimpan')->flash();
        return redirect('/admin/manage-student');
    }

    protected function editMatkul(Request $request, $id)
    {
        
        InvolvedCourse::where('reg_mbkm_id', $id)->delete();
        if ($request->ids) {
            
            # code...
            for ($i = 0; $i < count($request->ids); $i++) {
                InvolvedCourse::create([
                    "reg_mbkm_id" => $id,
                    "course_id" => $request->ids[$i]
                ]);
            }
        }



        Alert::success('Berhasil Menyimpan')->flash();
        return redirect('/admin/manage-student');
    }
}
