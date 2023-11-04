<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PenilaianMitraRequest;
use App\Models\Mbkm;
use App\Models\MbkmReport;
use App\Models\PenilaianMitra;
use App\Models\RegisterMbkm;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Prologue\Alerts\Facades\Alert;

/**
 * Class PenilaianMitraCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PenilaianMitraCrudController extends CrudController
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
        CRUD::setModel(\App\Models\PenilaianMitra::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/penilaian-mitra');
        CRUD::setEntityNameStrings('penilaian mitra', 'penilaian mitras');
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
        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']); 
         */
        $this->crud->addButtonFromView('line', 'partner_grade', 'partner_grade', 'beginning');
    }
    public function penilaianmitra()
    {
        $crud = $this->crud;

        $pendaftar = RegisterMbkm::with('student')->with('mbkm')->get();

        return view('vendor/backpack/crud/viewpenilaianmitra', compact('pendaftar', 'crud'));
    }
    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(PenilaianMitraRequest::class);



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
        $this->crud->addField([]);


        // CRUD::field('nilai_mitra')
        // ->type('upload')
        // ->withFiles([
        //     'disk' => 'public', // the disk where file will be stored
        //     'path' => 'uploads', // the path inside the disk where file will be stored
        // ]);
    }
    public function updating($id)
    {


        $regmbkm = RegisterMbkm::where('id', $id)->get();


        foreach ($regmbkm as $item) {
            if ($item->status == 'done') {
                Alert::warning('Tidak bisa upload nilai karna anda sudah upload nilaiS')->flash();
                return back();
            }
        }

        $mbkmId = RegisterMbkm::with('mbkm')
            ->where('student_id', $regmbkm[0]->student_id)
            ->where('status',  'accepted')
            ->whereHas('mbkm', function ($query) {
                $now = Carbon::now();
                $query->whereDate('start_date', '<=', $now)
                    ->whereDate('end_date', '>=', $now);
            })->orderBy('id', 'desc')->get();
        $laporan = MbkmReport::where('reg_mbkm_id', $id)->get();
        $acceptedCount = $laporan->where('status', 'accepted')->count();
        $targetCount = Mbkm::where('id', $mbkmId[0]->mbkm_id)->value('task_count');

        
        if ($laporan->isEmpty()) {
            $count = 0;

            Alert::error('Tidak bisa upload nilai karna task dari peserta belum lengkap')->flash();
            return redirect('admin/penilaian-mitra');
        } elseif ($acceptedCount == 0) {
            $count = "0";
        } else {
            $count = ($acceptedCount / $targetCount) * 100;
        }

        if (($count == 100)) {
            $regmbkm = RegisterMbkm::where('id', $id)->get();
            $crud = $this->crud;
            return view('vendor.backpack.crud.partner_grading', compact('crud'));
        } else {


            Alert::error('Tidak bisa upload nilai karna task dari peserta belum lengkap')->flash();
            return redirect('admin/penilaian-mitra');
        }

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
            $input['status'] = "done";
        }

        $user = PenilaianMitra::where('id', $id)->update($input);

        Alert::success('Berhasil upload nilai')->flash();
        return redirect("admin/penilaian-mitra");
    }
}
