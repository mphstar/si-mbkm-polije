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
            <form action="{{ 'editDosen' }}" method="post">
                {!! csrf_field() !!}
                <div class="card">
                    <div class="card-header"><strong>Dosen Pembimbing</strong></div>
                    <div class="card-body">

                        <div class="form-group">
                            <label for="dosen">Dosen</label>
                            <select class="form-control" id="dosen" name="dosen">
                                @foreach ($dosen as $item)
                                    <option {{ $item->id == $data->pembimbing ? 'selected' : '' }}
                                        value="{{ $item->id }}">
                                        {{ $item->lecturer_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-sm btn-primary" type="submit"><i class="fa fa-dot-circle-o"></i>
                            Simpan</button>
                        {{-- <button class="btn btn-sm btn-danger" type="reset"><i class="fa fa-ban"></i> Reset</button> --}}
                    </div>
                </div>
            </form>
            <form id="formNilai" action="{{ 'editMatkul' }}" method="post">
                {!! csrf_field() !!}
                <div class="card">
                    <div class="card-header"><strong>Mata Kuliah</strong>
                        <p class="text-danger">Butuh minimal <strong>{{ $data->mbkm->jumlah_sks }}</strong> SKS</p>
                    </div>

                    <div class="card-body">
                        <div class="form-group">
                            <label for="nf-email">Mata Kuliah</label>
                            <br>
                            {{-- @if ($course->isNotEmpty())
                                <label for="">jumlah sks minimal yang di konversikan = </label>
                            @endif --}}
                            <div class="col-md-9 col-form-label">
                                @foreach ($course as $itemB)
                                    <?php
                                    $found = false;
                                    foreach ($data->involved as $itemA) {
                                        if ($itemA->course_id === $itemB->id) {
                                            $found = true;
                                            break;
                                        }
                                    }
                                    ?>
                                    @if ($found)
                                        <div class="form-check form-check-inline mr-4">
                                            <input sks="{{ $itemB->sks }}"
                                                onchange="handleCheck(this, '{{ $itemB->sks }}')" checked name="ids[]"
                                                class="form-check-input" id="{{ $itemB->id }}" type="checkbox"
                                                value="{{ $itemB->id }}">
                                            <label class="form-check-label" for="{{ $itemB->id }}">{{ $itemB->name }}
                                                ({{ $itemB->sks }} SKS)
                                            </label>
                                        </div>
                                    @else
                                        <div class="form-check form-check-inline mr-4">
                                            <input sks="{{ $itemB->sks }}"
                                                onchange="handleCheck(this, '{{ $itemB->sks }}')" name="ids[]"
                                                class="form-check-input" id="{{ $itemB->id }}" type="checkbox"
                                                value="{{ $itemB->id }}">
                                            <label class="form-check-label" for="{{ $itemB->id }}">{{ $itemB->name }}
                                                ( {{ $itemB->sks }} SKS )</label>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        <p><strong>Sks terpilih: <span id="terpilih">6</span></strong></p>
                    </div>
                    <div class="card-footer">
                        <button onclick="submitNilai('{{ $data->mbkm->jumlah_sks }}')" class="btn btn-sm btn-primary"
                            type="button"><i class="fa fa-dot-circle-o"></i>
                            Submit</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
@endsection

@section('after_scripts')
    <script>
        let selected = 0;
        const checkb = document.getElementsByName('ids[]')
        const terpilih = document.getElementById('terpilih')
        terpilih.innerHTML = selected

        for (var checkbox of checkb) {
            if (checkbox.checked) {
                selected += parseInt(checkbox.getAttribute('sks'))
                terpilih.innerHTML = selected
            }
        }

        // if (document.getElementById('checkbox').checked) {
        //     alert("checked");
        // } else {
        //     alert("You didn't check it! Let me check it for you.")
        // }
        const formNilai = document.getElementById('formNilai')

        const submitNilai = (jum_sks) => {
            if (selected >= jum_sks) {
                formNilai.submit()
                // console.log("ok");
            } else {
                new Noty({
                    type: "error",
                    text: 'SKS tidak terpenuhi',
                }).show();
            }

        }

        const handleCheck = (e, sks) => {
            // console.log(sks);
            if (e.checked) {
                selected += parseInt(sks)
            } else {
                selected -= parseInt(sks)
            }
            terpilih.innerHTML = selected
        }
    </script>
@endsection
