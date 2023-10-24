<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\MbkmRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class MbkmCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class MbkmCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    // use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
private function getFieldsData()  {
    
}
    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Mbkm::class);
        CRUD::setRoute('admin/mbkm');
        CRUD::setEntityNameStrings('mbkm', 'mbkms');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        
        // $this->crud->addColumn([
        //     'name' => 'partner.partner_name',
        //     'label' => 'Nama Mitra',
        // ]);
        
        $this->crud->setColumns(['partner.partner_name', 'start_date', 'end_date', 'info', 'status_acc', 'is_active']);
        $this->crud->setColumnLabel('Partner.partner_name', 'NAMA MITRA'); 
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
        CRUD::setValidation(MbkmRequest::class);

        $this->crud->addField([
            'name' => 'partner_id', // Nama kolom dalam tabel "MBKM" yang akan menyimpan ID mitra
            'label' => 'Pilih Mitra',
            'type' => 'select',
            'entity' => 'partner', // Nama relasi dalam model "MBKM"
            'attribute' => 'partner_name', // Atribut yang ingin ditampilkan dalam combo box
            'model' => 'App\Models\Partner', // Model yang digunakan untuk mendapatkan data mitra
        ]);
        $this->crud->addField([
            'name' => 'capacity',
            'type' => 'number',
            'label' => "Masukkan Kapasitas mbkm"
          ]);
          $this->crud->addField([
              'name' => 'start_date',
              'type' => 'date',
              'label' => "Masukkan tanggal awal mbkm"
            ]);
            
            $this->crud->addField([
                'name' => 'end_date',
                'type' => 'date',
                'label' => "Masukkan tanggal awal mbkm"
            ]);
            $this->crud->addField([
                'name' => 'info',
                'type' => 'text',
                'label' => "Masukkan keterangan mbkm"
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
    // protected function setupUpdateOperation()
    // {
    //     $this->setupCreateOperation();
    // }
}
