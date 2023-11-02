<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PenilaianMitraRequest;
use App\Models\PenilaianMitra;
use App\Models\RegisterMbkm;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
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
        ] ]);
        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']); 
         */
        $this->crud->addButtonFromView('line', 'partner_grade', 'partner_grade', 'beginning');
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
        $this->crud->addField([
   
  


        ]);
       
        
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
    $crud = $this->crud;
    return view('vendor.backpack.crud.partner_grading', compact( 'crud'));
// show a form that does something
}
public function penilaian(Request $request, $id) {
    $post = PenilaianMitra::find($id);

    if ($request->hasFile('file')) {
        // Hapus file lama jika ada
        if ($post->file_path) {
            Storage::delete($post->file_path);
        }

        // Simpan file yang diunggah ke penyimpanan yang sesuai
        $file = $request->file('file')->getClientOriginalName();
        $fileName = time().'.'.$request->file('file')->getClientOriginalExtension();
 
        $request->file('file')->move(public_path('storage/uploads'), $fileName);
        $input['partner_grade'] = "storage/uploads/$fileName";
     
    }
   
    $user = PenilaianMitra::where('id',$id)->update($input);
    Alert::success('Berhasil Mendaftar!')->flash();
    return redirect("admin/penilaian-mitra");
}

}
