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
    <div class="row">
        <div class="{{ $crud->getCreateContentClass() }}">
            <!-- Default box -->

            @include('crud::inc.grouped_errors')

            <div class="card">
                <div class="card-header"><strong>{{ $data->student->name }}</strong></div>
                <div class="card-body ">
                    <p>Unduh laporan yang akan ditandatangani <a href="">disini</a>.<br>
                        Upload laporan yang akan ditandatangani <a href="">disini</a>.</p>

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
                                <th>Jumlah SKS</th>
                                <th>Bukti Diterima</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data->detail as $item)
                                <tr>
                                    <td>{{ $item->mbkm->program_name }}</td>
                                    <td>{{ $item->mbkm->jenismbkm->jenismbkm }}</td>
                                    <td>{{ $item->mbkm->jumlah_sks }}</td>
                                    <td>
                                        @if ($item->file_diterima)
                                            <a href="/uploads/{{ $item->file_diterima }}"><button
                                                    class="btn btn-block btn-sm btn-link text-left px-0 active">Download
                                                    disini</button></a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->status == 'pengajuan')
                                            <span class="badge badge-warning">
                                                Pending
                                            </span>
                                        @elseif($item->status == 'diterima')
                                            <span class="badge badge-success">
                                                Diterima
                                            </span>
                                        @elseif($item->status == 'diambil')
                                            <span class="badge badge-primary">
                                                Diterima
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
    </div>
@endsection
