<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\MBKMEksternalRequest;
use App\Models\MBKMEksternal;
use App\Models\RegisterMbkm;
use App\Models\Students;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Prologue\Alerts\Facades\Alert;

/**
 * Class MBKMEksternalCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class MBKMEksternalCrudController extends CrudController
{
    // use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    // use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    // use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    // use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    // use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\MBKMEksternal::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/m-b-k-m-eksternal');
        CRUD::setEntityNameStrings('m b k m eksternal', 'm b k m eksternals');
    }

    public function daftareksternal() {
    // $id_student = backpack_auth()->user()->with('partner')->whereHas('partner', function($query){
    //         return $query->where('users_id', backpack_auth()->user()->id);
    //     })->first();
    $siswa=Students::all();
    $id=2;
        $crud = $this->crud;
        return view('vendor/backpack/crud/mbkbmeksternal', compact('crud','siswa','id'));
    }
public function storeData(Request $request)  {
    $cek=RegisterMbkm::where('student_id',$request->student_id);
    if ($cek != null) {
        $messages ="Maaf anda sudah mendaftar program mbkm";
        Alert::warning($messages[0])->flash();
        return back()->withInput();
    }else{
    $validator = Validator::make($request->all(), [

        'jenis_mbkm' => 'required',
        'nama_mitra' => 'required',

    ]);

    if ($validator->fails()) {
        $messages = $validator->errors()->all();
        Alert::warning($messages[0])->flash();
        return back()->withInput();
    }
    $input = $request->all();

    $file = $request->file('requirements_files')->getClientOriginalName();
    // dd($file);
    $fileName = time().'.'.$request->file('requirements_files')->getClientOriginalExtension();

    $request->file('requirements_files')->move(public_path('storage/uploads'), $fileName);
    $input['requirements_files'] = "storage/uploads/$fileName";


    $user = MBKMEksternal::create($input);

    Alert::success('Berhasil daftar!')->flash();
    return back();
}

}
    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {


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
        CRUD::setValidation(MBKMEksternalRequest::class);



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
