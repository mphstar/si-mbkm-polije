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
      
    </section>
@endsection
@section('content')
    <div class="row">




        <div class="col-md-12">
            <h2>Riwayat peserta MBKM Internal</h2><br>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Nama Mahasiswa</th>
                                    <th class="text-center">NIM</th>
                                    <th class="text-center">Semester</th>
                                    <th class="text-center">Nama Mitra </th>
                                    <th class="text-center">Nama Program MBKM</th>
                                    <th class="text-center">Jenis Program MBKM</th>
                                    <th class="text-center">Nama Dosen Pembimbing</th>
                                    <th class="text-center">Status</th>
                                

                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $index = 1;
                                @endphp
                                @foreach ($datamhs as $pdftr)
                                    <tr>
                                        <td class="text-center">{{ $index }}</td>
                                        <td class="text-center">{{ $pdftr->student->name }}</td>
                                        <td class="text-center">{{ $pdftr->student->nim }}</td>
                                        <td class="text-center">{{ $pdftr->student->semester }}</td>

                                        <td class="text-center">{{ $pdftr->mbkm->partner->partner_name }}</td>
                                        <td class="text-center">{{ $pdftr->mbkm->program_name }}</td>
                                        <td class="text-center">

                                            {{ $pdftr->mbkm->jenismbkm->jenismbkm }}
                                         
                                        </td>
                                        <td class="text-center">

                                            {{ $pdftr->lecturer->lecturer_name }}
                                         
                                        </td>
                                        <td class="text-center">

                                            <span class="badge bg-success">selesai</span>
                                         
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
