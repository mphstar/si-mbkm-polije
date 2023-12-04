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

            <form method="post" action="{{ 'prosesNilai' }}">
                {!! csrf_field() !!}
                <!-- load the view from the application if it exists, otherwise load the one in the package -->
                <div class="card">
                    <div class="card-header"><strong>List</strong> Mata Kuliah</div>
                    <div class="card-body">
                        <form action="" method="post">
                            @foreach ($data->involved as $item)
                                <div class="form-group">
                                    <label for="{{ $item->kode_matkul }}">{{ $item->nama_matkul }}</label>
                                    <input oninput="conversiNilai(this, 'result_konversi_{{ $item->kode_matkul }}')"
                                        class="form-control" id="{{ $item->kode_matkul }}" type="number"
                                        name="{{ $item->kode_matkul }}" value="{{ $item->grade }}"
                                        placeholder="Masukkan nilai">
                                    <p class="help-block">Hasil Konversi: <span
                                            id="result_konversi_{{ $item->kode_matkul }}">
                                            @if ($item->grade == '')
                                                -
                                            @else
                                                @if ($item->grade >= 0 && $item->grade < 46)
                                                    E
                                                @elseif($item->grade >= 46 && $item->grade < 56)
                                                    D
                                                @elseif($item->grade >= 56 && $item->grade < 66)
                                                    C
                                                @elseif($item->grade >= 66 && $item->grade < 71)
                                                    BC
                                                @elseif($item->grade >= 71 && $item->grade < 76)
                                                    B
                                                @elseif($item->grade >= 76 && $item->grade < 81)
                                                    AB
                                                @elseif($item->grade >= 81 && $item->grade <= 100)
                                                    A
                                                @else
                                                    Tidak Diketahui
                                                @endif
                                            @endif
                                        </span>
                                    </p>
                                </div>
                            @endforeach
                        </form>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-sm btn-primary" type="submit"><i class="fa fa-dot-circle-o"></i>
                            Submit</button>
                        {{-- <button class="btn btn-sm btn-danger" type="reset"><i class="fa fa-ban"></i> Reset</button> --}}
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
        const conversiNilai = (e, id_span) => {
            var nilai = ''
            if (e.value == '') {
                nilai = '-'
            } else {
                if (e.value >= 0 && e.value < 46) {
                    nilai = 'E'
                } else if (e.value >= 46 && e.value < 56) {
                    nilai = 'D'
                } else if (e.value >= 56 && e.value < 66) {
                    nilai = 'C'
                } else if (e.value >= 66 && e.value < 71) {
                    nilai = 'BC'
                } else if (e.value >= 71 && e.value < 76) {
                    nilai = 'B'
                } else if (e.value >= 76 && e.value < 81) {
                    nilai = 'AB'
                } else if (e.value >= 81 && e.value <= 100) {
                    nilai = 'A'
                } else {
                    nilai = 'Tidak Diketahui'
                }
            }

            document.getElementById(id_span).innerHTML = nilai
        }
    </script>
@endsection
