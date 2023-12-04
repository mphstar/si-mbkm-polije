<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\TemplateNilaiRequest;
use App\Models\TemplateNilai;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Prologue\Alerts\Facades\Alert;

/**
 * Class TemplateNilaiCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class TemplateNilaiCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\TemplateNilai::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/template-nilai');
        CRUD::setEntityNameStrings('Format File', 'Format File');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {


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
        CRUD::setValidation(TemplateNilaiRequest::class);



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

    public function index()
    {
        $crud = $this->crud;
        $file = TemplateNilai::orderBy('id', 'desc')->get();

        return view('vendor/backpack/crud/Halaman_index_template_nilai', compact('crud', 'file'));
    }

    public function HalamanTambah()
    {
        $crud = $this->crud;

        return view('vendor/backpack/crud/Halaman_tambah_template_nilai', compact('crud'));
    }

    public function store(Request $request)
    {
        $crud = $this->crud;

        $request->validate([
            'file' => 'nullable|file|mimes:pdf|max:2048|required',
            'name_template' => 'required',
        ]);
        // dd($request->all());

        // dd($request);
        $name = $request->name_template;
        $file = $request->file('file');

        // dd($request);
        // dd($file);
        // dd($jenisDocument);

        if ($file->getError() > 0) {
            // dd($file);
            return redirect()->back()->with('error', 'File upload error: ' . $file->getErrorMessage());
        }

        $path = time() . '-' . $request->name_template . '.' . $file->getClientOriginalExtension();

        Storage::disk('local')->put('uploads/' . $path, file_get_contents($file));
        // Storage::disk('public')->put($path, file_get_contents($file));


        try {
            TemplateNilai::create([
                'nama' => $name,
                'file' => $path,
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create record: ' . $e->getMessage());
            // dd($name, $file);
        }

        return redirect(config('backpack.base.route_prefix') . '/template-nilai');
    }
    public function deleteFile($id)
    {

        $data = TemplateNilai::findOrFail($id);

        Storage::delete($data->file);
        $data->delete();
        return redirect()->back();
    }

    public function unduhfile($id, Request $request)
    {
        try {
            $file = TemplateNilai::findOrFail($id);

            // Path ke file di dalam penyimpanan
            $filePath = 'uploads/' . $file->file;

            // Mendapatkan nama asli file
            $originalName = pathinfo($file->file, PATHINFO_FILENAME);

            // Membangun response untuk mengirimkan file ke pengguna
            return response()->download(storage_path("app/{$filePath}"), "{$originalName}.pdf");
        } catch (\Throwable $th) {
            Alert::error('Gagal download', 'Gagal')->flash();
            return back();
        }
    }

    public function HalamanEdit($id)
    {
        $crud = $this->crud;
        $data  = TemplateNilai::find($id);

        return view('vendor/backpack/crud/Halaman_edit_template_nilai', compact('crud', 'data'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'file' => 'nullable|file|mimes:pdf|max:2048',

        ]);

        $template = TemplateNilai::findOrFail($id);

        // Jika ada file baru diunggah
        if ($request->hasFile('file')) {
            $file = $request->file('file');

            if ($file->getError() > 0) {
                return redirect()->back()->with('error', 'File upload error: ' . $file->getErrorMessage());
            }

            $path = time() . '-' . $request->name_template . '.' . $file->getClientOriginalExtension();

            // Hapus file lama sebelum menggantinya
            Storage::disk('local')->delete('uploads/' . $template->file);

            // Simpan file baru
            Storage::disk('local')->put('uploads/' . $path, file_get_contents($file));

            // Update record dengan file baru dan nama baru
            $template->update([
                'nama' => $request->name_template,
                'file' => $path,
            ]);
        } else {
            // Jika tidak ada file baru diunggah, hanya update nama
            $template->update([
                'nama' => $request->name_template,
            ]);
        }

        return redirect(config('backpack.base.route_prefix') . '/template-nilai');
    }
}
