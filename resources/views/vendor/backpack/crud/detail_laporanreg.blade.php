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
                <small class=""><a href="{{ url($crud->route) }}" class="font-sm"><i class="la la-angle-double-left"></i>
                        {{ trans('backpack::crud.back_to_all') }} <span>{{ $crud->entity_name_plural }}</span></a></small>
            @endif
        </h2>
    </section>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12" style="margin-top: 20px;">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Progres dari mahasiswa</h4>
                    <div class="progress" style="height: 15px">
                        <div class="progress-bar bg-primary active progress-bar-striped"
                            style="width: {{ $count }}%;" role="progressbar">{{ $count }}%</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">

        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Tanggal Upload</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">File</th>
                                    <th class="text-center">Keteragan dari mitra</th>
                                    <th class="text-center">Aksi</th>

                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $index = 1;
                                @endphp
                                @foreach ($laporan as $report)
                                    <tr>
                                        <th class="text-center">{{ $index }}</th>
                                        <th class="text-center">{{ $report->upload_date }}</th>
                                        <td class="text-center">
                                            @if ($report->status === 'accepted')
                                                <span class="badge badge-success px-2">diterima</span>
                                            @elseif($report->status === 'pending')
                                                <span class="badge badge-warning px-2">proses</span>
                                            @elseif($report->status === 'rejected')
                                                <span class="badge badge-danger px-2">ditolak</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span>
                                                <a href="/{{ $report->file }}" class="btn btn-primary">
                                                    <i class="nav-icon la la-file" style="color:white"></i>
                                                </a>
                                            </span>
                                        </td>
                                        <td class="text-center">{{ $report->notes }}</td>
                                        <td class="text-center">
                                            <span>
                                                @if ($report->status === 'accepted')
                                                <button disabled type="button" class="btn btn-success" data-toggle="modal"
                                                    data-target="#">
                                                    diterima
                                                </button>
                                            @elseif($report->status === 'pending')
                                                <button type="button" class="btn btn-warning" data-toggle="modal"
                                                    data-target="#modaledit"onclick="edit({{  $report }})">proses</button>
                                            @elseif($report->status === 'rejected')
                                                <button disabled type="button" class="btn btn-danger" data-toggle="modal"
                                                    data-target="#modaledit{{ $report->id }}">ditolak</button>

                                                    @endif
                                            </span>
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
 
        <div class="modal fade" id="modaledit" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form action="approve-laporan" method="post" enctype="multipart/form-data">
                    {!! csrf_field() !!}
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Validasi Laporan</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="status">Status Laporan</label>
                                <select class="form-control" id="status"name="status">

                                    <option value="accepted">accepted</option>
                             
                                    <option value="rejected">rejected</option>
                                </select>
                            </div>
                            <div class="form-group">

                                <input type="textarea" class="form-control" id="notes"name="notes"
                                    placeholder="Komentar mitra"required>
                            </div>
                            <input type="hidden" name="id" class="form-control-file" id="idModal">
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
            </div>
            </form>
        </div>
        </div>
    
@endsection
@section('after_styles')
    {{-- <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet"> --}}
    {{-- <link rel="stylesheet" href="{{ asset('packages/backpack/crud/css/show.css').'?v='.config('backpack.base.cachebusting_string') }}"> --}}
@endsection
@section('after_scripts')
    <script>
        $(document).on('show.bs.modal', '.modal', function() {
            $(this).appendTo('body');
        });



      

        function edit(data) {
            console.log(data);
            document.getElementById("idModal").value=data.id;

        }
    </script>
    <script src="{{ asset('packages/backpack/crud/js/crud.js') . '?v=' . config('backpack.base.cachebusting_string') }}">
    </script>
    <script src="{{ asset('packages/backpack/crud/js/show.js') . '?v=' . config('backpack.base.cachebusting_string') }}">
    </script>
@endsection
