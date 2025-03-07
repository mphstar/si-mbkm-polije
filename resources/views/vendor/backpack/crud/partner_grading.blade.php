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
	<form class="form" action="{{'penilaian'}}" enctype="multipart/form-data" method="post">
		{!! csrf_field() !!}
		<div class="card padding-10">
			<div class="card-header">
				<h4>Penilaian</h4>
				{{-- <p>Download template penilaian <a href="{{ route('penilaian-mitra.unduhtemplate', 'Template Penilaian Mitra') }}">disini</a></p> --}}
				{{-- {{ trans('backpack::base.update_account_info') }} --}}
			</div>
			<div class="card-body backpack-profile-form bold-labels">
				<div class="row">
					
					<div class="col-md-6 form-group">
						<label class="required">File Penilaian</label>
						<input required class="form-control" type="file" name="file" value="" accept=".pdf">
						<div class="text-danger">*Jenis file yang diizinkan: .pdf.</div>
					</div>
					
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
