<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PartnerRequest;
use App\Models\Partner;
use App\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\CRUD\app\Library\CrudPanel\CrudButton;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Prologue\Alerts\Facades\Alert;

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
            'name' => 'user.email',
            'label' => 'Email',
        ], [
            'name'  => 'status',
            'label' => 'Status ACC', // Table column heading
            'type'  => 'model_function',
            'function_name' => 'getStatusSpan'
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
            'name' => 'email',
            'type' => 'email',
            'label' => "Masukkan email mitra"
        ]);

        // $this->crud->addField([
        //   'name' => 'username',
        //   'type' => 'text',
        //   'label' => "Masukkan username akun mitra"
        // ]);
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

    public function update(Request $request, $id)
    {
        // dd($request->all());

        $check = User::where('id', $request->user['id'])->first();
        if ($check && $check->email != $request->user['email']) {
            $validatorA = Validator::make($request->user, [
                'email' => 'required|unique:users',
            ]);

            if ($validatorA->fails()) {
                return redirect()->back()
                    ->withErrors($validatorA)
                    ->withInput();
            }
        }

        $validatorB = Validator::make($request->all(), [
            "partner_name" => 'required',
            "address" => 'required',
            "phone" => 'required',
        ]);

        if ($validatorB->fails()) {
            return redirect()->back()
                ->withErrors($validatorB)
                ->withInput();
        }

        $user = User::where('id', $request->user['id'])->update([
            "name" => $request->partner_name,
            "email" => $request->user['email'],
        ]);

        $partner = Partner::where('id', $id)->update([
            "partner_name" => $request->partner_name,
            "address" => $request->address,
            "phone" => $request->phone,
            "jenis_mitra" => $request->jenis_mitra,
        ]);

        Alert::success('Data berhasil disimpan')->flash();
        return Redirect::to($this->crud->getRoute());
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [

            "partner_name" => 'required',
            "address" => 'required',
            "phone" => 'required',

        ]);


        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $user = null;

        // Check if email and password are provided
    if ($request->filled('email') && $request->filled('password')) {
        // If both email and password are provided, create a user
        $user = User::create([
            "name" => $request->partner_name,
            "email" => $request->email,
            "password" => bcrypt($request->password),
            "level" => 'mitra',
        ]);
    }
     // Set the status to "accepted" if jenis_mitra is "luar kampus"
     $status = $request->jenis_mitra === 'luar kampus' ? 'accepted' : 'pending';
        $partner = Partner::create([
            "partner_name" => $request->partner_name,
            "address" => $request->address,
            "phone" => $request->phone,
            "status" => $status,
            "jenis_mitra" => $request->jenis_mitra,

            "users_id" => $user ? $user->id : null, // Use user ID if user is created, otherwise null
        ]);

        Alert::success('Data berhasil disimpan')->flash();
        return Redirect::to($this->crud->getRoute());
    }

    public function destroy($id){
        $data = Partner::find($id);

        $delete = User::where('id', $data->users_id)->delete();

        return $delete;
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
            'name' => 'jenis_mitra',
            'type' => 'select_from_array',
            'label' => 'Jenis mitra',
            'options' => ['luar kampus' => 'Luar Kampus', 'dalam kampus' => 'Dalam Kampus'],
            'default' => 'luar kampus',
        ]);
        $this->crud->addField([
            'name' => 'user.email',
            'type' => 'email',
            'label' => "Masukkan email mitra"
        ]);

        $this->crud->addField([
            'name' => 'user.id',
            'type' => 'hidden',
            'label' => "Masukkan email mitra"
        ]);
    }

    protected function setupShowOperation()
    {
        // by default the Show operation will try to show all columns in the db table,
        // but we can easily take over, and have full control of what columns are shown,
        // by changing this config for the Show operation
        $this->crud->set('show.setFromDb', false);



        $this->crud->addColumn([
            'name' => 'partner_name',
            'label' => 'Nama Mitra',
        ]);
        $this->crud->addColumn([
            'name' => 'phone',
            'label' => 'No telfon',
        ]);
        $this->crud->addColumn([
            'name' => 'address',
            'label' => 'Alamat',
        ]);
        $this->crud->addColumn([
            'name' => 'user.email',
            'label' => 'Email',
        ]);
        $this->crud->addColumn([
            'name' => 'jenis_mitra',
            'label' => 'Jenis Mitra',
        ]);
        $this->crud->addColumn([
            'name'  => 'status',
            'label' => 'Status ACC', // Table column heading
            'type'  => 'model_function',
            'function_name' => 'getStatusSpan'
        ]);
        $this->crud->addColumn([
            'name' => 'user.password',
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
