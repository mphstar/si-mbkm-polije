<?php

namespace App\Http\Controllers\Admin;

use App\Course;
use App\Http\Controllers\ClassApi;
use App\Http\Requests\ManageStudentRequest;
use App\InvolvedCourse;
use App\Models\Lecturer;
use App\Models\Nilaimbkm;
use App\Models\RegisterMbkm;
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
                "label" => "Nama Program",
                'type' => 'model_function',
                'function_name' => 'getNamaProgram'
            ],
        ]);

        $this->crud->addClause('where', 'status', '=', 'accepted');
        $user = backpack_auth()->user();
        
        $this->crud->addClause('whereHas', 'students', function($query) use ($user){
            return $query->where('jurusan', $user->lecturer->jurusan);
        });

        // dd($user->lecturer);
        // dd('dwadwa');
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
    public function riwayatmhs_mbkminternal(){
        $crud = $this->crud;
        $datakap = backpack_auth()->user();
        // $datakap = backpack_auth()->user()->with('lecturer')->whereHas('lecturer', function ($query) {
        //     return $query->where('users_id', backpack_auth()->user()->id);
        // })->first();
        $datamhs=RegisterMbkm::with(['lecturer','mbkm','student'])->whereHas('student', function($query) use ($datakap){
            return $query->where('program_studi', $datakap->lecturer->program_studi);
        })->where('status','done')->where('program_name',null)->get();

        return view('vendor/backpack/crud/riwayatmhs_mbkminternal', compact('datamhs', 'crud'));
    }
    public function riwayatmhs_mbkmeksternal(){
        $crud = $this->crud;
        $datakap = backpack_auth()->user();
        // $datakap = backpack_auth()->user()->with('lecturer')->whereHas('lecturer', function ($query) {
        //     return $query->where('users_id', backpack_auth()->user()->id);
        // })->first();
        $datamhs=RegisterMbkm::with(['lecturer','mbkm','student'])->whereHas('student', function($query) use ($datakap){
            return $query->where('program_studi', $datakap->lecturer->program_studi);
        })->where('status','done')->where('mbkm_id',null)->get();

        return view('vendor/backpack/crud/riwayatmhs_mbkbmeksternal', compact('datamhs', 'crud'));
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

    protected function formEdit($id, Request $request)
    {
        $crud = $this->crud;
        $api = new ClassApi;
        
        $dosen = Lecturer::where('status', 'dosen pembimbing')->get();
        
        $data = Nilaimbkm::with(['involved.course', 'students.program_study', 'mbkm'])->where('id', $id)->first();
        // return $data;
        $nim = $data->students->nim;
        $A = substr($nim, 1, 1);  // Mengambil karakter pada posisi 1 (indeks 0) untuk variabel A
        $B = substr($nim, 3, 2);  // Mengambil karakter pada posisi 3 (indeks 2) untuk variabel B

        
        $semester = $data->mbkm_id == null ? $data->semester : $data->mbkm->semester;
        $ganjilGenap = $semester % 2 == 0 ? 'Genap' : 'Ganjil';
        
        $tahun_kelas = 1;
        if($semester == 1 || $semester == 2){
            $tahun_kelas = 1;
        } else if($semester == 3 || $semester == 4){
            $tahun_kelas = 2;
        } else if($semester == 5 || $semester == 6){
            $tahun_kelas = 3;
        } else if($semester == 7 || $semester == 8){
            $tahun_kelas = 4;
        } 
        

        $querycourse = $api->getMatkul($request, "20{$B}", $ganjilGenap, $data->students->program_studi, $tahun_kelas);

        $filteredCourse = array_unique(array_column($querycourse, 'kode_mata_kuliah'));
        $resultCourse = array_values(array_intersect_key($querycourse, array_flip(array_keys($filteredCourse))));

        $course = $resultCourse;                                                                                                     

        // return $course;

        // return $course;
        // return $data->students;        //    $course = Course::where('program_id', $data->students->study_program_id)
        //     ->where('tahun_kurikulum', "20{$B}")
        //     ->where(function ($query) use ($data, $Rsemester, $data_sks) {
        //         $query->where(function ($innerQuery) use ($data_sks) {
        //             $innerQuery->whereNull('semester')->orWhereNull('sks')->orWhere('sks', optional($data_sks->first())->sks);
        //         })
        //         ->orWhere(function ($innerQuery) use ($data, $Rsemester) {
        //             $innerQuery->where('semester', optional($data->mbkm)->semester)->orWhere('semester', optional($Rsemester)->semester);
        //         });
        //     })
        //     ->get();

        // return $data;w
        return view('vendor.backpack.crud.editManageStudent', compact('crud', 'dosen', 'data', 'course'));
    }

    protected function editDosen(Request $request, $id)
    {
        Nilaimbkm::where('id', $id)->update([
            "pembimbing" => $request->dosen
        ]);
        session()->flash('test', 'success');
        Alert::success('Berhasil Menyimpan')->flash();
        return redirect('/admin/manage-student');
    }

    protected function editMatkul(Request $request, $id)
    {

        InvolvedCourse::where('reg_mbkm_id', $id)->delete();
        if ($request->ids) {
            
            # code...
            for ($i = 0; $i < count($request->ids); $i++) {
                $decodeMatkul = json_decode($request->ids[$i]);
                InvolvedCourse::create([
                    "reg_mbkm_id" => $id,
                    "kode_matkul" => $decodeMatkul->kode_matkul,
                    "nama_matkul" => $decodeMatkul->nama_matkul,
                    "sks" => $decodeMatkul->sks
                ]);
            }
        }
        Alert::success('Berhasil Menyimpan')->flash();
        return redirect('/admin/manage-student');
    }
}
