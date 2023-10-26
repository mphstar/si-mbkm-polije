<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\RegisterMbkmRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\CRUD\app\Library\Validation\Rules\ValidUpload;
/**
 * Class RegisterMbkmCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class RegisterMbkmCrudController extends CrudController
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
        CRUD::setModel(\App\Models\RegisterMbkm::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/register-mbkm');
        CRUD::setEntityNameStrings('register mbkm', 'register mbkms');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->setColumns(['student.name', 'mbkm.info', 'status']);

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
        CRUD::setValidation(RegisterMbkmRequest::class);

        $this->crud->addField([
            'name' => 'student_id', // Nama kolom dalam tabel "MBKM" yang akan menyimpan ID mitra
            'label' => 'Pilih Mahasiswa',
            'type' => 'select',
            'entity' => 'student', // Nama relasi dalam model "MBKM"
            'attribute' => 'name', // Atribut yang ingin ditampilkan dalam combo box
            'model' => 'App\Models\students', // Model yang digunakan untuk mendapatkan data mitra
        ]);
        $this->crud->addField([
            'name' => 'mbkm_id', // Nama kolom dalam tabel "MBKM" yang akan menyimpan ID mitra
            'label' => 'Pilih MBKM',
            'type' => 'select',
            'entity' => 'mbkm', // Nama relasi dalam model "MBKM"
            'attribute' => 'info', // Atribut yang ingin ditampilkan dalam combo box
            'model' => 'App\Models\mbkm', // Model yang digunakan untuk mendapatkan data mitra
        ]);
        CRUD::field('requirements_file')
        ->type('upload')
        ->withFiles([
            'disk' => 'public', // the disk where file will be stored
            'path' => 'uploads', // the path inside the disk where file will be stored
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
}
