@extends(backpack_view('blank'))

@php
    $defaultBreadcrumbs = [
        trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
        $crud->entity_name_plural => url($crud->route),
        trans('backpack::crud.add') => false,
    ];

    // if breadcrumbs aren't defined in the CrudController, use the default breadcrumbs
    $breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;
@endphp

@section('header')
    <section class="container-fluid">
        <h2>
            <span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</span>
            <small>{!! $crud->getSubheading() ?? trans('backpack::crud.add') . ' ' . $crud->entity_name !!}.</small>

            @if ($crud->hasAccess('list'))
                <small><a href="{{ url($crud->route) }}" class="d-print-none font-sm"><i
                            class="la la-angle-double-{{ config('backpack.base.html_direction') == 'rtl' ? 'right' : 'left' }}"></i>
                        {{ trans('backpack::crud.back_to_all') }} <span>{{ $crud->entity_name_plural }}</span></a></small>
            @endif
        </h2>
    </section>
@endsection

@section('content')
    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" style="display: none;"
        aria-hidden="true">
        <form action="{{ 'upload-laporan-ttd' }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Upload Surat</h4>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="col-md-6 form-group">
                            <label class="required">File Surat</label>
                            <input required class="form-control" type="file" name="file_surat" accept=".pdf">
                            <div class="text-danger">*Jenis file yang diizinkan: .pdf.</div>
                        </div>
                        <input name="id" value="{{ $data->id }}" type="hidden">
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                        <button class="btn btn-primary" type="submit">Save changes</button>
                    </div>
                </div>
                <!-- /.modal-content-->
            </div>
            <!-- /.modal-dialog-->
        </form>
    </div>
    <div class="row">
        <div class="{{ $crud->getCreateContentClass() }}">
            <!-- Default box -->

            @include('crud::inc.grouped_errors')

            <div class="card">
                <div class="card-header"><strong>{{ $data->student->name }}</strong></div>
                <div class="card-body ">
                    <p>Unduh laporan yang akan ditandatangani <a href="/{{ $data->file_surat }}">disini</a>.<br>
                        Upload laporan yang akan ditandatangani <a data-target="#myModal" data-toggle="modal"
                            href="#">disini</a>.</p>

                </div>
            </div>

            <div class="card">
                <div class="card-header"><i class="fa fa-align-justify"></i><strong> List Pengajuan</strong></div>
                <div class="card-body">
                    <table class="table table-responsive-sm">
                        <thead>
                            <tr>
                                <th>Nama Program</th>
                                <th>Jenis MBKM</th>
                                {{-- <th>Jumlah SKS</th> --}}
                                <th>Bukti Diterima</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data->detail as $item)
                                <tr>
                                    <td>{{ $item->nama_program }}</td>
                                    <td>{{ $data->jenismbkm->jenismbkm }}</td>
                                    {{-- <td>{{ $item->jumlah_sks }}</td> --}}
                                    <td>
                                        @if ($item->file_diterima)
                                            <a href="/{{ $item->file_diterima }}"><button
                                                    class="btn btn-block btn-sm btn-link text-left px-0 active">Download
                                                    disini</button></a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->status == 'pengajuan')
                                            <span class="badge badge-warning">
                                                Menunggu
                                            </span>
                                        @elseif($item->status == 'diterima')
                                            <span class="badge badge-success">
                                                Diterima
                                            </span>
                                        @elseif($item->status == 'diambil')
                                            <span class="badge badge-primary">
                                                Diambil
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->status == 'diterima')
                                            <button
                                                class="btn btn-block btn-sm btn-link text-left px-0 active">Ambil</button>
                                        @elseif ($item->status == 'pengajuan' && $item->file_diterima)
                                            <button
                                                class="btn btn-block btn-sm btn-link text-left px-0 active">Terima</button>
                                        @else
                                            <button class="btn btn-block btn-sm btn-link text-left px-0 active">-</button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('after_scripts')
    <script>
        $(document).on('show.bs.modal', '.modal', function() {
            $(this).appendTo('body');
        });
    </script>
@endsection
