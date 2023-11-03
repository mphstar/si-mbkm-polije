<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\MbkmRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Models\Mbkm;
use App\Models\RegisterMbkm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Prologue\Alerts\Facades\Alert;

/**
 * Class MbkmCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class MbkmCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    
    private function getFieldsData()  {
        
    }
    public function setup()
    {
        CRUD::setModel(\App\Models\Mbkm::class);
        CRUD::setRoute('admin/mbkm');
        CRUD::setEntityNameStrings('MBKM', 'Program MBKM');
    }
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
        ], 'info']);
        $this->crud->addButtonFromView('line', 'reg_mbkm', 'reg_mbkm', 'end');
        CRUD::addClause('where', 'capacity', '>', '0');
        CRUD::addClause('where', 'status_acc', '=', 'accepted');
        CRUD::addClause('where', 'is_active', '=', 'active');
    }

    public function register($id) 
    {
        $user = backpack_auth()->user();
        $accReg = RegisterMbkm::where('student_id', backpack_auth()->user()->id)->where('status',  'accepted')->get();
        $pendingReg = RegisterMbkm::where('student_id', backpack_auth()->user()->id)->where('status', 'pending', )->get();
        // return dd($sudahReg);
        if($accReg->count() > 0){
            Alert::error('Anda sudah daftar')->flash();
            return back();
        }
        if($pendingReg->count() > 0){
            Alert::warning('Anda tidak dapat mendaftar jika status pendaftaran sebelumnya masih pending')->flash();
            return back();
        }
        $mbkm = Mbkm::with('partner')->where('mbkms.id', $id)->get();
        $crud = $this->crud;
        return view('vendor.backpack.crud.register_mbkm', compact('mbkm', 'crud', 'user'));
    }

    public function addreg(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:zip,rar'
        ]);

        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            Alert::error($messages[0])->flash();
            return back()->withInput();
        }
        $input = $request->all();

        $file = $request->file('file')->getClientOriginalName();
        $fileName = time().'.'.$request->file('file')->getClientOriginalExtension();
 
        $request->file('file')->move(public_path('storage/uploads'), $fileName);
        $input['requirements_files'] = "storage/uploads/$fileName";
        $user = RegisterMbkm::create($input);
        Alert::success('Berhasil Mendaftar!')->flash();
        return redirect('admin/mbkm');
    }
}
