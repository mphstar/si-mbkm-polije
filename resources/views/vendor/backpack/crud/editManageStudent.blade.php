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

            <form id="formNilai" action="{{ 'editMatkul' }}" method="post">
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
                    <div class="card-header"><strong>Mata Kuliah</strong>
                        <p class="text-danger">Butuh minimal
                            <strong>{{ $data->mbkm_id == null ? 20 : $data->mbkm->jumlah_sks }}</strong> SKS
                        </p>
                    </div>

                    <div class="card-body">
                        <div class="form-group">
                            <label for="nf-email">Mata Kuliah</label>


                            {{-- @if ($course->isNotEmpty())
                                <label for="">jumlah sks minimal yang di konversikan = </label>
                            @endif --}}
                            <br>

                            <div class="form-check form-check-inline mr-4">
                                <input onclick="checkAll(this)" class="form-check-input" id="checkall" type="checkbox">

                                <label class="form-check-label" for="checkall">Pilih Semua
                                </label>
                            </div>
                            <div class=" col-form-label">
                                @foreach ($course as $itemB)
                                    <?php
                                    $found = false;
                                    foreach ($data->involved as $itemA) {
                                        if ($itemA->kode_matkul === $itemB->kode_mata_kuliah) {
                                            $found = true;
                                            break;
                                        }
                                    }
                                    ?>
                                    @if ($found)
                                        <div class="form-check form-check-inline mr-4">
                                            <input sks="{{ $itemB->sks }}"
                                                onchange="handleCheck(this, '{{ $itemB->sks }}')" checked name="ids[]"
                                                class="form-check-input" id="{{ $itemB->kode_mata_kuliah }}"
                                                type="checkbox"
                                                value="{{ json_encode([
                                                    'kode_matkul' => $itemB->kode_mata_kuliah,
                                                    'nama_matkul' => $itemB->mata_kuliah ? $itemB->mata_kuliah : $itemB->mata_kuliah_praktikum,
                                                    'sks' => $itemB->sks,
                                                ]) }}">
                                            <label class="form-check-label"
                                                for="{{ $itemB->kode_mata_kuliah }}">{{ $itemB->mata_kuliah ? $itemB->mata_kuliah : $itemB->mata_kuliah_praktikum }}
                                                ({{ $itemB->sks }} SKS)
                                            </label>
                                        </div>
                                    @else
                                        <div class="form-check form-check-inline mr-4">
                                            <input sks="{{ $itemB->sks }}"
                                                onchange="handleCheck(this, '{{ $itemB->sks }}')" name="ids[]"
                                                class="form-check-input" id="{{ $itemB->kode_mata_kuliah }}"
                                                type="checkbox"
                                                value="{{ json_encode([
                                                    'kode_matkul' => $itemB->kode_mata_kuliah,
                                                    'nama_matkul' => $itemB->mata_kuliah ? $itemB->mata_kuliah : $itemB->mata_kuliah_praktikum,
                                                    'sks' => $itemB->sks,
                                                ]) }}">
                                            <label class="form-check-label"
                                                for="{{ $itemB->kode_mata_kuliah }}">{{ $itemB->mata_kuliah ? $itemB->mata_kuliah : $itemB->mata_kuliah_praktikum }}
                                                ( {{ $itemB->sks }} SKS )</label>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        <p><strong>Sks terpilih: <span id="terpilih">6</span></strong></p>

                    </div>
                    <div class="card-footer">
                        <button onclick="submitNilai('{{ $data->mbkm_id == null ? 20 : $data->mbkm->jumlah_sks }}')"
                            class="btn btn-sm btn-primary" type="button"><i class="fa fa-dot-circle-o"></i>
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

        const checkAll = (e) => {
            if (e.checked) {

                checkb.forEach(element => {
                    element.click()
                });
            } else {
                selected = 0
                terpilih.innerHTML = selected

                checkb.forEach(element => {
                    element.checked = false
                });
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
