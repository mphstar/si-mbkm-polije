<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ValidasiMbkmRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ValidasiMbkmCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ValidasiMbkmCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    // use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    // use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\ValidasiMbkm::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/validasi-mbkm');
        CRUD::setEntityNameStrings('validasi mbkm', 'validasi mbkms');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->setColumns(['partner.partner_name', 'program_name', 'start_date', 'end_date', 'info', [
            'name'  => 'status_acc',
            'label' => 'Status ACC', // Table column heading
            'type'  => 'model_function',
            'function_name' => 'getStatusSpan'
        ]]);
        $this->crud->setColumnLabel('Partner.partner_name', 'NAMA MITRA');
        // CRUD::addClause('where', 'status_acc', '=', 'pending');
        // CRUD::addClause('where', 'is_active', '=', 'inactive');


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
        CRUD::setValidation(ValidasiMbkmRequest::class);



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
            'name' => 'status_acc',
            'type' => 'select_from_array',
            'label' => 'Status ACC',
            'options' => ['accepted' => 'Accepted', 'rejected' => 'Rejected'],

        ]);
        $this->crud->addField([
            'name' => 'is_active',
            'type' => 'select_from_array',
            'label' => 'Status MBKM',
            'options' => ['active' => 'Active', 'inactive' => 'Inactive'],

        ]);
    }
    protected function setupShowOperation()
    {
        // by default the Show operation will try to show all columns in the db table,
        // but we can easily take over, and have full control of what columns are shown,
        // by changing this config for the Show operation 
        $this->crud->set('show.setFromDb', false);



        $this->crud->addColumn([
            'name' => 'partner.partner_name',
            'label' => 'Nama Mitra',
        ]);
        $this->crud->addColumn([
            'name' => 'program_name',
            'label' => 'Nama Program',
        ]);
        $this->crud->addColumn([
            'name' => 'start_date',
            'label' => 'Tanggal Mulai program MBKM',
        ]);
        $this->crud->addColumn([
            'name' => 'end_date',
            'label' => 'Tanggal Selesai Program MBKM ',
        ]);
        $this->crud->addColumn([
            'name' => 'start_reg',
            'label' => 'Tanggal awal pendaftaran',
        ]);
        $this->crud->addColumn([
            'name' => 'end_reg',
            'label' => 'Tanggal terakhir pendaftaran',
        ]);
        $this->crud->addColumn([
            'name' => 'info',
            'label' => 'Keterangan MBKM'
        ]);
        $this->crud->addColumn([
            'name' => 'semester',
            'label' => 'Berlaku Bagi semester'
        ]);
        $this->crud->addColumn([
            'name'  => 'status_acc',
            'label' => 'Status ACC', // Table column heading
            'type'  => 'model_function',
            'function_name' => 'getStatusSpan'
        ]);
        $this->crud->addColumn([
            'name'  => 'is_active',
            'label' => 'Status Active', // Table column heading
            'type'  => 'model_function',
            'function_name' => 'getIsactiveSpan'
        ]);

        // $this->crud->removeColumn('date');
        // $this->crud->removeColumn('extras');

        // Note: if you HAVEN'T set show.setFromDb to false, the removeColumn() calls won't work
        // because setFromDb() is called AFTER setupShowOperation(); we know this is not intuitive at all
        // and we plan to change behaviour in the next version; see this Github issue for more details
        // https://github.com/Laravel-Backpack/CRUD/issues/3108
    }
}
