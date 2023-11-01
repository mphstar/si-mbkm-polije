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
        
//         $this->crud->setColumns(['partner_name', 'address','phone','email','jenis_mitra','username','password']);
//         $this->crud->addColumn([  [
//             'name'  => 'status',
//    'label' => 'status ACC', // Table column heading
//    'type'  => 'model_function',
//    'function_name' => 'getStatusSpan'
//         ]])->afterColumn('jenis_mitra');
$this->crud->setColumns([[
    'name' => 'partner_name',
    'label' => 'Nama Partner',
], [
    'name' => 'address',
    'label' => 'Alamat Mitra',
], [
    'name' => 'phone',
    'label' => 'No telfon',
], [
    'name' => 'email',
    'label' => 'Email',
],[
    'name'  => 'status',
    'label' => 'Status ACC', // Table column heading
    'type'  => 'model_function',
    'function_name' => 'getStatusSpan'
],[
    'name' => 'username',
    'label' => 'username',
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
    protected function setupShowOperation()
    {
        // by default the Show operation will try to show all columns in the db table,
        // but we can easily take over, and have full control of what columns are shown,
        // by changing this config for the Show operation 
        $this->crud->set('show.setFromDb', false);

      
 
        $this->crud->addColumn( [
            'name' => 'partner_name',
            'label' => 'Nama Mitra',
        ]);
        $this->crud->addColumn( [
            'name' => 'phone',
            'label' => 'No telfon',
        ]);
        $this->crud->addColumn( [
            'name' => 'address',
            'label' => 'Alamat',
        ]);
        $this->crud->addColumn( [
            'name' => 'email',
            'label' => 'Email',
        ]);
        $this->crud->addColumn( [
            'name' => 'jenis_mitra',
            'label' => 'Jenis Mitra',
        ]);
        $this->crud->addColumn( [
            'name'  => 'status',
            'label' => 'Status ACC', // Table column heading
            'type'  => 'model_function',
            'function_name' => 'getStatusSpan'
        ]);
        $this->crud->addColumn( [
            'name' => 'username',
            'label' => 'Username',
        ]);
        $this->crud->addColumn( [
            'name' => 'password',
            'label' => 'Password',
        ]);
    
  
        
        // $this->crud->removeColumn('date');
        // $this->crud->removeColumn('extras');

        // Note: if you HAVEN'T set show.setFromDb to false, the removeColumn() calls won't work
        // because setFromDb() is called AFTER setupShowOperation(); we know this is not intuitive at all
        // and we plan to change behaviour in the next version; see this Github issue for more details
        // https://github.com/Laravel-Backpack/CRUD/issues/3108
    }
}
