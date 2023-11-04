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
	        <span class="text-capitalize">Fitur validasi  Peserta MBKM</span>
            <br>
	        <small>untuk download file  bisa tekan tombol   <i class="las la-download"></i></small>
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
                                    <button
                                        disabled 
                                        type="button" class="btn btn-success" data-toggle="modal" data-target="#modaledit{{$pdftr->id}}">
                                        {{ $pdftr->status }}
                                    </button>
                                    @elseif($pdftr->status === 'pending')
                                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modaledit{{$pdftr->id}}">{{ $pdftr->status }}</button>
                                    @elseif($pdftr->status === 'rejected')
                                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modaledit{{$pdftr->id}}">{{ $pdftr->status }}</button>
                                        @else
                                        <button
                                        disabled 
                                        type="button" class="btn btn-success" data-toggle="modal" data-target="#modaledit{{$pdftr->id}}">
                                        {{ $pdftr->status }}
                                    </button>
                                    @endif
                                </td>
                              
                                <td class="text-center">{{$pdftr->mbkm->program_name}}</td>
          
                                <td class="text-center">
                                 
                                    <a href="/{{ $pdftr->requirements_files}}" class="btn btn-xs btn-primary"><i class="nav-icon la la-download"></i></a>

                                  
    

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
@foreach ($pendaftar as $item)
<div class="modal fade" id="modaledit{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="validasi-peserta" method="post" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Validasi peserta</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="status">Validasi peserta</label>
                            <select class="form-control" id="status"name="status">
                                <option value="accepted">accepted</option>
                                <option value="pending">pending</option>
                                <option value="rejected">rejected</option>
                            </select>
                        </div>
                    </div>
                    <input  type="hidden" name="id" class="form-control-file" id="fileInput" value="{{$item->id}}">
                 
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endforeach
@endsection
@section('after_styles')
    {{-- <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet"> --}}
	{{-- <link rel="stylesheet" href="{{ asset('packages/backpack/crud/css/show.css').'?v='.config('backpack.base.cachebusting_string') }}"> --}}
@endsection
@section('after_scripts')
    <script>
        $(document).on('show.bs.modal', '.modal', function() {
            $(this).appendTo('body');
       });
    </script>
	<script src="{{ asset('packages/backpack/crud/js/crud.js').'?v='.config('backpack.base.cachebusting_string') }}"></script>
	<script src="{{ asset('packages/backpack/crud/js/show.js').'?v='.config('backpack.base.cachebusting_string') }}"></script>
@endsection