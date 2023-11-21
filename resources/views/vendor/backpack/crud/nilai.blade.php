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
            <span class="text-capitalize">Fitur accepted nilai</span>
            <br>
            {{-- <small>untuk download file bisa tekan tombol <i class="las la-download"></i></small> --}}
            {{-- @if ($crud->hasAccess('list'))
                <small class=""><a href="{{ url($crud->route) }}" class="font-sm"><i class="la la-angle-double-left"></i>
                        {{ trans('backpack::crud.back_to_all') }} <span>{{ $crud->entity_name_plural }}</span></a></small>
            @endif --}}
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
                                    <th class="text-center">ID Pendaftaran</th>
                                    <th class="text-center">Nama MBKM</th>
                                    <th class="text-center">Mahasiswa</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($nilai as $data)
                                    <tr>
                                        <td class="text-center">{{ $data->id }}</td>
                                        <td class="text-center">{{ $data->mbkm_id == null ? $data->jenis_mbkm : $data->mbkm->program_name }}</td>
                                        <td class="text-center">{{ $data->students->name }}</td>
                                        <td class="text-center"><a
                                                href="{{ route('detail_grade', [$data->id]) }}"><button class="btn btn-primary">detail</button></a>
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
