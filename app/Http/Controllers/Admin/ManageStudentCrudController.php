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

        $dosen = Lecturer::where('status', 'dosen pembimbing')->get();

        $data = Nilaimbkm::with(['involved.course', 'students.program_study', 'mbkm'])->where('id', $id)->first();
        // dd($data);
        $data_sks = DB::table('mbkms')
    ->join('reg_mbkms', 'mbkms.id', '=', 'reg_mbkms.mbkm_id')
    ->where('reg_mbkms.mbkm_id', $data->mbkm_id)
    ->orWhereNull('reg_mbkms.mbkm_id')
    ->select('mbkms.jumlah_sks AS sks')
    ->get();

    // dd($data_sks);

    $Rsemester = DB::table('reg_mbkms')
    ->select('id', 'semester')
    ->where('student_id', $id)
    ->whereNotNull('semester')
    ->first();
        // dd($Rsemester);

        $nim = $data->students->nim;
        $A = substr($nim, 1, 1);  // Mengambil karakter pada posisi 1 (indeks 0) untuk variabel A
        $B = substr($nim, 3, 2);  // Mengambil karakter pada posisi 3 (indeks 2) untuk variabel B

//    $course = Course::where('program_id', $data->students->study_program_id)
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

        $course = Course::where('program_id', $data->students->study_program_id)
    ->where('tahun_kurikulum', "20{$B}")
    ->where('semester', optional($data->mbkm)->semester) // Use optional() to handle null
    ->orWhere('semester', optional($Rsemester)->semester) // Use optional() to handle null
    ->get();

//     $course = Course::where('program_id', $data->students->study_program_id)
//     ->where('tahun_kurikulum', "20{$B}")
//     ->where(function ($query) use ($data, $Rsemester, $data_sks) {
//         $query->whereNull('semester')
//             ->orWhere('semester', optional($data->mbkm)->semester)
//             ->orWhere('semester', optional($Rsemester)->semester);
//     });

// if ($data_sks->isNotEmpty()) {
//     // Jika informasi sks ada (MBKM internal)
//     $course->where(function ($innerQuery) use ($data_sks) {
//         $innerQuery->whereNull('sks')
//             ->orWhere('sks', $data_sks->pluck('sks')->first());
//     });
// } else {
//     // Jika informasi sks tidak ada (MBKM eksternal)
//     $course->select('id', 'name'); // Hanya pilih kolom yang diperlukan
// }

// $courseResult = $course->get();

// $courseQuery = Course::where('program_id', $data->students->study_program_id)
//     ->where('tahun_kurikulum', "20{$B}")
//     ->where(function ($query) use ($data, $Rsemester) {
//         $query->whereNull('semester')
//             ->orWhere('semester', optional($data->mbkm)->semester)
//             ->orWhere('semester', optional($Rsemester)->semester);
//     });

// $courseInternalQuery = clone $courseQuery;
// $courseInternalQuery->where(function ($innerQuery) use ($data_sks) {
//     $innerQuery->whereNull('sks')
//         ->orWhere('sks', $data_sks->pluck('sks')->first());
// });

// // Di sini kita perlu mengeksekusi query internal dan eksternal terpisah
// $courseInternalResult = $courseInternalQuery->get();
// $courseExternalResult = $courseQuery->whereNull('sks')->get();

// // Tambahkan informasi tambahan untuk debugging
// // dd([
// //     'data_sks' => $data_sks,
// //     'courseInternalResult' => $courseInternalResult,
// //     'courseExternalResult' => $courseExternalResult,
// // ]);

// $courseResult = $courseInternalResult->merge($courseExternalResult);


    // dd($course);
        // return $data;w
        return view('vendor.backpack.crud.editManageStudent', compact('crud', 'dosen', 'data', 'course','data_sks'));
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
