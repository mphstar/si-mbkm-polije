<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PartnerRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\CRUD\app\Library\CrudPanel\CrudButton;
/**
 * Class PartnerCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PartnerCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Partner::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/partner');
        CRUD::setEntityNameStrings('partner', 'partners');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        
        $this->crud->setColumns(['partner_name', 'address','phone','email','status','jenis_mitra','username','password']);
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
        CRUD::setValidation(PartnerRequest::class);
        $this->crud->addField([
            'name' => 'partner_name',
            'type' => 'text',
            'label' => "Masukkan Nama mitra"
          ]);
          $this->crud->addField([
            'name' => 'address',
            'type' => 'text',
            'label' => "Masukkan alamat mitra"
          ]);
          $this->crud->addField([
              'name' => 'phone',
              'type' => 'number',
              'label' => "Masukkan nomor telfon mitra"
            ]);
          $this->crud->addField([
              'name' => 'email',
              'type' => 'email',
              'label' => "Masukkan email mitra"
            ]);
            
            // $this->crud->addField([
            //     'name' => 'jenis_mitra',
            //     'type' => 'select', // Menggunakan select untuk combobox
            //     'label' => 'Pilih jenis mitra',
            //     'attributes' => [
            //         'placeholder' => 'Pilih jenis' // Optional: Tambahkan atribut untuk placeholder
            //     ],
            //     'options' => function () {
            //         return [
            //             'luar kampus' => 'Luar Kampus',
            //             'dalam kampus' => 'Dalam Kampus',
                        
            //         ];
            //     }
            // ]);
            // $this->crud->addField([
            //     'name' => 'jenis_mitra', // Nama kolom di database
            //     'label' => 'Jenis Mitra', // Label yang akan ditampilkan pada form
            //     'type' => 'select', // Tipe field
            //     'options' => [  // Definisikan pilihan yang akan ditampilkan
            //         'luar kampus' => 'Luar Kampus',
            //         'dalam kampus' => 'Dalam Kampus',
                   
            //     ],
            // ]);
            $this->crud->addField([
                'name' => 'jenis_mitra',
                'type' => 'select_from_array',
                'label' => 'Jenis mitra',
                'options' => ['luar kampus' => 'Luar Kampus', 'dalam kampus' => 'Dalam Kampus'],
                'default' => 'luar kampus',
            ]);
            $this->crud->addField([
              'name' => 'username',
              'type' => 'text',
              'label' => "Masukkan username akun mitra"
            ]);
            $this->crud->addField([
              'name' => 'password',
              'type' => 'password',
              'label' => "Masukkan password akun mitra"
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
        CRUD::setValidation(PartnerRequest::class);

    $this->crud->addField([
        'name' => 'partner_name',
        'type' => 'text',
        'label' => "Masukkan Nama mitra"
    ]);
    $this->crud->addField([
        'name' => 'address',
        'type' => 'text',
        'label' => "Masukkan alamat mitra"
    ]);
    $this->crud->addField([
        'name' => 'phone',
        'type' => 'number',
        'label' => "Masukkan nomor telfon mitra"
    ]);
    $this->crud->addField([
        'name' => 'email',
        'type' => 'email',
        'label' => "Masukkan email mitra"
    ]);
    $this->crud->addField([
        'name' => 'jenis_mitra',
        'type' => 'select_from_array',
        'label' => 'Jenis mitra',
        'options' => ['luar kampus' => 'Luar Kampus', 'dalam kampus' => 'Dalam Kampus'],
        'default' => 'luar kampus',
    ]);
    $this->crud->addField([
        'name' => 'username',
        'type' => 'text',
        'label' => "Masukkan username akun mitra"
    ]);
    $this->crud->addField([
        'name' => 'password',
        'type' => 'text',
        'label' => "Masukkan password akun mitra"
   // Mengatur nilai default ke string kosong
    ]);
    }
}
