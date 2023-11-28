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
  <div class="container-fluid">
    <h2>
      <span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</span>
      <small id="datatable_info_stack">{!! $crud->getSubheading() ?? '' !!}</small>
    </h2>
  </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            {{-- <div class="col-6 col-sm-4 col-md-2 col-xl mb-3 mb-xl-0">
                <a href="template-nilai/HalamanTambah">
                    <button type="button" class="btn btn-primary">tambah</button>
                </a>
            </div> --}}
            <div class="card">
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th class="text-center" hidden>id</th>
                                <th class="text-center">Nama file</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ( $file as $files )
                            <tr>
                                <td class="text-center" hidden>{{ $files->id }}</td>
                                <td class="text-center">{{ $files->nama }}</td>
                                <td class="text-center">
                                   <a href="{{ route('unduhfile',['id' => $files->id]) }}"><button class="btn btn-primary">download</button></a>
                                   <a href="{{ route('HalamanEditFile',['id' => $files->id]) }}"><button class="btn btn-warning">update</button></a>
                                </td>
                            </tr>
                            @endforeach
                         </tbody>
                    </table>
                </div>
                </div>
                </div>
            </div>
        </div>
    @endsection


