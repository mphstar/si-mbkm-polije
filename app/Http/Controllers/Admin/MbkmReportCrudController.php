<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\MbkmReportRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class MbkmReportCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class MbkmReportCrudController extends CrudController
{
    // use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    // use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    // use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    // use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    // use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    
    public function viewReport() {
        $crud = $this->crud;
        return view('vendor/backpack/crud/report_mbkm', compact('crud'));
    }
    public function setup()
    {
        CRUD::setModel(\App\Models\MbkmReport::class);
        // CRUD::setModel(\App\Models\RegisterMbkm::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/mbkm-report');
        CRUD::setEntityNameStrings('mbkm report', 'mbkm reports');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        // return dd($this->crud);
        $this->crud->setColumns(['regMbkm.student_id', 'reg_mbkm_id', 'file', 'status', 'upload_date']);
        // CRUD::addClause('where', 'student_id', '=', '1');
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(MbkmReportRequest::class);

        CRUD::field('id');
        CRUD::field('report_mbkm_id');
        CRUD::field('file');
        CRUD::field('status');
        CRUD::field('upload_date');
        CRUD::field('created_at');
        CRUD::field('updated_at');

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
