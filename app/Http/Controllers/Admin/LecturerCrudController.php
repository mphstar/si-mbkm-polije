<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\LecturerRequest;
use App\Models\Lecturer;
use App\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Prologue\Alerts\Facades\Alert;

/**
 * Class LecturerCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class LecturerCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Lecturer::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/lecturer');
        CRUD::setEntityNameStrings('lecturer', 'lecturers');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('user.email');
        CRUD::column('address');;

        CRUD::column('lecturer_name');
        CRUD::column('nip');
        CRUD::column('phone');
        CRUD::column('status');


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
        CRUD::setValidation(LecturerRequest::class);

        CRUD::field('lecturer_name');
        CRUD::field('address');

        CRUD::field('nip');
        CRUD::field('phone');

        $this->crud->addField([
            'name' => 'status',
            'type' => 'select_from_array',
            'label' => 'Status ACC',
            'options' => ['dosen pembimbing' => 'Dosen Pembimbing', 'admin prodi' => 'Admin Prodi', 'kaprodi' => 'Kaprodi'],

        ]);
        CRUD::field('email');
        CRUD::field('password');
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
        CRUD::field('lecturer_name');
        CRUD::field('address');

        CRUD::field('nip');
        CRUD::field('phone');

        $this->crud->addField([
            'name' => 'status',
            'type' => 'select_from_array',
            'label' => 'Status ACC',
            'options' => ['dosen pembimbing' => 'Dosen Pembimbing', 'admin prodi' => 'Admin Prodi', 'kaprodi' => 'Kaprodi'],

        ]);
        $this->crud->addField(
            [
                'name' => 'user.email',
                'label' => 'Email',
                'type' => 'text',
            ]
        );;

        $this->crud->addField([
            'name' => 'user.id',
            'type' => 'hidden',
            'label' => "Masukkan email mitra"
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|unique:users',
            "lecturer_name" => 'required',
            "address" => 'required',
            "phone" => 'required',
            "nip" => 'required',
            "status" => 'required',
            "password" => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $level = $request->status == 'dosen pembimbing' ? 'dospem' : 'kaprodi';

        $user = User::create([
            "name" => $request->lecturer_name,
            "email" => $request->email,
            "password" => bcrypt($request->password),
            "level" => $level
        ]);

        $partner = Lecturer::create([
            "lecturer_name" => $request->lecturer_name,
            "address" => $request->address,
            "phone" => $request->phone,
            "status" => $request->status,
            "nip" => $request->nip,
            "users_id" => $user->id
        ]);

        Alert::success('Data berhasil disimpan')->flash();
        return Redirect::to($this->crud->getRoute());
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
            "lecturer_name" => 'required',
            "address" => 'required',
            "phone" => 'required',
            "nip" => 'required',
            "status" => 'required',
        ]);

        if ($validatorB->fails()) {
            return redirect()->back()
                ->withErrors($validatorB)
                ->withInput();
        }

        $level = $request->status == 'dosen pembimbing' ? 'dospem' : 'kaprodi';

        $user = User::where('id', $request->user['id'])->update([
            "name" => $request->lecturer_name,
            "email" => $request->user['email'],
            "level" => $level
        ]);

        $partner = Lecturer::where('id', $id)->update([
            "lecturer_name" => $request->lecturer_name,
            "address" => $request->address,
            "phone" => $request->phone,
            "nip" => $request->nip,
            "status" => $request->status,
        ]);

        Alert::success('Data berhasil disimpan')->flash();
        return Redirect::to($this->crud->getRoute());
    }

    public function destroy($id){
        $data = Lecturer::find($id);
        
        $delete = User::where('id', $data->users_id)->delete();

        return $delete;
    }
}
