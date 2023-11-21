<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AccNilaiRequest;
use App\Models\RegisterMbkm;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class AccNilaiCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class AccNilaiCrudController extends CrudController
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
        CRUD::setRoute(config('backpack.base.route_prefix') . '/acc-nilai');
        CRUD::setEntityNameStrings('acc nilai', 'acc nilais');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
     $this->crud->setColumns([
        'id', 'mbkm.program_name', 'student.name'
     ]);
     CRUD::addButtonFromView('line', 'detailnilai', 'detailnilai', 'beginning');

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
        CRUD::setValidation(AccNilaiRequest::class);



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

    public function nilai(){
        // $id_kaprodi = backpack_auth()->user();
        // $nilai = DB::table('reg_mbkms')
        // ->select('reg_mbkms.id as reg_mbkms_id', 'mbkms.program_name as mbkm_name', 'students.name as students_name', 'reg_mbkms.status')
        // ->join('students', 'reg_mbkms.student_id', '=', 'students.id')
        // ->join('mbkms', 'reg_mbkms.mbkm_id', '=', 'mbkms.id')
        // ->whereNotNull('reg_mbkms.id')
        // ->whereNotNull('mbkms.id')
        // ->where('reg_mbkms.status', 'done')
        // ->get();

        $nilai =DB::table('reg_mbkms')
        ->select('reg_mbkms.id as ID', 'mbkms.program_name as Program_name', 'students.name as mahasiswa')
        ->Join('mbkms', 'reg_mbkms.mbkm_id', '=', 'mbkms.id')
        ->Join('students', 'reg_mbkms.student_id', '=', 'students.id')
        ->get();
        // dd($nilai);
        $crud = $this->crud;
        return view('vendor/backpack/crud/nilai', compact('crud', 'nilai'));
    }

    public function detailnilai($id)
    {
        $result = DB::table('students')
        ->select('reg_mbkms.id as id', 'students.name as nama_mahasiswa', 'courses.name as matkul', 'involved_course.grade as nilai', 'reg_mbkms.konfirmasi_nilai as setuju')
        ->join('reg_mbkms', 'students.id', '=', 'reg_mbkms.student_id')
        ->join('involved_course', 'reg_mbkms.id', '=', 'involved_course.reg_mbkm_id')
        ->join('courses', 'involved_course.course_id', '=', 'courses.id')
        ->whereNotNull('students.name')
        ->whereNotNull('involved_course.grade')
        ->where('reg_mbkms.id', $id)
        ->get();
         dd($result);
        $crud = $this->crud;
        return view('vendor/backpack/crud/nilaidetail', compact('result','crud'));
// show a form that does something
}

public function updateApproved($id, $approval){
    // Memperbarui status berdasarkan nilai $approval
    RegisterMbkm::where('id', $id)->update(['konfirmasi_nilai' => $approval]);
    // RegisterMbkm::where('id', $id)->where('tolak', null)->delete();
    return redirect()->back()->with('success', 'Berhasil diterima dan diterima.');
}

public function tolak(Request $request, $id, $not_aprroval){
    // dd($request);
    // dd($id, $not_aprroval);
//  $result = RegisterMbkm::find($id);
 $result = RegisterMbkm::where('id',$id)->update(['konfirmasi_nilai'=>$not_aprroval, 'tolak'=>$request->deskripsi]);

 $crud = $this->crud;
//  return response()->json(['message' => 'Revisi berhasil disimpan.']);

 return redirect()->back();
}
}

