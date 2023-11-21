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
     <h2> Data pada Tabel merupakan data mbkm eksternal yang anda ajukan  </h2><br>
     <h5> untuk mendaftar MBKM anda cukup tekan tombol DAFTAR PROGRAM</h5>
          {{-- <button  type="button" class="btn btn-primary mr-2 mb-5" data-toggle="modal"
          data-target="#daftarexternal">Daftar PROGRAM </button> --}}
          <div class="col-md-2 mb-3 mt-3">
          <a href="{{ backpack_url('daftarmbkmexternal') }}" class="btn btn-sm btn-block btn-outline-primary">DAFTAR PROGRAM</a>
          </div>
            <div class="card">
         
                <div class="card-body">



                  <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Jenis Program</th>
                                <th class="text-center">Kategory</th>
                                <th class="text-center">Semeter</th>
                                <th class="text-center">Detail Program</th>
                                <th class="text-center">Download File TTD kaprodi</th>
                              {{-- <th class="text-center">Upload Bukti Terima</th> --}}

                             
               

                            </tr>
                        </thead>
                        <tbody>
                          @php
                          $index = 1;
                      @endphp
                          @foreach ($extenal_sementara as $item)
                              
                        
                          <tr>
                            <td class="text-center">{{ $index }}</th>
                              <td class="text-center">{{ $item->jenismbkm->jenismbkm }}</th>
                                
                                <td class="text-center">{{ $item->jenismbkm->kategori_jenis }}</td>
                                <td class="text-center">{{ $item->semester }}</td>
                                <td class="text-center"><a href="{{ backpack_url('detailpengajuan/'.$item->id) }}" class="btn btn-sm btn-primary">Detail Program</a></td>
                              @if ($item->file_surat_ttd === null)
                              <td class="text-center">-</td> 
                              @else
                              <td class="text-center"><a href="/{{ $item->file_surat_ttd }}" class="btn btn-sm btn-primary"><i
                                class="nav-icon la la-download"></i></a></td>   
                              @endif
                            
                               
                                {{-- <td class="text-center">
                           
                                  <button  type="button" class="btn btn-primary mr-2 mb-5" data-toggle="modal"
                                  data-target="#uploadsk">Daftar PROGRAM </button></td> --}}
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
   
    <div class="modal fade" id="uploadsk" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Upload Pngajuan</h5>
              <form action="{{ 'tambahData' }}" method="post" enctype="multipart/form-data">
                @csrf
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="col-md-6 form-group">
                    <label class="required">File Penilaian</label>
                    <input required class="form-control" type="file" name="file_surat" accept=".pdf">
                    <div class="text-danger">*Jenis file yang diizinkan: .pdf.</div>
                </div>
                <input type="hidden" name="student_id" value="{{ $siswa }}">
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
        </form>
          </div>
        </div>
      </div>
    @endsection
    @section('after_scripts')
    <script>
        $(document).on('show.bs.modal', '.modal', function() {
            $(this).appendTo('body');
        });


   
    </script>
    @endsection
