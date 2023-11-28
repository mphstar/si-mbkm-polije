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
        </h2>
        {{ trans('backpack::crud.back_to_all') }} <span>{{ $crud->entity_name_plural }}</span></a></small>
        @endif
    </section>
@endsection

@section('after_styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <h4> Form Daftar MBKM EKSTERNAL</h4>
            <p>Download file pengajuan <a href="#">disini</a></p>
            <div class="col-md-2">
                <a href="{{ backpack_url('m-b-k-m-eksternal') }}"
                    class="btn btn-sm btn-block btn-outline-danger mb-3 mt-2">Kembali</a>
            </div>

            <div class="card">
                <div class="card-body">

                    <form action="{{ 'tambahData' }}"method="post" enctype="multipart/form-data">
                        @csrf

                        <input type="hidden" name="student_id" value="{{ $siswa->id }}">
                        <input type="hidden" name="semester" value="{{ $siswa->semester }}">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>jenis mbkm</label>

                                <select class="form-control" name="id_jenis">
                                    @foreach ($jenis_mbkm as $key)
                                        <option value="{{ $key->id }}">{{ $key->jenismbkm }}</option>
                                    @endforeach

                                </select>
                            </div>
                        </div>

                        <div class="form-group col-md-5">
                            <label>Upload File Pengajuan</label>


                            <input required class="form-control" type="file" name="file_surat"
                                placeholder="file harus berupa pdf" accept=".pdf">
                            <div class="text-danger">*Jenis file yang diizinkan: .pdf.</div>
                        </div>
                        <div id="dynamic-forms" class="px-3">
                            <div class="row form-group">
                                <div class="col-md-4">
                                    <label>Nama Mitra dan alamat mitra</label>
                                    <select class="form-control js-example-basic-single" name="partner_id[]">
                                        <!-- ... -->
                                        @foreach ($partner as $key)
                                            <option value="{{ $key->id }}">{{ $key->partner_name }} . Alamat
                                                :{{ $key->address }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label>Nama Program</label>
                                    <input type="text" class="form-control" name="nama_program[]">
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-primary mb-2 mt-4 add-form">+</button>
                                </div>
                            </div>
                        </div>




                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('after_scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $(".add-form").click(function() {
                var newForm = `
                    <div class="row form-group">
                        <div class="col-md-4">
                            <label>Nama Mitra</label>
                            <select class="form-control js-example-basic-single" name="partner_id[]">
                                 
                                @foreach ($partner as $key)
                                    <option value="{{ $key->id }}">{{ $key->partner_name }} . Alamat
                                                :{{ $key->address }}</option>
                                    @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>Nama Program</label>
                            <input type="text" class="form-control" name="nama_program[]">
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger mb-2 mt-4 remove-form">-</button>
                        </div>
                    </div>`;
                $("#dynamic-forms").append(newForm);
                 $('.js-example-basic-single').select2();
            });

            $(document).on('click', '.remove-form', function() {
                $(this).closest('.form-group').remove();
            });
        });

        // In your Javascript (external .js resource or <script> tag)
        $(document).ready(function() {
            $('.js-example-basic-single').select2();
        });
    </script>
@endsection
