<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\TemplateNilaiRequest;
use App\Models\TemplateNilai;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
        CRUD::setEntityNameStrings('template nilai', 'template nilais');
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

    public function index(){
        $crud = $this->crud;
        $file = TemplateNilai::orderBy('id','desc')->get();

        return view('vendor/backpack/crud/Halaman_index_template_nilai',compact('crud','file'));
    }

    public function HalamanTambah(){
        $crud = $this->crud;
        $jenisdocument = DB::table('jenis_document')
                    ->select('id', 'nama_jenis_document')
                    ->get();
      return view('vendor/backpack/crud/Halaman_tambah_template_nilai',compact('crud', 'jenisdocument'));
    }

    public function store(Request $request)
    {
        $crud = $this->crud;

        $request->validate([
            'file' => 'nullable|file|mimes:pdf|max:2048|required',
            'name_template' => 'required',
            'format'=>'required'
        ]);

        $name = $request->name_template;
        $file = $request->file('file');
        $jenisDocument = $request->format;

        // dd($request->all());
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
                'id_jenis_document'=> $jenisDocument
            ]);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create record: ' . $e->getMessage());
            // dd($name, $file);
        }

        return redirect(config('backpack.base.route_prefix').'/template-nilai');
    }
    public function deleteFile($id){

        $data = TemplateNilai::findOrFail($id);

        Storage::delete($data->file);
        $data->delete();
        return redirect()->back();
    }

    public function unduhfile($id){
        $file = TemplateNilai::findOrFail($id);

    // Path ke file di dalam penyimpanan
    $filePath = 'uploads/' . $file->file;

    // Mendapatkan nama asli file
    $originalName = pathinfo($file->file, PATHINFO_FILENAME);

    // Membangun response untuk mengirimkan file ke pengguna
    return response()->download(storage_path("app/{$filePath}"), "{$originalName}.pdf");
    }

}
