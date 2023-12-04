@extends(backpack_view('layouts.plain'))
<style>
    .custom-image-size {
        width: 300px;
        height: 200px;
        /* Atau atur ukuran responsif */
        max-width: 100%;
        /* Maksimum lebar gambar */
        height: auto;
        /* Tinggi gambar menyesuaikan lebar sesuai aspek ratio */
    }
</style>
@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-12">
            <div class="responsive-feature-img text-center">
                <img src="{{ asset('assets/img/sissmbkm.png') }}" alt="responsive devices" class="custom-image-size">
            </div>

            <div class="card">
                <div class="card-body">
                    <h3 class="text-center mb-4">{{ trans('backpack::base.register') }}</h3>
                    <form class="col-md-12 p-t-10" role="form" method="POST" action="/register/student/proses">
                        {!! csrf_field() !!}

                        <div class="form-group">
                            <label class="control-label" for="name">Nama</label>

                            <div>
                                <input type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                    name="name" id="name" value="{{ old('name') }}">

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="phone">Phone</label>

                            <div>
                                <input type="number" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}"
                                    name="phone" id="phone" value="{{ old('phone') }}">

                                @if ($errors->has('phone'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="nim">NIM</label>

                            <div>
                                <input type="text" class="form-control{{ $errors->has('nim') ? ' is-invalid' : '' }}"
                                    name="nim" id="nim" value="{{ old('nim') }}">

                                @if ($errors->has('nim'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('nim') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="semester">Semester</label>

                            <div>
                                <input type="text" class="form-control{{ $errors->has('semester') ? ' is-invalid' : '' }}"
                                    name="semester" id="semester" value="{{ old('semester') }}">

                                @if ($errors->has('semester'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('semester') }}</strong>
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
                                        <option
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

                                </select>

                                @if ($errors->has('program_studi'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('program_studi') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="address">Address</label>

                            <div>
                                <textarea class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }}" name="address" id="address"
                                    value="{{ old('address') }}">{{ old('address') }}</textarea>

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
                                    name="email" id="email" value="{{ old('email') }}">

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label" for="password">Password</label>

                            <div>
                                <input type="password"
                                    class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password"
                                    id="password">

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label" for="password_confirmation">Confirm Password</label>

                            <div>
                                <input type="password"
                                    class="form-control{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}"
                                    name="password_confirmation" id="password_confirmation">

                                @if ($errors->has('password_confirmation'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div>
                                <button type="submit" class="btn btn-block btn-primary">
                                    Register
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @if (backpack_users_have_email() && config('backpack.base.setup_password_recovery_routes', true))
                <div class="text-center"><a
                        href="{{ route('backpack.auth.password.reset') }}">{{ trans('backpack::base.forgot_your_password') }}</a>
                </div>
            @endif
            <div class="text-center"><a href="{{ route('backpack.auth.login') }}">{{ trans('backpack::base.login') }}</a>
            </div>
        </div>
    </div>
@endsection

@section('after_scripts')
    <script>
        const program_studi = document.getElementById('program_studi')
        const handleJurusan = async (e, prodi) => {
            const filtered = prodi.filter((item) => item.parent_uuid == e.value)
            let kontenHtml = ''

            filtered.forEach((element) => {
                kontenHtml += `<option value="${element.uuid}">${element.unit}</option>`
            });

            program_studi.innerHTML = kontenHtml
        }
    </script>
@endsection
