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
    {{-- <section class="container-fluid d-print-none">
        <a href="javascript: window.print();" class="btn float-right"><i class="la la-print"></i></a>

    </section>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <h2>Riwayat peserta MBKM Luar Kampus</h2><br>

            {{-- <button  type="button" class="btn btn-primary mr-2 mb-5" data-toggle="modal"
          data-target="#daftarexternal">Daftar PROGRAM </button> --}}

    <div class="card">

        <div class="card-body">



            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Nama Mahasiswa</th>
                            <th class="text-center">NIM</th>
                            <th class="text-center">Nama Mitra</th>
                            <th class="text-center">Alamat Mitra</th>
                            <th class="text-center">Nama Program</th>
                            <th class="text-center">Jenis Program</th>
                            <th class="text-center">Nama Dosen Pembimbing</th>
                            <th class="text-center">Semester</th>
                            <th class="text-center">Status</th>



                            {{-- <th class="text-center">Upload Bukti Terima</th> --}}


                        </tr>
                    </thead>
            
                  

                    <tbody>
                        @php
                            $index = 1;
                        @endphp
                        @foreach ($datamhs as $item)
                            <tr>
                                <td class="text-center">{{ $index }}</th>
                                <td class="text-center">{{ $item->student->name }}</td>
                                <td class="text-center">{{ $item->student->nim }}</td>
                               
                                <td class="text-center">{{ $item->partner->partner_name }}</td>
                                <td class="text-center">{{ $item->partner->address }}</td>

                                <td class="text-center">{{ $item->program_name }}</td>
                                <td class="text-center">{{ $item->jenismbkm->jenismbkm }}</td>


                                <td class="text-center">{{ $item->lecturer->lecturer_name }}</td>
                                <td class="text-center">{{ $item->semester }}</td>

                                <td class="text-center">

                                    <span class="badge bg-success">selesai</span>

                                </td>
                                {{-- <td class="text-center">
                           
                                  <button  type="button" class="btn btn-primary mr-2 mb-5" data-toggle="modal"
                                  data-target="#uploadsk">Daftar PROGRAM </button></td> --}}
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

