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
                    <form action="/admin/management-m-b-k-m/tambahdatambkm"method="post">
                        @csrf
                        <input type="hidden" value="{{ $id_partner->partner->id }}" class="form-control" name="partner_id">
                        <div class="form-group">
                            <label>Nama Program</label>
                            <input type="text" class="form-control" name="program_name">
                        </div>
                        <div class="form-group">
                            <label>Kapasitas</label>
                            <input type="number" class="form-control" name="capacity">

                        </div>
                        <div class="form-group">
                            <label>Jumlah Task</label>
                            <input type="number" class="form-control" name="task_count">

                        </div>
                        <div class="form-group">
                            <label>Semester </label>
                            <select name="semester" class="form-control">
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                            </select>

                        </div>
                        <div class="form-group">
                            <label>Jumlah SKS </label>
                            <input type="number"onlyinput="sks()" class="form-control" name="jumlah_sks">

                        </div>
                        <div class="form-group">
                            <label>Nama Penanggung Jawab </label>
                            <input type="text" class="form-control" name="nama_penanggung_jawab">

                        </div>

                        <div class="form-group">
                            <label>Tanggal Awal Pendaftaran</label>
                            <input onchange="fungsi()" type="date" class="form-control"id="start_reg" name="start_reg">

                        </div>
                        <div class="form-group">
                            <label>Tanggal Tarakhir Pendaftaran</label>

                            <input onchange="fungsi()" type="date" class="form-control"id="end_reg"
                                name="end_reg"disabled>

                        </div>
                        <div class="form-group">
                            <label>Tanggal Awal Mulai Program</label>
                            <input onchange="fungsi()" type="date" class="form-control"id="start_date"
                                name="start_date"disabled>

                        </div>
                        <div class="form-group">
                            <label>Tanggal Akhir Program</label>
                            <input type="date" class="form-control"id="end_date" name="end_date"disabled>

                        </div>
                        <div class="form-group">
                            <label>Keterangan </label>
                            <textarea class="form-control" rows="3"name="info"></textarea>

                        </div>
                        <div class="form-group">
                            <label >Jurusan</label>
                             
                            <select class="form-control" name="jurusan">
                                @foreach($jurusan as $key)
                                    <option value="{{ $key->uuid }}">{{ $key->unit }}</option>
                                @endforeach
                             
                            </select>
                        </div>
                        <div class="form-group">
                            <label >Jenis MBKM</label>
                             
                            <select class="form-control" name="id_jenis">
                                @foreach($jenis_mbkm as $key)
                                <option value="{{ $key->id }}">{{ $key->jenismbkm }}</option>
                            @endforeach
                             
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        const tanggalAwaldaftar = document.getElementById('start_reg');
        const tanggalAkhirdaftar = document.getElementById('end_reg');
        const tanggalAwalProgram = document.getElementById('start_date');
        const tanggalAkhirProgram = document.getElementById('end_date');

        function fungsi() {
            if (tanggalAwaldaftar.value === "") {

                tanggalAkhirdaftar.setAttribute('disabled', 'disabled');
                tanggalAwalProgram.setAttribute('disabled', 'disabled');
            } else {
                tanggalAkhirdaftar.removeAttribute('disabled');

            }
            if (tanggalAkhirdaftar.value === '') {
                tanggalAwalProgram.setAttribute('disabled', 'disabled');
            } else {
                tanggalAwalProgram.removeAttribute('disabled');
            }
            if (tanggalAwalProgram.value === '') {
                tanggalAkhirProgram.setAttribute('disabled', 'disabled');
            } else {
                tanggalAkhirProgram.removeAttribute('disabled');
            }
        }

        tanggalAwaldaftar.addEventListener('input', () => {
            tanggalAkhirdaftar.min = tanggalAwaldaftar.value;
            tanggalAwalProgram.min = tanggalAwaldaftar.value;


        });

        tanggalAkhirdaftar.addEventListener('input', () => {
            tanggalAwalProgram.min = tanggalAkhirdaftar.value;


        });

        tanggalAwalProgram.addEventListener('input', () => {
            tanggalAkhirProgram.min = tanggalAwalProgram.value;
        });

        tanggalAkhirProgram.addEventListener('input', () => {
            tanggalAwalProgram.min = tanggalAkhirdaftar.value;
        });

        function sks() {
    // Ambil elemen input dengan nama "nama_penanggung_jawab"
    var inputElement = document.querySelector('input[name="nama_penanggung_jawab"]');

    // Ambil nilai dari input
    var nilai = inputElement.value;

    // Periksa apakah nilai lebih dari 20
    if (parseInt(nilai) > 20) {
      // Jika lebih dari 20, maka nonaktifkan input
      inputElement.disabled = true;
    } else {
      // Jika tidak lebih dari 20, aktifkan input (jika sebelumnya dinonaktifkan)
      inputElement.disabled = false;
    }
  }

  // Tambahkan event listener untuk memanggil fungsi saat nilai input berubah
  var inputElement = document.querySelector('input[name="nama_penanggung_jawab"]');
  inputElement.addEventListener('input', checkNilaiInput);

  // Inisialisasi cek nilai input saat halaman dimuat
  checkNilaiInput();




    </script>
@endsection
