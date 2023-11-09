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
                                <th>{{ $result[0]->nama_mahasiswa }}</th>
                                <th></th>
                                <th></th>
                                <th>
                                    <form action="{{ route('uprove', ['id' => $result[0]->id, 'approval' => 'approved']) }}" method="GET">
                                        {{ csrf_field() }}
                                        <button type="submit" class="btn btn-primary">Accepted</button>
                                    </form>
                                </th>
                             <!-- Inside your HTML -->
<th>
    {{-- <button type="button" id="showModalButton" class="btn btn-primary">Revisi</button> --}}
    <button class="btn btn-primary btn-sm update-status-btn" data-toggle="modal" data-target="#updateStatusModal" data-partner-id="">Update Status</button>

</th>

                                <th>
                                    @if ($result->count() > 0 && $result[0]->setuju === 'approved')
                                        <button id="btnPrint">Print</button>
                                    @else
                                        <button disabled>Print</button>
                                    @endif
                                </th>

                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>matkul</td>
                                <td>nilai</td>
                                <td></td>
                                <td>nilai akademik</td>
                            </tr>
                            @foreach ($result as $row)
                            <tr>
                                <td>{{ $row->matkul }}</td>
                                <td>{{ $row->nilai }}</td>
                                <td></td>
                                <td>
                                    @if ($row->nilai >= 80 && $row->nilai <= 100)
                                        A
                                    @elseif ($row->nilai >= 75 && $row->nilai < 80)
                                        B+
                                    @elseif ($row->nilai >= 60 && $row->nilai < 75)
                                        B
                                    @elseif ($row->nilai >= 0 && $row->nilai < 60)
                                        C
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
<form action="{{ route('tolak', ['id' => $result[0]->id, 'not_aprroval' => 'Not approved']) }}" method="post">
    @csrf
    <div class="modal fade" id="updateStatusModal" tabindex="-1" role="dialog" aria-labelledby="updateStatusModalLabel" aria-hidden="true" data-backdrop="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">revisi nilai</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                       {{ csrf_field() }}
                       <div class="form-group">
                          <label for="deskripsi">jelaskan nilai mana yang harus di revisi </label>
                          <textarea class="form-control" name="deskripsi" id="deskripsi"></textarea>
                       </div>
                    </form>
                 </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary" id="simpanData">Save changes</button>
                </div>
              </div>
        </div>
    </div>
</form>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/modal.js') }}"></script>

<script>
    document.getElementById('btnPrint').addEventListener('click', function(event) {
        event.preventDefault(); // Mencegah aksi default dari tombol

        if ({{ $result[0]->setuju === 'approved' ? 'true' : 'false' }}) {
            // console.log($result);
            window.print();
        } else {
            alert('Data belum disetujui. Anda tidak bisa mencetak sampai data disetujui.');
        }
    });

    document.getElementById('showModalButton').addEventListener('click', function () {
        document.getElementById('myModal').style.display = 'block';
    });

    document.querySelectorAll('[data-dismiss="modal"]').forEach(function (element) {
        element.addEventListener('click', function () {
            document.getElementById('myModal').style.display = 'none';
        });
    });
</script>
@endsection
