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
                  <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Nama Program</th>
                                <th class="text-center">Nama Mitra</th>
                                <th class="text-center">Keterangan</th>
                                <th class="text-center">Jenis MBKM</th>

                             
                                <th class="text-center">Action</th>

                            </tr>
                        </thead>
                        <tbody>
                          @php
                          $index = 1;
                      @endphp
                          @foreach ($mbkm as $item)
                              
                        
                          <tr>
                            <td class="text-center">{{ $index }}</th>
                              <td class="text-center">{{ $item->program_name }}</th>
                                
                                <td class="text-center">{{ $item->partner->partner_name }}</td>
                                <td class="text-center">{{ $item->info }}</td>
                                <td class="text-center">{{ $item->jenismbkm->jenismbkm}}</td>
                          </tr>
                          @php
                          $index++;
                      @endphp
                          @endforeach
                         </tbody>
                    </table>
                </div>
                </div>
                </div>
            </div>
        </div>
    @endsection
