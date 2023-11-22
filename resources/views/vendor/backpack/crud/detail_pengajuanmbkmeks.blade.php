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

    </section>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <h5 class="">Anda Hanya dapat Memilih <strong>satu</strong> program MBKM yang ingin Anda ambil</h5>
            <h6>Untuk memilih program Anda cukup menekan tombol yang ada pada kolom status</h6>
            <div class="col-md-2">
                <a href="{{ backpack_url('m-b-k-m-eksternal') }}"
                    class="btn btn-sm btn-block btn-outline-danger mb-3 mt-2">Kembali</a>
            </div>
            {{-- <button  type="button" class="btn btn-primary mr-2 mb-5" data-toggle="modal"
          data-target="#daftarexternal">Daftar PROGRAM </button> --}}
            <div class="col-md-2 mb-3 mt-3">

            </div>
            <div class="card">

                <div class="card-body">



                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Nama Mitra</th>
                                    <th class="text-center">Nama Program</th>
                                    <th class="text-center">Status</th>

                                    {{-- <th class="text-center">Upload Bukti Terima</th> --}}

                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $index = 1;
                                @endphp
                                @foreach ($detail_pengajuan as $item)
                                    <tr>
                                        <td class="text-center">{{ $index }}</th>
                                        <td class="text-center">{{ $item->partner->partner_name }}</td>

                                        <td class="text-center">{{ $item->nama_program }}</td>


                                        @if ($item->status == 'pengajuan')
                                            <td class="text-center"><button type="button"
                                                    class="btn btn-warning mr-2 mb-5 " data-toggle="modal"
                                                    data-target="#ubahstatus"onclick="ubah({{ $item }})">{{ $item->status }}
                                                </button></td>
                                        @elseif($item->status == 'diterima')
                                            @if (count($cek_status) != 0)
                                                ;
                                                <td class="text-center"><button disabled type="button"
                                                        class="btn btn-primary mr-2 mb-5">{{ $item->status }} </button></td>
                                            @else
                                                <td class="text-center"><button type="button"
                                                        class="btn btn-primary mr-2 mb-5" data-toggle="modal"
                                                        data-target="#uploadsk"onclick="edit({{ $item }})">{{ $item->status }}
                                                    </button></td>
                                            @endif
                                        @elseif($item->status == 'diambil')
                                            <td class="text-center"><button disabled type="button"
                                                    class="btn btn-success mr-2 mb-5">{{ $item->status }}</button></td>
                                        @elseif($item->status == 'ditolak')
                                            <td class="text-center"><button disabled type="button"
                                                    class="btn btn-danger mr-2 mb-5">{{ $item->status }}</button></td>
                                        @endif


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

    <div class="modal fade" id="uploadsk" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Upload Pengajuan</h5>
                    <form action="{{ 'ambilmbkmek' }}" method="post" enctype="multipart/form-data"id="myForm">
                        @csrf
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="status">Keputusan Di ambil</label>
                        <select class="form-control" name="status">

                            <option value="diambil">diambil</option>

                        </select>
                    </div>
                    <div class="form-group">
                        <label for="status">Nama program</label>


                        <input type="text"class="form-control" name="nama_program" id="nama_prog" readonly>

                    </div>
                    <div class="form-group">
                        <label class="required">Upload bukti di terima</label>
                        <input required class="form-control" type="file" name="file_diterima" accept=".pdf">
                        <div class="text-danger">*Jenis file yang diizinkan: .pdf.</div>
                    </div>
                    <input type="hidden" name="id" class="form-control-file" id="idModal">
                    <input type="hidden" name="student_id"value={{ $siswa->id }}>
                    <input type="hidden" name="semester"value={{ $siswa->semester }}>
                    <input type="hidden" name="id_jenis"value={{ $idjenis }}>
                    <input type="hidden" name="partner_id" id="id_partner">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="confirmSubmission()">Save changes</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="ubahstatus" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Upload Pengajuan</h5>
                    <form action="{{ 'ambilmbkmek' }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="status">Status terima</label>
                        <select class="form-control" name="status">

                            <option value="diterima">diterima</option>
                            <option value="ditolak">ditolak</option>

                        </select>
                    </div>


                    <input type="hidden" name="id" class="form-control-file" id="idubah">


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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).on('show.bs.modal', '.modal', function() {
            $(this).appendTo('body');
        });

        function edit(data) {
            console.log(data);
            document.getElementById("idModal").value = data.id;
            document.getElementById("id_partner").value = data.partner_id;
            document.getElementById("nama_prog").value = data.nama_program;

        }

        function confirmSubmission() {
            Swal.fire({
                title: "Apakah Anda yakin?",
                text: "Data yang dipilih tidak dapat diubah lagi",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya",
                cancelButtonText: "Tidak"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('myForm').submit();
                }
            });

        }

        function ubah(data) {
            console.log(data);
            document.getElementById("idubah").value = data.id;


        }
    </script>
@endsection
