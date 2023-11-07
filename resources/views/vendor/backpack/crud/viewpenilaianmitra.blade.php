@extends(backpack_view('blank'))

@php
  $defaultBreadcrumbs = [
    trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
    $crud->entity_name_plural => url($crud->route),
    trans('backpack::crud.preview') => false,
  ];
  // if breadcrumbs aren't defined in the CrudController, use the default breadcrumbs
  $breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;
@endphp

@section('header')
	<section class="container-fluid d-print-none">
    	<a href="javascript: window.print();" class="btn float-right"><i class="la la-print"></i></a>
		<h2>
	        <span class="text-capitalize">Fitur upload Nilai untuk Peserta MBKM</span>
            <br>
	        <small>untuk upload bisa tekan tombol   <i class="las la-upload"></i></small>
	        @if ($crud->hasAccess('list'))
	          <small class=""><a href="{{ url($crud->route) }}" class="font-sm"><i class="la la-angle-double-left"></i> {{ trans('backpack::crud.back_to_all') }} <span>{{ $crud->entity_name_plural }}</span></a></small>
	        @endif
	    </h2>
    </section>
@endsection
@section('content')
<div class="row">


        

    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Nama Mahasiswa</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Nama program MBKM</th>
                                <th class="text-center">Action</th>
                               
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $index = 1;
                            @endphp
                            @foreach($pendaftar as $pdftr)
                            <tr>
                                <td class="text-center">{{ $index }}</th>
                                <td class="text-center">{{ $pdftr->student->name }}</th>
                                <td class="text-center">
                                    @if($pdftr->status === 'accepted')
                                        <span class="badge badge-success px-2">{{ $pdftr->status }}</span>
                                    @elseif($pdftr->status === 'pending')
                                        <span class="badge badge-warning px-2">{{ $pdftr->status }}</span>
                                    @elseif($pdftr->status === 'rejected')
                                        <span class="badge badge-danger px-2">{{ $pdftr->status }}</span>
                                        @else
                                        <span class="badge badge-success px-2">{{ $pdftr->status }}</span>
                                    @endif
                                </td>
                              
                                <td class="text-center">{{$pdftr->mbkm->program_name}}</td>
          
                                <td class="text-center">
                                    @if ($pdftr->status==='done')
                                    <a href="{{ route('grader_partner', ['id' => $pdftr->id]) }}" class="btn btn-xs btn-default"><i class="las la-upload"></i></a>
                                 
                                        
                                    @elseif($pdftr->status==='success')
                                    <a href="{{ route('grader_partner', ['id' => $pdftr->id]) }}" class="btn btn-xs btn-success"><i class="las la-upload"></i></a> 
                                    @endif

                                  
    

                                </td>
                            </tr>
                            @php
                                $index++;
                            @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>    

@endsection