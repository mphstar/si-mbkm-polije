<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AcctiveAccountMitraRequest;
use App\Mail\AccountAccepted;
use App\Models\Partner;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

/**
 * Class AcctiveAccountMitraCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class AcctiveAccountMitraCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    // use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    // use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    // use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\AcctiveAccountMitra::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/acctive-account-mitra');
        CRUD::setEntityNameStrings('acctive account mitra', 'validasi account mitra');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        // CRUD::setValidation(AcctiveAccountMitraRequest::class);
        // CRUD::column("partner_name");
        // CRUD::column("address");
        // CRUD::column("phone");
        // CRUD::column("email");

        // CRUD::column("jenis_mitra");
        $this->crud->setColumns([
            'partner_name', 'address', 'phone', 'user.email', 'jenis_mitra', [
                'name'  => 'status',
                'label' => 'status ACC', // Table column heading
                'type'  => 'model_function',
                'function_name' => 'getStatusSpan'
            ]
        ]);
        // CRUD::addClause('where', 'status', '=', 'pending');

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
        CRUD::setValidation(AcctiveAccountMitraRequest::class);



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
        // $this->setupCreateOperation();
        CRUD::setValidation(AcctiveAccountMitraRequest::class);

        // CRUD::field('partner_name');
        // CRUD::field('address');
        // CRUD::field('phone');
        // CRUD::field('email');
        CRUD::addField([
            'name' => 'status',
            'label' => 'Status',
            'type' => 'select_from_array',
            'options' => [
                'accepted' => 'Accepted',
                'rejected' => 'Rejected',
                'pending' => 'Pending',
            ],
        ]);
        // CRUD::field("jenis_mitra");
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
            'name' => 'email',
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
            'name' => 'username',
            'label' => 'Username',
        ]);
        $this->crud->addColumn([
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
    public function index()
    {

        $partners = Partner::select('partners.id as id', 'partners.partner_name as name', 'partners.address as alamat', 'partners.phone as phone', 'users.email as email', 'partners.jenis_mitra as jenis_mitra', 'partners.status as status')
            ->join('users', 'partners.users_id', '=', 'users.id')
            ->where('partners.status', '!=', 'accepted')
            ->get();

        $crud = $this->crud;

        return view('vendor/backpack/crud/ValidasiAccountMitra', compact('partners', 'crud'));
    }

    public function ubah_status($id, Request $request)
    {
        $partner = Partner::find($id);
        $partner->status = $request->newStatus;
        // dd($partner);
        $partner->save();

        $partner->load('user');
        $email = $partner->user->email;
        $password = $partner->user->password;
        Mail::to($email)->send(new AccountAccepted($partner));
        return redirect()->back();
    }
}
