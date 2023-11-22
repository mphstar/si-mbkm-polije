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

            <form method="post" action="{{ url($crud->route.'/'.$data->id) }}">
                @csrf
                {!! method_field('PUT') !!}
                <div class="card">
                    <div class="card-body">

                        <div class="form-group">
                            <label class="control-label" for="lecturer_name">Nama Dosen</label>

                            <div>
                                <input type="text"
                                    class="form-control{{ $errors->has('lecturer_name') ? ' is-invalid' : '' }}"
                                    name="lecturer_name" id="lecturer_name" value="{{ $data->lecturer_name }}">

                                @if ($errors->has('lecturer_name'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('lecturer_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="phone">Phone</label>

                            <div>
                                <input type="number" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}"
                                    name="phone" id="phone" value="{{ $data->phone }}">

                                @if ($errors->has('phone'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="nip">NIP</label>

                            <div>
                                <input type="text" class="form-control{{ $errors->has('nip') ? ' is-invalid' : '' }}"
                                    name="nip" id="nip" value="{{ $data->nip }}">

                                @if ($errors->has('nip'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('nip') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="status">Status</label>

                            <div>
                                <select name="status"
                                    class="form-control{{ $errors->has('status') ? ' is-invalid' : '' }}" id="status">
                                    <option {{ $data->status == 'dosen pembimbing' ? 'selected' : '' }} value="dosen pembimbing">Dosen Pembimbing</option>
                                    <option {{ $data->status == 'kaprodi' ? 'selected' : '' }} value="kaprodi">Kaprodi</option>
                                </select>

                                @if ($errors->has('status'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('status') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="jurusan">Jurusan</label>

                            <div>
                                <select onchange="handleJurusan(this, {{ json_encode($prodi) }})" name="jurusan"
                                    class="form-control{{ $errors->has('jurusan') ? ' is-invalid' : '' }}" id="jurusan">
                                    <option value="">-</option>
                                    @foreach ($jurusan as $item)
                                        <option {{ $data->jurusan == $item->uuid ? 'selected' : '' }}
                                            {{ $errors->has('jurusan') ? ($errors->first('jurusan') == $item->uuid ? 'selected' : '') : '' }}
                                            value="{{ $item->uuid }}">{{ $item->unit }}</option>
                                    @endforeach
                                </select>

                                @if ($errors->has('jurusan'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('jurusan') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="program_studi">Program Studi</label>
                            <div>
                                <select name="program_studi"
                                    class="form-control{{ $errors->has('program_studi') ? ' is-invalid' : '' }}"
                                    id="program_studi">
                                    {{-- <option value="{{ $data->program_studi }}">{{$data->program_studi}}</option> --}}
                                </select>

                                @if ($errors->has('program_studi'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('program_studi') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="address">Alamat</label>

                            <div>
                                <textarea class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }}" name="address" id="address"
                                    value="{{ old('address') }}">{{ $data->address }}</textarea>

                                @if ($errors->has('address'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('address') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label"
                                for="email">{{ config('backpack.base.authentication_column_name') }}</label>

                            <div>
                                <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                    name="email" id="email" value="{{ $data->user->email }}">

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                    </div>
                </div>
                @include('crud::inc.form_save_buttons')
            </form>
        </div>
    </div>
@endsection

@section('after_scripts')
    <script>
        const program_studi = document.getElementById('program_studi')
        const handleJurusan = async (e, prodi) => {
            const filtered = prodi.filter((item) => item.parent_uuid == e.value)
            let kontenHtml = ''
            kontenHtml += `<option value="">-</option>`

            filtered.forEach((element) => {
                kontenHtml += `<option value="${element.uuid}">${element.unit}</option>`
            });

            program_studi.innerHTML = kontenHtml
        }
    </script>
@endsection
