<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\MbkmReportRequest;
use App\Mail\pesertauploadlaporan;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Models\MbkmReport;
use App\Models\RegisterMbkm;
use App\Models\Mbkm;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Prologue\Alerts\Facades\Alert;
/**
 * Class MbkmReportCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class MbkmReportCrudController extends CrudController
{
    // use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    // use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    // use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    // use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    // use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    
    public function viewReport() {
        $crud = $this->crud;

        $user = backpack_auth()->user()->with('student')->whereHas('student', function($query){
            return $query->where('users_id', backpack_auth()->user()->id);
        })->first();
        
$cek=RegisterMbkm::where('status','=','accepted')->where('student_id', $user->student->id)->first();
if (!$cek) {
    session()->flash('status', 'error');
    Alert::error('Anda tidak terdaftar program MBKM')->flash();
    return back();
}else{
if ($cek->id_jenis != null) {
    session()->flash('status', 'error');
    Alert::error('Anda tidak terdaftar program MBKM internal')->flash();
    return back();
}
}

        $mbkmId = RegisterMbkm::with('mbkm')
        ->where('student_id', $user->student->id)
        ->where('status',  'accepted')
        ->whereHas('mbkm', function ($query) {
            $now = Carbon::now();
            $query->whereDate('start_date', '<=', $now)
                  ->whereDate('end_date', '>=', $now);
        })->orderBy('id', 'desc')->get();
        if(isset($mbkmId[0])) {
            $reports = MbkmReport::with('regMbkm')
                ->whereHas('regMbkm', function ($query) use ($mbkmId, $user) {
                $query->where('student_id', $user->student->id)
                ->where('mbkm_id', $mbkmId[0]->mbkm_id);})->get();

            $acceptedCount = $reports->where('status', 'accepted')->count();
            $targetCount = Mbkm::where('id', $mbkmId[0]->mbkm_id)->value('task_count');

          
            $count = round(($acceptedCount / $targetCount) * 100);
            if ($count > 100) {
                $count=100;
            }else{
                $count;
            }
            $today = Carbon::now()->toDateString();
            session()->flash('status', 'success');
            return view('vendor/backpack/crud/report_mbkm', compact('crud', 'reports', 'today', 'count', 'mbkmId'));
        }else{
            session()->flash('status', 'error');
            Alert::warning('Program MBKM anda belum Mulai')->flash();
            return back();
        }
        
    }
    public function upReport(Request $request) {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:pdf'
        ]);

        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            session()->flash('status', 'error');
            Alert::warning($messages[0])->flash();
            return back()->withInput();
        }
        $input = $request->all();

        $file = $request->file('file')->getClientOriginalName();
        $fileName = time().'.'.$request->file('file')->getClientOriginalExtension();

        $request->file('file')->move(public_path('storage/uploads'), $fileName);
        $input['file'] = "storage/uploads/$fileName";
        $input['status'] = 'pending';

        $user = MbkmReport::create($input);
        $tes = MbkmReport::with('regMbkm.mbkm.partner.user')->where('id', $user->id)->first();
        $siswaupload=MbkmReport::with('regMbkm.student')->where('id',$user->id)->first();
        // return $siswaupload;
        $namamhs=$siswaupload->regMbkm->student;
  
        // return $tes;
        $email=$tes->regMbkm->mbkm->partner->user->email;
        try {
            Mail::to($email)->send(new pesertauploadlaporan($namamhs));
        } catch (\Throwable $th) {
            Alert::warning('Kirim Email bermasalah')->flash();
        return back();
        }
 
        session()->flash('status', 'success');
        Alert::success('Berhasil upload laporan!')->flash();
        return back();
    }

    public function revReport(Request $request) {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:pdf'
        ]);
    
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            session()->flash('status', 'error');
            Alert::warning($messages[0])->flash();
            return back()->withInput();
        }
        $report = MbkmReport::where('id', $request->id)->first();
        
        // Hapus file yang ada
        $existingFilePath = $report->file;
        if (file_exists(public_path($existingFilePath))) {
            unlink(public_path($existingFilePath));
        }
    
        // Simpan file yang baru
        $file = $request->file('file')->getClientOriginalName();
        $fileName = time().'.'.$request->file('file')->getClientOriginalExtension();
    
        $request->file('file')->move(public_path('storage/uploads'), $fileName);
        $report->file = "storage/uploads/$fileName";
        $report->status = 'pending';
        $report->save();
        Alert::success('Berhasil mengupdate laporan!')->flash();
        session()->flash('test', 'success');
        return back();
    }
    public function setup()
    {
        CRUD::setModel(\App\Models\MbkmReport::class);
        // CRUD::setModel(\App\Models\RegisterMbkm::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/mbkm-report');
        CRUD::setEntityNameStrings('mbkm report', 'mbkm reports');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        // return dd($this->crud);
        $this->crud->setColumns(['regMbkm.student_id', 'reg_mbkm_id', 'file', 'status', 'upload_date']);
        // CRUD::addClause('where', 'student_id', '=', '1');
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(MbkmReportRequest::class);

        CRUD::field('id');
        CRUD::field('report_mbkm_id');
        CRUD::field('file');
        CRUD::field('status');
        CRUD::field('upload_date');
        CRUD::field('created_at');
        CRUD::field('updated_at');

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