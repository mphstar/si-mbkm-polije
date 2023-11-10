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
            <span class="text-capitalize">Validasi Partner</span>
            <br>

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
                                    <th>Partner Name</th>
                                    <th>addres</th>
                                    <th>phone</th>
                                    <th>email</th>
                                    <th>jenis mitra</th>
                                    <th>status</th>
                                    <th>action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($partners as $row)
                                    <tr>
                                        <td>{{ $row->name }}</td>
                                        <td>{{ $row->alamat }}</td>
                                        <td>{{ $row->phone }}</td>
                                        <td>{{ $row->email }}</td>
                                        <td>{{ $row->jenis_mitra }}</td>
                                        <td>{{ $row->status }}</td>
                                        <td>
                                            <button class="btn btn-primary btn-sm update-status-btn" data-toggle="modal"
                                                data-target="#updateStatusModal{{ $row->id }}"
                                                data-partner-id="{{ $row->id }}">Update Status</button>
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
    @foreach ($partners as $row)
        <form action="{{ route('ubah_status', ['id' => $row->id]) }}" method="post">
            @csrf
            <div class="modal fade" id="updateStatusModal{{ $row->id }}" tabindex="-1" role="dialog"
                aria-labelledby="updateStatusModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="updateStatusModalLabel">Update Status</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="partnerId" value="{{ $row->id }}">
                            <div class="form-group">
                                <label for="newStatus">New Status</label>
                                <select class="form-control" id="newStatus" name="newStatus">
                                    <option value="accepted">accepted</option>
                                    <option value="rejected">rejected</option>
                                    <option value="pending">pending</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" id="updateStatus">Update</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    @endforeach
@endsection

@section('after_scripts')
<script>
    $(document).on('show.bs.modal', '.modal', function() {
        $(this).appendTo('body');
    });
</script>

@endsection