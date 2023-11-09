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
            <form action="{{ 'editMatkul' }}" method="post">
                {!! csrf_field() !!}
                <div class="card">
                    <div class="card-header"><strong>Mata Kuliah</strong></div>
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
                                            <input checked name="ids[]" class="form-check-input" id="{{ $itemB->id }}"
                                                type="checkbox" value="{{ $itemB->id }}">
                                            <label class="form-check-label"
                                                for="{{ $itemB->id }}">{{ $itemB->name }}</label>
                                        </div>
                                    @else
                                        <div class="form-check form-check-inline mr-4">
                                            <input name="ids[]" class="form-check-input" id="{{ $itemB->id }}"
                                                type="checkbox" value="{{ $itemB->id }}">
                                            <label class="form-check-label"
                                                for="{{ $itemB->id }}">{{ $itemB->name }}</label>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                    </div>
                    <div class="card-footer">
                        <button class="btn btn-sm btn-primary" type="submit"><i class="fa fa-dot-circle-o"></i>
                            Submit</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
    <script></script>
@endsection
