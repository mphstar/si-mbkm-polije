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

            @if ($crud->hasAccess('list'))
                <small class=""><a href="{{ url($crud->route) }}" class="font-sm"><i class="la la-angle-double-left"></i>
                        {{ trans('backpack::crud.back_to_all') }} <span>{{ $crud->entity_name_plural }}</span></a></small>
            @endif
        </h2>
    </section>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="col-6 col-sm-4 col-md-2 col-xl mb-3 mb-xl-0">
                <a href="template-nilai/HalamanTambah">
                    <button type="button" class="btn btn-primary">tambah</button>
                </a>
            </div>
            <div class="card">
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th class="text-center" hidden>id</th>
                                <th class="text-center">nama file</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ( $file as $files )
                            <tr>
                                <td class="text-center" hidden>{{ $files->id }}</td>
                                <td class="text-center">{{ $files->nama }}</td>
                                <td class="text-center">
                                   <a href="{{ route('unduhfile',['id' => $files->id]) }}"><button>download</button></a>
                                   <a href="{{ route('hapusFile', ['id' => $files->id]) }}"><button>hapus</button></a>
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


