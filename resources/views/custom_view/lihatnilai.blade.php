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
                    <div id="isitable">
                        <table id="tablee" class="table table-responsive-sm">
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
                                        <td>{{ $item->nama_matkul }}</td>
                                        <td>{{ $item->sks }}</td>
                                        <td>{{ $item->grade == '' ? '-' : $item->grade }}</td>
                                        <td>
                                            @if ($item->grade == '')
                                                -
                                            @else
                                                @if ($item->grade >= 0 && $item->grade < 46)
                                                    E
                                                @elseif($item->grade >= 46 && $item->grade < 56)
                                                    D
                                                @elseif($item->grade >= 56 && $item->grade < 66)
                                                    C
                                                @elseif($item->grade >= 66 && $item->grade < 71)
                                                    BC
                                                @elseif($item->grade >= 71 && $item->grade < 76)
                                                    B
                                                @elseif($item->grade >= 76 && $item->grade < 81)
                                                    AB
                                                @elseif($item->grade >= 81 && $item->grade <= 100)
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
                    </div>
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
                <button {{ $data->status == 'done' ? 'disabled' : '' }} onclick="tolak({{ $data }})"
                    id="reject" class="btn btn-danger" type="button">Tolak</button>
                <button {{ $data->status == 'done' ? 'disabled' : '' }} onclick="terima({{ $data }})"
                    class="btn btn-success" id="acc" type="button">Terima</button>
                <a href="#"><button id="btnPrint" class="btn btn-secondary" type="button">Print</button></a>
                {{-- <a href="/cetak_nilai/{{ $data->id }}" target="_blank"><button id="btnPrint" class="btn btn-secondary" type="button">Print</button></a> --}}

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

    // btnPrint.addEventListener('click', function () {
    //     const printWindow = window.open('', '_blank');
    //     printWindow.document.write('<html><head><title>Print</title>');
    //     printWindow.document.write('</head><body>');
    //     printWindow.document.write(document.getElementById('isitable').innerHTML);
    //     printWindow.document.write('</body></html>');
    //     printWindow.document.close();
    //     printWindow.print();
    // });
</script>
