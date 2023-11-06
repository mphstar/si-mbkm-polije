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
                    <form action="/admin/m-b-k-m-eksternal/daftareksternal"method="post"enctype="multipart/form-data">
                      @csrf
           
                        <div class="form-group">
                      
                          <label for="pilihan">jenis mbkm</label>
                       
                          <select class="form-control" id="pilihan"name="jenis_mbkm">
                       
                              <option value="msib pertukaran pelajar">MSIB pertukaran pelajar</option>
                              <option value="msib magang">MSIB Magang</option>
                              <option value="msib studi independence">MSIB Studi Independence</option>
                              <option value="msib kampus mengajar">MSIB Kampus Mengajar</option>
                              <option value="msib wmk">MSIB WMK</option>
                  

                          </select>
                        
                        </div>
                        <div class="form-group">
                          <label>Nama mitra</label>
                          <input type="text" class="form-control" name="nama_mitra">

                        </div>
                        <input type="hidden" class="form-control" name="student_id"value="{{ $id }}">
                        {{-- <div class="form-group">
                          <label>Nama mitra</label> --}}

                        {{-- </div> --}}
                        <div class="form-group">
                            <label for="exampleFormControlFile1">Masukkan file Pegajuan</label>
                            <input type="file" class="form-control-file" id="exampleFormControlFile1"name="requirements_files">
                          </div>
                       
                 
                     
                   
                     
                   
                       
                     
                     
                        <button type="submit" class="btn btn-primary">Submit</button>
                      </form>
                </div>
                </div>
            </div>
        </div>



      
      
      
      
      
      
    @endsection
