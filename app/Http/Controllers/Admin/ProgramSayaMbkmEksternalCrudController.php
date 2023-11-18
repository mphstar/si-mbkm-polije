<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ProgramSayaMbkmEksternalRequest;
use App\Models\ManagementMBKM;
use App\Models\MbkmReport;
use App\Models\PenilaianMitra;
use App\Models\RegisterMbkm;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Prologue\Alerts\Facades\Alert;

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
            'name' => 'partner.partner_name',
            'label' => 'Nama Mitra',
        ], [
            'name' => 'jenismbkm.jenismbkm',
            'label' => 'Jenis MBKM',
        ], [
            'name' => 'semester',
            'label' => 'Semester',
        ], [
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

        $this->crud->addButtonFromView('line', 'partner_grade', 'partner_grade', 'beginning');

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

    public function updating($id)
    {


        $regmbkm = RegisterMbkm::where('id', $id)->get();

        if($regmbkm[0]->status == 'done'){
            Alert::warning('Anda sudah menyelesaikan program')->flash();
            return back();
        }

        $crud = $this->crud;
        session()->flash('status', 'success');

        return view('vendor.backpack.crud.partner_grading', compact('crud'));
        // show a form that does something
    }

    public function penilaian(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:pdf|max:10000'
        ], [
            'file.required' => 'File PDF harus diunggah.',
            'file.mimes' => 'File harus berformat PDF.',
            'file.max' => 'Ukuran file tidak boleh melebihi 10 MB.',
        ]);

        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            Alert::warning($messages[0])->flash();
            session()->flash('status', 'file not valid');
            return back()->withInput();
        }
        $post = PenilaianMitra::find($id);

        if ($request->hasFile('file')) {
            // Hapus file lama jika ada
            if ($post->file_path) {
                Storage::delete($post->file_path);
            }

            // Simpan file yang diunggah ke penyimpanan yang sesuai
            $file = $request->file('file')->getClientOriginalName();
            $fileName = time() . '.' . $request->file('file')->getClientOriginalExtension();

            $request->file('file')->move(public_path('storage/uploads'), $fileName);
            $input['partner_grade'] = "storage/uploads/$fileName";
            $input['status'] = "accepted";
        }
        
        PenilaianMitra::where('id', $id)->update($input);
        session()->flash('status', 'success');
        Alert::success('Berhasil upload nilai')->flash();

        return redirect("admin/mbkm-eksternal");
    }
}
