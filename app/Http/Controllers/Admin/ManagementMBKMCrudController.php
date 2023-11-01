<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ManagementMBKMRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ManagementMBKMCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ManagementMBKMCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    // use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\ManagementMBKM::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/management-m-b-k-m');
        CRUD::setEntityNameStrings('management MBKM', 'management MBKMS');
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
            'name' => 'program_name',
            'label' => 'Nama Program',
        ], [
            'name' => 'partner.partner_name',
            'label' => 'Nama Mitra',
        ], [
            'name' => 'start_date',
            'label' => 'Tanggal Mulai',
        ], [
            'name' => 'end_date',
            'label' => 'Tanggal Selesai',
        ],[
            'name' => 'capacity',
            'label' => 'Kuota',
        ],[
            'name'  => 'status_acc',
            'label' => 'Status ACC', // Table column heading
            'type'  => 'model_function',
            'function_name' => 'getStatusSpan'
        ],[
            'name'  => 'is_active',
            'label' => 'Status Active', // Table column heading
            'type'  => 'model_function',
            'function_name' => 'getIsactiveSpan'
        ],]);
   
   
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
        CRUD::setValidation(ManagementMBKMRequest::class);
        $this->crud->addField([
            'name' => 'partner_id', // Nama kolom dalam tabel "MBKM" yang akan menyimpan ID mitra
            'label' => 'Pilih Mitra',
            'type' => 'select',
            'entity' => 'partner', // Nama relasi dalam model "MBKM"
            'attribute' => 'partner_name', // Atribut yang ingin ditampilkan dalam combo box
            'model' => 'App\Models\Partner', // Model yang digunakan untuk mendapatkan data mitra
        ]);
        $this->crud->addField([
            'name' => 'program_name', // Nama kolom dalam tabel "MBKM" yang akan menyimpan ID mitra
            'label' => 'Nama Program',
            'type' => 'text',
        ]);
        $this->crud->addField([
            'name' => 'capacity',
            'type' => 'number',
            'label' => "Kapasitas mbkm"
          ]);
          $this->crud->addField([
              'name' => 'start_date',
              'type' => 'date',
              'label' => " tanggal awal mbkm"
            ]);
          $this->crud->addField([
              'name' => 'start_reg',
              'type' => 'date',
              'label' => " tanggal awal pendaftaran MBKM"
            ]);
            
            $this->crud->addField([
                'name' => 'end_date',
                'type' => 'date',
                'label' => "tanggal akhir program mbkm"
            ]);
            $this->crud->addField([
                'name' => 'end_reg',
                'type' => 'date',
                'label' => "tanggal akhir pendaftaran MBKM"
            ]);
         
                 $this->crud->addField([
                'name'  => 'info',
                'type'  => 'textarea',
                'label' => 'Masukkan informasi terkait MBKM(termasuk syarat pendaftaran)',
              ]);
            $this->crud->addField([
                'name' => 'task_count',
                'type' => 'number',
                'label' => "Tugas MBKM(berupa banyaknya laporan yang harus di upload oleh peserta MBKM)"
              ]);
            $this->crud->addField([
                'name' => 'semester',
                'type' => 'number',
                'label' => "Berlaku bagi Semester"
              ]);
           
        
        

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
    protected function setupShowOperation()
    {
        // by default the Show operation will try to show all columns in the db table,
        // but we can easily take over, and have full control of what columns are shown,
        // by changing this config for the Show operation 
        $this->crud->set('show.setFromDb', false);

      
 
        $this->crud->addColumn( [
            'name' => 'partner.partner_name',
            'label' => 'Nama Mitra',
        ]);
        $this->crud->addColumn( [
            'name' => 'program_name',
            'label' => 'Nama Program',
        ]);
        $this->crud->addColumn( [
            'name' => 'start_date',
            'label' => 'Tanggal Mulai program MBKM',
        ]);
        $this->crud->addColumn( [
            'name' => 'end_date',
            'label' => 'Tanggal Selesai Program MBKM ',
        ]);
        $this->crud->addColumn( [
            'name' => 'start_reg',
            'label' => 'Tanggal awal pendaftaran',
        ]);
        $this->crud->addColumn( [
            'name' => 'end_reg',
            'label' => 'Tanggal terakhir pendaftaran',
        ]);
        $this->crud->addColumn( [
            'name' => 'info',
            'label' => 'Keterangan MBKM'
        ]);
        $this->crud->addColumn( [
            'name' => 'semester',
            'label' => 'Berlaku Bagi semester'
        ]);
        $this->crud->addColumn( [
            'name'  => 'status_acc',
            'label' => 'Status ACC', // Table column heading
            'type'  => 'model_function',
            'function_name' => 'getStatusSpan'
        ]);
        $this->crud->addColumn( [
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
