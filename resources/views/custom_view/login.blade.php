@extends(backpack_view('layouts.plain'))

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-4">
            <h3 class="text-center mb-4">Login</h3>
            <div class="card">
                <div class="card-body">
                    <form class="col-md-12 p-t-10" role="form" method="GET" action="{{ route('google.login') }}">
                        {!! csrf_field() !!}

                        

                        <div class="form-group">
                            <div>
                                <button type="submit" class="btn btn-block btn-primary">
                                    Login with SSO
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            {{-- @if (backpack_users_have_email() && config('backpack.base.setup_password_recovery_routes', true))
                <div class="text-center"><a
                        href="{{ route('backpack.auth.password.reset') }}">{{ trans('backpack::base.forgot_your_password') }}</a>
                </div>
            @endif
            <div class="text-center"><a
                    href="{{ route('backpack.auth.login') }}">{{ trans('backpack::base.login') }}</a>
            </div> --}}
        </div>
    </div>
    <script>
        const footer = document.querySelector(".app-footer")
        console.log(footer);
    </script>
@endsection
