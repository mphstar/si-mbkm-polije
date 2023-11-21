<div class="modal fade" id="progressNilai" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Progress Mahasiswa</h4>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-header"><i class="fa fa-align-justify"></i> Progress Laporan <small>MBKM</small>
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            @foreach ($data as $index => $item)
                                <a href="/{{ $item->file }}">
                                    <li
                                        class="list-group-item d-flex list-group-item-action justify-content-between align-items-center">
                                        {{ $item->file_info }}<span
                                            class="badge {{ $item->status == 'pending' ? 'badge-primary' : ($item->status == 'accepted' ? 'badge-success' : 'badge-danger') }} badge-pill">{{ $item->status }}</span>
                                    </li>
                                </a>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" type="button" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content-->
    </div>
    <!-- /.modal-dialog-->
</div>

<script>
    $(document).on('show.bs.modal', '.modal', function() {
        $(this).appendTo('body');
    });
</script>
