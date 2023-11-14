<div class="modal fade" id="lihatNilai" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Nilai Mahasiswa</h4>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="card-body">
                    <table class="table table-responsive-sm">
                        <thead>
                            <tr>
                                <th>Mata Kuliah</th>
                                <th>SKS</th>
                                <th>Nilai</th>
                                <th>Hasil</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data->involved as $item)
                                <tr>
                                    <td>{{ $item->course->name }}</td>
                                    <td>{{ $item->course->sks }}</td>
                                    <td>{{ $item->grade == '' ? '-' : $item->grade }}</td>
                                    <td>
                                        @if ($item->grade == '')
                                            -
                                        @else
                                            @if ($item->grade >= 0 && $item->grade < 60)
                                                C
                                            @elseif($item->grade >= 60 && $item->grade < 75)
                                                B
                                            @elseif($item->grade >= 75 && $item->grade < 87)
                                                B+
                                            @elseif($item->grade >= 87 && $item->grade <= 100)
                                                A
                                            @else
                                                Tidak Diketahui
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name"><strong>Keterangan</strong></label>
                                <textarea class="form-control" id="keterangan" type="text" placeholder="Isi keterangan">{{ $data->keterangan_kaprodi }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button onclick="tolak({{ $data }})" id="reject" class="btn btn-danger"
                    type="button">Tolak</button>
                <button onclick="terima({{ $data }})" class="btn btn-success" id="acc"
                    type="button">Terima</button>
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

    const keterangan = document.getElementById('keterangan')
    const reject = document.getElementById('reject')
    const acc = document.getElementById('acc')

    const tolak = (data) => {
        window.location.href = `/admin/acc-nilai/${data.id}/tolak?keterangan=${btoa(keterangan.value)}`
    }
    const terima = (data) => {
        window.location.href = `/admin/acc-nilai/${data.id}/terima?keterangan=${btoa(keterangan.value)}`
    }
</script>
