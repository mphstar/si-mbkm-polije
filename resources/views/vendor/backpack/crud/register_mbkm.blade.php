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
@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
@section('header')
	<section class="container-fluid d-print-none">
    	<a href="javascript: window.print();" class="btn float-right"><i class="la la-print"></i></a>
		<h2>
	        <span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</span>
	        <small>{!! $crud->getSubheading() ?? mb_ucfirst(trans('backpack::crud.preview')).' '.$crud->entity_name !!}.</small>
	        @if ($crud->hasAccess('list'))
	          <small class=""><a href="{{ url($crud->route) }}" class="font-sm"><i class="la la-angle-double-left"></i> {{ trans('backpack::crud.back_to_all') }} <span>{{ $crud->entity_name_plural }}</span></a></small>
	        @endif
	    </h2>
    </section>
@endsection

@section('content')
<div class="col-lg-8">
	<form class="form" action="{{'addreg'}}" enctype="multipart/form-data" method="post">
		{!! csrf_field() !!}
		<div class="card padding-10">
			<div class="card-header">
				<h4>Pendaftaran MBKM</h4>
				{{-- {{ trans('backpack::base.update_account_info') }} --}}
			</div>
			<div class="card-body backpack-profile-form bold-labels">
				<div class="row">
					<div class="col-md-6 form-group">
						<label class="required">Nama Instansi</label>
						<input required class="form-control" type="text" name="" value="{{ $mbkm[0]->partner->partner_name }}" readonly>
					</div>
					<div class="col-md-6 form-group">
						<label class="required">Nama Program</label>
						<input required class="form-control" type="text" name="mbkm" value="{{ $mbkm[0]->program_name }}" readonly>
					</div>
					<div class="col-md-6 form-group">
						<label class="required">File Persyaratan       </label>
						<input required class="form-control" type="file" name="file" placeholder="file harus berupa zip" value="" >
						<div class="text-danger">*Jenis file yang diizinkan: .zip.</div>

					</div>
					<input required class="" type="hidden" name="mbkm_id" value="{{ $mbkm[0]->id }}" >
					<input required class="" type="hidden" name="student_id" value="{{backpack_auth()->user()->id}}" >
				</div>
			</div>
			<div class="card-footer">
				<button type="submit" class="btn btn-success"><i class="la la-save"></i> {{ trans('backpack::base.save') }}</button>
				<a href="{{ backpack_url() }}" class="btn">{{ trans('backpack::base.cancel') }}</a>
			</div>
		</div>
	</form>
</div>
@endsection


@section('after_styles')
	<link rel="stylesheet" href="{{ asset('packages/backpack/crud/css/crud.css').'?v='.config('backpack.base.cachebusting_string') }}">
	<link rel="stylesheet" href="{{ asset('packages/backpack/crud/css/show.css').'?v='.config('backpack.base.cachebusting_string') }}">
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
