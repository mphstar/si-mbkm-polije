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
	        <span class="text-capitalize">Laporan Kegiatan MBKM</span>
	        <small>Data laporan kegiatan MBKM mahasiswa</small>
	        @if ($crud->hasAccess('list'))
	          <small class=""><a href="{{ url($crud->route) }}" class="font-sm"><i class="la la-angle-double-left"></i> {{ trans('backpack::crud.back_to_all') }} <span>{{ $crud->entity_name_plural }}</span></a></small>
	        @endif
	    </h2>
    </section>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12" style="margin-top: 20px;">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Progresmu</h4>
                <div class="progress" style="height: 15px">
                    <div class="progress-bar bg-info active progress-bar-striped" style="width: 50%;" role="progressbar">50%</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    <h4>Table Striped</h4>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th>1</th>
                                <td>Kolor Tea Shirt For Man</td>
                                <td><span class="badge badge-primary px-2">Sale</span>
                                </td>
                                <td>January 22</td>
                                <td class="color-primary">$21.56</td>
                            </tr>
                            <tr>
                                <th>2</th>
                                <td>Kolor Tea Shirt For Women</td>
                                <td><span class="badge badge-danger px-2">Tax</span>
                                </td>
                                <td>January 30</td>
                                <td class="color-success">$55.32</td>
                            </tr>
                            <tr>
                                <th>3</th>
                                <td>Blue Backpack For Baby</td>
                                <td><span class="badge badge-success px-2">Extended</span>
                                </td>
                                <td>January 25</td>
                                <td class="color-danger">$14.85</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>    
@endsection