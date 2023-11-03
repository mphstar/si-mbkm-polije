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
                                    <label for="{{ $item->course->id }}">{{ $item->course->name }}</label>
                                    <input oninput="conversiNilai(this)" class="form-control" id="{{ $item->course->id }}"
                                        type="number" name="{{ $item->course->id }}" value="{{ $item->grade }}"
                                        placeholder="Masukkan nilai">
                                    <p class="help-block">Hasil Konversi: <span id="result_konversi">
                                            @if ($item->grade >= 0 && $item->grade < 60)
                                                C
                                            @elseif($item->grade >= 60 && $item->grade < 75)
                                                B
                                            @elseif($item->grade >= 75 && $item->grade < 87)
                                                B+
                                            @elseif($item->grade >= 87 && $item->grade <= 100)
                                                A
                                            @else
                                                Tidak Diketahui
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
        const res = document.getElementById('result_konversi')

        const conversiNilai = (e) => {
            var nilai = ''
            if (e.value == '') {
                nilai = '-'
            } else {
                if (e.value >= 0 && e.value < 60) {
                    nilai = 'C'
                } else if (e.value >= 60 && e.value < 75) {
                    nilai = 'B'
                } else if (e.value >= 75 && e.value < 87) {
                    nilai = 'B+'
                } else if (e.value >= 87 && e.value <= 100) {
                    nilai = 'A'
                } else {
                    nilai = 'Tidak Diketahui'
                }
            }

            res.innerHTML = nilai
        }
    </script>
@endsection
