<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\MbkmRequest;
use App\Mail\daftarmbkm;
use App\Models\ManagementMBKM;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Models\Mbkm;
use App\Models\RegisterMbkm;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
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
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    
    private function getFieldsData()  {
        
    }
    public function setup()
    {
        CRUD::setModel(\App\Models\ManagementMBKM::class);
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
        ], [
            'name' => 'info',
            'label' => "Keterangan"
        ]]);
        $this->crud->addButtonFromView('line', 'reg_mbkm', 'reg_mbkm', 'end');
        CRUD::addClause('where', 'capacity', '>', '0');
        CRUD::addClause('where', 'status_acc', '=', 'accepted');
        CRUD::addClause('where', 'is_active', '=', 'active');
        
        $now = Carbon::now();
        CRUD::addClause('whereDate', 'start_reg', '<=', $now);
        CRUD::addClause('whereDate', 'end_reg', '>=', $now);
        $user = backpack_auth()->user()->with('student')->whereHas('student', function($query){
            return $query->where('users_id', backpack_auth()->user()->id);
        })->first();
        CRUD::addClause('where', 'jurusan', '=', $user->student->jurusan);
        CRUD::addClause('where', 'semester', '=', $user->student->semester);


    }
    protected function setupShowOperation()
    {
        // by default the Show operation will try to show all columns in the db table,
        // but we can easily take over, and have full control of what columns are shown,
        // by changing this config for the Show operation 
        $this->crud->set('show.setFromDb', false);



        $this->crud->addColumn([
            'name' => 'partner.partner_name',
            'label' => 'Nama Mitra',
        ]);
        $this->crud->addColumn([
            'name' => 'program_name',
            'label' => 'Nama Program',
        ]);
        $this->crud->addColumn([
            'name' => 'start_date',
            'label' => 'Tanggal Mulai program MBKM',
        ]);
        $this->crud->addColumn([
            'name' => 'end_date',
            'label' => 'Tanggal Selesai Program MBKM ',
        ]);
        $this->crud->addColumn([
            'name' => 'start_reg',
            'label' => 'Tanggal awal pendaftaran',
        ]);
        $this->crud->addColumn([
            'name' => 'end_reg',
            'label' => 'Tanggal terakhir pendaftaran',
        ]);
        $this->crud->addColumn([
            'name' => 'info',
            'label' => 'Keterangan MBKM'
        ]);
        $this->crud->addColumn([
            'name' => 'semester',
            'label' => 'Berlaku Bagi semester'
        ]);
        $this->crud->addColumn([
            'name' => 'nama_penanggung_jawab',
            'label' => 'Nama Penanggung Jawab'
        ]);
        $this->crud->addColumn([
            'name' => 'jenismbkm.jenismbkm',
            'label' => 'Jenis MBKM'
        ]);
        $this->crud->addColumn([
            'name' => 'jenismbkm.jenismbkm',
            'label' => 'Jenis MBKM'
        ]);
        $this->crud->addColumn([
            'name' => 'jumlah_sks',
            'label' => 'Jumlah SKS'
        ]);
        $this->crud->addColumn([
            'name' => 'task_count',
            'label' => 'Laporan harus dikerjakan'
        ]);
        // $this->crud->addColumn([
        //     'name' => 'jurusan',
        //     'label' => 'Jurusan'
        // ]);

        $this->crud->addColumn([
            'name'  => 'jurusan',
            'label' => 'Jurusan', // Table column heading
            'type'  => 'model_function',
            'function_name' => "getTextJurusan"
        ]);


        // $this->crud->addColumn([
        //     'name'  => 'status_acc',
        //     'label' => 'Status ACC', // Table column heading
        //     'type'  => 'model_function',
        //     'function_name' => 'getStatusSpan'
        // ]);
        // $this->crud->addColumn([
        //     'name'  => 'is_active',
        //     'label' => 'Status Active', // Table column heading
        //     'type'  => 'model_function',
        //     'function_name' => 'getIsactiveSpan'
        // ]);

        // $this->crud->removeColumn('date');
        // $this->crud->removeColumn('extras');

        // Note: if you HAVEN'T set show.setFromDb to false, the removeColumn() calls won't work
        // because setFromDb() is called AFTER setupShowOperation(); we know this is not intuitive at all
        // and we plan to change behaviour in the next version; see this Github issue for more details
        // https://github.com/Laravel-Backpack/CRUD/issues/3108
    }

    public function register($id) 
    {
        $user = backpack_auth()->user()->with('student')->whereHas('student', function($query){
            return $query->where('users_id', backpack_auth()->user()->id);
        })->first();
        
        $accReg = RegisterMbkm::where('student_id', $user->student->id)->where('status',  'accepted')->get();
        $pendingReg = RegisterMbkm::where('student_id', $user->student->id)->where('status', 'pending', )->get();
        // return dd($sudahReg);
        if($accReg->count() > 0){
            session()->flash('test', 'sudah mendaftar');
            Alert::error('Anda sudah daftar')->flash();
            return back();
        }
        if($pendingReg->count() > 0){
            session()->flash('test', 'pending');
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
            'file' => 'required|file|mimes:zip,rar|max:10000'
        ]);

        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            session()->flash('status', 'fileNotValid');
            Alert::error($messages[0])->flash();
            return back()->withInput();
        }
        $input = $request->all();

        $file = $request->file('file')->getClientOriginalName();
        $fileName = time().'.'.$request->file('file')->getClientOriginalExtension();
 
        $request->file('file')->move(public_path('storage/uploads'), $fileName);
        $input['requirements_files'] = "storage/uploads/$fileName";
        $user = RegisterMbkm::create($input);

        
        
        $dataaa=RegisterMbkm::with(['mbkm.partner.user','student'])->where('id',$user->id)->first();
try {
    Mail::to($dataaa->mbkm->partner->user->email)->send(new daftarmbkm($dataaa));
} catch (\Throwable $th) {
    Alert::warning('gagal send email')->flash();  
}
     
        session()->flash('status', 'success');
        Alert::success('Berhasil Mendaftar!')->flash();
        return redirect('admin/mbkm');
    }
}
