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
            <span class="text-capitalize">Riwayat Peserta MBKM</span>
            <br>

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
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Nama Mahasiswa</th>
                           
                                    <th class="text-center">Nama program MBKM</th>
                                    <th class="text-center">Jenis MBKM</th>
                                    <th class="text-center">Penanggung Jawab</th>
                                    <th class="text-center">Dosen Pembimbing</th>
                                    <th class="text-center">Status</th>
                                

                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $index = 1;
                                @endphp
                                @foreach ($pendaftar as $pdftr)
                                    <tr>
                                        <td class="text-center">{{ $index }}</th>
                                        <td class="text-center">{{ $pdftr->student->name }}</th>

                                        <td class="text-center">{{ $pdftr->mbkm->program_name }}</td>
                                        <td class="text-center">{{ $pdftr->mbkm->jenismbkm->jenismbkm}}</td>
                                        <td class="text-center">{{ $pdftr->mbkm->nama_penanggung_jawab }}</td>
                                        <td class="text-center">{{ $pdftr->lecturer->lecturer_name }}</td>
                                        <td class="text-center">


                                          @if($pdftr->status === 'rejected')
                                                <button disabled type="button" class="btn btn-danger" data-toggle="modal"
                                                    data-target="#modaledit{{ $pdftr->id }}">Tidak diterima</button>
                                            @else
                                                <button disabled type="button" class="btn btn-success" data-toggle="modal"
                                                    data-target="#modaledit{{ $pdftr->id }}">
                                                    selesai
                                                </button>
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
@section('after_styles')
    {{-- <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet"> --}}
    {{-- <link rel="stylesheet" href="{{ asset('packages/backpack/crud/css/show.css').'?v='.config('backpack.base.cachebusting_string') }}"> --}}
@endsection
@section('after_scripts')
   
    <script src="{{ asset('packages/backpack/crud/js/crud.js') . '?v=' . config('backpack.base.cachebusting_string') }}">
    </script>
    <script src="{{ asset('packages/backpack/crud/js/show.js') . '?v=' . config('backpack.base.cachebusting_string') }}">
    </script>
@endsection
