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
        <span class="text-capitalize">Dataku</span>


    </h2>
</section>
@endsection
@section('content')
<div class="row">
    <div class="col-md-8">

        <!-- Default box -->
        <div class="">
            <div class="card no-padding no-border">
                <table class="table table-striped mb-0">
                    <tbody>
                        <tr>
                            <td>
                                <strong>Nama Partner</strong>
                            </td>
                            <td>
                                <span>
                                    {{ $data_mitra->partner->partner_name }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Alamat:</strong>
                            </td>
                            <td>
                                <span>
                                    {{ $data_mitra->partner->address }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>No telfon:</strong>
                            </td>
                            <td>
                                <span>
                                    {{ $data_mitra->partner->phone }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Status:</strong>
                            </td>
                            <td>
                                @if ($data_mitra->partner->status == "pending")
                                <span class="badge bg-warning">{{ $data_mitra->partner->status }}</span>
                                @elseif( $data_mitra->partner->status == "accepted")
                                <span class="badge bg-success">{{ $data_mitra->partner->status }}</span>
                                @else
                                <span class="badge bg-danger">{{ $data_mitra->partner->status }}</span>
                                @endif

                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Jenis mitra:</strong>
                            </td>
                            <td>
                                <span>
                                    {{ $data_mitra->partner->jenis_mitra }}
                                </span>
                            </td>
                        </tr>

                        <tr>
                            <td><strong>Actions</strong></td>
                            <td>

                                <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#ubahdata">ubah</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div><!-- /.box-body -->
        </div><!-- /.box -->

    </div>
</div>
<div class="modal fade" id="ubahdata" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ubah Data</h5>
                <form action="{{ 'ubahbiodata' }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="status">Nama Mitra</label>
                    <input type="text" class="form-control" name="partner_name"value="{{ $data_mitra->partner->partner_name }}">
                </div>
                <div class="form-group">
                    <label for="status">Alamat</label>
                    <input type="text" class="form-control" name="address" id="alamat"value="{{ $data_mitra->partner->address }}">
                </div>
                <div class="form-group">
                    <label for="status">No Telfon</label>
                    <input type="text" class="form-control" name="phone" id="no_hp"value="{{ $data_mitra->partner->phone }}">
                </div>

 
                <input type="hidden" name="id" class="form-control" value="{{ $data_mitra->partner->id }}">


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
            </form>
        </div>
    </div>
</div>

@endsection
@section('after_scripts')
<script>
    $(document).on('show.bs.modal', '.modal', function() {
        $(this).appendTo('body');
    });


    function ubahdata(data) {
        console.log(data);
        


    }
</script>
@endsection