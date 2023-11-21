<?php

namespace App\Http\Controllers\Admin;

use App\DetailMbkmExternalSementara;
use App\Http\Requests\MbkmExternalRequest;
use App\Models\MbkmExternal;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Prologue\Alerts\Facades\Alert;

/**
 * Class MbkmExternalCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class MbkmExternalCrudController extends CrudController
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
    // public function index(){
    //     return view('custom_view.MbkmExternal', [
    //         "crud" => $this->crud
    //     ]);
    // }

    public function setup()
    {
        CRUD::setModel(\App\Models\MbkmExternal::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/mbkm-external');
        CRUD::setEntityNameStrings('mbkm external', 'mbkm externals');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */

    public function detail(Request $request){
        $data = MbkmExternal::with(['detail.mbkm.jenismbkm', 'student'])->where('id', $request->id)->first();
        return view('custom_view.detailExmbkmKaprodi', [
            "crud" => $this->crud,
            "data" => $data
        ]);
    }

    public function upload_laporan_ttd(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'file_surat' => 'required|file|mimes:pdf',
        ]);

        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            Alert::warning($messages[0])->flash();
            return back()->withInput();
        }

        $fileName = time() . '.' . $request->file('file_surat')->getClientOriginalExtension();
        $request->file('file_surat')->move(public_path('storage/uploads'), $fileName);
        $input['file_surat'] = "storage/uploads/$fileName";

        MbkmExternal::where('id', $request->id)->update([
            "file_surat_ttd" => $input['file_surat']
        ]);

        Alert::success('Berhasil upload')->flash();
        return back();
    }

    protected function setupListOperation()
    {
        $this->crud->addColumns([
            [
                "name" => "student.name",
                "label" => "Mahasiswa"
            ],
            [
                "name" => "student.nim",
                "label" => "NIM"
            ],
            [
                "name" => "student.program_studi",
                "label" => "Program Studi"
            ],
            [
                "name" => "student.phone",
                "label" => "No Telepon"
            ],
        ]);
 
        $this->crud->addButtonFromModelFunction('line', 'lihatDetail', 'lihatDetail', 'beginning');
        

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
        CRUD::setValidation(MbkmExternalRequest::class);

        

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
}
