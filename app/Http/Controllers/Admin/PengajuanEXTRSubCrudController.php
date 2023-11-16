<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PengajuanEXTRSubRequest;
use App\Models\PengajuanEXTR;
use App\Models\PengajuanEXTRSub;
use App\Models\Students;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class PengajuanEXTRSubCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PengajuanEXTRSubCrudController extends CrudController
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
        CRUD::setModel(\App\Models\PengajuanEXTRSub::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/pengajuan-e-x-t-r-sub');
        CRUD::setEntityNameStrings('pengajuan e x t r sub', 'pengajuan e x t r subs');
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
    public function detail_pengajuan($id){
        $detail_pengajuan=PengajuanEXTRSub::with(['partner'])->where('exmbkm_id',$id)->get();
        $cek_status=PengajuanEXTRSub::with(['partner'])->where('exmbkm_id',$id)->where('status','=','diambil')->get();
        $idjenis=PengajuanEXTR::where('id',$id)->value('id_jenis');
        $siswa=PengajuanEXTR::where('id',$id)->value('student_id');
        $id_extra=$id;
        $crud = $this->crud;
        return view('vendor/backpack/crud/detail_pengajuanmbkmeks', compact('crud', 'detail_pengajuan','id_extra','idjenis','siswa','cek_status'));

    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(PengajuanEXTRSubRequest::class);

        

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
