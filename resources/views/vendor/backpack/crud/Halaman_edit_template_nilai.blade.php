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
                <form action="{{ route('updatefile', ['id' => $data->id]) }}"method="post" enctype="multipart/form-data">
                    @csrf

                    <div class="col-md-6 form-group">
                        <label class=""></label>
                        <input type="hidden" name="id" value="{{ $data->id }}">
                    </div>

                    <div class="col-md-6 form-group">
                        <label class="required">Nama Format</label>
                        <input required class="form-control" type="text" name="name_template" value="{{ $data->nama }}" readonly>
                    </div>

                    <div class="col-md-6 form-group">
						<label class="required">Upload Format File</label>
						<input required class="form-control" type="file" name="file" placeholder="file harus berupa pdf" accept=".pdf" value="" >
						<div class="text-danger">*Jenis file yang diizinkan: .pdf.</div>
					</div>

                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>

</script>
@endsection
