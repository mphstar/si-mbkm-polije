<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\MbkmExternalRequest;
use App\Models\MbkmExternal;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Http\Request;

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
