<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\LectureRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class LectureCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class LectureCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\Lecture');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/lecture');
        $this->crud->setEntityNameStrings('lecture', 'lectures');
    }

    protected function setupListOperation()
    {
        $this->crud->setColumns(['nip', 'nama_dosen','email','alamat','no_telfon','username','password','status']);
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(LectureRequest::class);
        $this->crud->addField([
            'name' => 'nip',
            'type' => 'number',
            'label' => "masukkan nip"
          ]);
          $this->crud->addField([
            'name' => 'nama_dosen',
            'type' => 'text',
            'label' => "masukkan nama dosen"
          ]);
          $this->crud->addField([
            'name' => 'email',
            'type' => 'text',
            'label' => "masukkan email dosen"
          ]);
          $this->crud->addField([
            'name' => 'alamat',
            'type' => 'text',
            'label' => "masukkan nama alamat"
          ]);
          $this->crud->addField([
            'name' => 'no_telfon',
            'type' => 'number',
            'label' => "masukkan  no telfon"
          ]);
          $this->crud->addField([
            'name' => 'username',
            'type' => 'text',
            'label' => "masukkan username dosen"
          ]);
          $this->crud->addField([
            'name' => 'password',
            'type' => 'text',
            'label' => "masukkan password"
          ]);
          $this->crud->addField([
            'name' => 'status',
            'type' => 'select_from_array',
            'label' => 'Status',
            'options' => ['dosen pembimbing' => 'Dosen Pembimbing', 'admin prodi' => 'Admin Prodi','kaprodi' => 'Kaprodi'],
          
        ]);
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
