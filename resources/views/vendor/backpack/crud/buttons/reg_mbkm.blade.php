@if ($crud->hasAccess('create'))
<a href="{{ url($crud->route.'/'.$entry->getKey().'/reg-mbkm') }} " class="btn btn-xs btn-default"><i class="fa fa-ban"></i> Daftar</a>
@endif