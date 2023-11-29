<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
</head>
<style>
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    @page {
        size: A4;
        margin-top: 20px;
        margin-bottom: 70px;
        margin-left: 60px;
        margin-right: 70px;
    }

    html,
    body {
        width: 210mm;
        /* height: 297mm; */
    }

    .cop {
        width: 210mm;
        height: 200px;
        display: flex;
        justify-content: flex-start;
        align-items: center;
        flex-direction: row;
        top: 0;
        position: fixed;
        border-bottom: 4px solid black;
        background-color: white;
    }

    .content {
        display: flex;
        flex-direction: column;
        /* background-color: red; */
        justify-content: center;
        margin-top: 200px;
        /* padding-top: 200px; */
    }

    .content p:nth-child(1) {
        margin-top: 12px;
    }

    .logo-cop {
        height: 120px;
    }

    .text-cop {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding-right: 50px;
        max-width: 80%;
    }

    .text-cop p {
        text-align: center;
    }

    .text-cop p:nth-child(1) {
        font-size: 24px;
    }

    .text-cop p:nth-child(2) {
        font-size: 18px;
    }

    .text-center {
        text-align: center;
    }

    .detail_data {
        height: auto;
        margin-left: 82px;
        display: flex;
        flex-direction: row;
        align-items: baseline;
        justify-items: baseline;
    }

    .detail_data div:nth-child(1) {
        width: 40px;
    }

    .detail_data div:nth-child(2) {
        width: 140px;
    }

    .detail_data div:nth-child(3) {
        padding-inline: 12px;
    }

    .detail_data div:nth-child(4) {
        flex: 1;
    }

    table {
        border-collapse: collapse;
    }

    table tr th {
        border: 1px solid black;
        padding-block: 6px;
    }

    table tr td {
        border: 1px solid black;
        padding-block: 6px;
        text-align: center;
    }

    table tr td:nth-child(1) {
        text-align: center;
    }

    table tr td:nth-child(2) {
        text-align: start;
        padding-left: 8px;
    }

    table tr th:nth-child(2) {
        text-align: start;
        padding-left: 8px;
    }

    .footer-ttd {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        padding: 10px;
        justify-items: center;
        grid-row-gap: 24px;
    }

    .footer-ttd-item {
        /* width: 300px; */
    }

    .footer-ttd .footer-ttd-item:nth-child(3){
        text-align: center;
        grid-column-start: 1;
        grid-column-end: 3;
    }

    /* .footer-ttd .footer-ttd-item {
        justify-content: center;
    } */


    /* ... the rest of the rules ... */
    @media print {
    }

</style>

<body class="">
    <div class="cop">
        <img class="logo-cop" src="/assets/img/polijeLogo.png" alt="logo_cop">
        <div class="text-cop">
            <p><b>KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI</b></p>
            <p>POLITEKNIK NEGERI JEMBER</p>
            <p>Jalan Mastrip Jember Kotak Pos 164 68101Telp.(0331)333532-34 Fax. (0331) 333531</p>
            <p>Email : politeknik@polije.ac.id ; Laman: www.polije.ac.id</p>
        </div>
    </div>
    <div class="content">
        <p class="text-center"><b>FORMULIR PENDAFTARAN</b></p>
        <p class="text-center"><b>PROGRAM MAGANG DAN STUDI INDEPENDEN BERSERTIFIKAT (MSIB)</b></p>
        <p class="text-center"><b>MERDEKA BELAJAR-KAMPUS MERDEKA</b></p>
        <p class="text-center"><b>POLITEKNIK NEGERI JEMBER</b></p>
        <br>

        <div class="detail_data">
            <div>1.</div>
            <div>Nama</div>
            <div>:</div>
            <div>Mphstar</div>
        </div>
        <div class="detail_data">
            <div>2.</div>
            <div>NIM</div>
            <div>:</div>
            <div>E41210618</div>
        </div>
        <div class="detail_data">
            <div>3.</div>
            <div>Program Studi</div>
            <div>:</div>
            <div>Teknik Informatika</div>
        </div>
        <div class="detail_data">
            <div>4.</div>
            <div>Jurusan</div>
            <div>:</div>
            <div>Teknologi Informasi</div>
        </div>
        <div class="detail_data">
            <div>5.</div>
            <div>Semester</div>
            <div>:</div>
            <div>Genap Tahun Akademik 2022/2023</div>
        </div>
        <div class="detail_data">
            <div>6.</div>
            <div>Judul MBKM</div>
            <div>:</div>
            <div>Bangkit</div>
        </div>
        <div class="detail_data">
            <div>7.</div>
            <div>Mitra MBKM</div>
            <div>:</div>
            <div>Google</div>
        </div>
        <div class="detail_data">
            <div>8.</div>
            <div>Lokasi MBKM</div>
            <div>:</div>
            <div>Jember</div>
        </div>
        <div class="detail_data">
            <div>9.</div>
            <div>Penanggung Jawab</div>
            <div>:</div>
            <div>Roni</div>
        </div>
        <div class="detail_data">
            <div>10.</div>
            <div>Dosen Pembimbing</div>
            <div>:</div>
            <div>Bu Arvita</div>
        </div>
        <div class="detail_data">
            <div>11.</div>
            <div>No SK Tugas</div>
            <div>:</div>
            <div></div>
        </div>
        <div class="detail_data">
            <div>12.</div>
            <div>Tanggal SK Tugas</div>
            <div>:</div>
            <div></div>
        </div>
        <div class="detail_data">
            <div>13.</div>
            <div>Durasi</div>
            <div>:</div>
            <div>1 (Satu) Semester</div>
        </div>
        <div class="detail_data">
            <div>14.</div>
            <div>Nilai Penyetaraan</div>
            <div>:</div>
            <div></div>
        </div>

        <br>
        <table>
            <tr>
                <th>No.</th>
                <th>Nama Mata Kuliah</th>
                <th>Kode Mata Kuliah</th>
                <th>SKS</th>
                <th>Disetarakan</th>
                <th>Nilai</th>
            </tr>
            <tr>
                <td>1</td>
                <td>Wokshop Sistem Tertanam</td>
                <td>TIF160001</td>
                <td>4</td>
                <td>Iya</td>
                <td>85</td>
            </tr>
            <tr>
                <td>2</td>
                <td>Wokshop Sistem Cerdas</td>
                <td>TIF160001</td>
                <td>4</td>
                <td>Iya</td>
                <td>85</td>
            </tr>
            <tr>
                <td>3</td>
                <td>Wokshop Pengolahan Citra dan Vision</td>
                <td>TIF160001</td>
                <td>4</td>
                <td>Iya</td>
                <td>85</td>
            </tr>
            <tr>
                <td>3</td>
                <td>Wokshop Pengolahan Citra dan Vision</td>
                <td>TIF160001</td>
                <td>4</td>
                <td>Iya</td>
                <td>85</td>
            </tr>
            <tr>
                <td>3</td>
                <td>Wokshop Pengolahan Citra dan Vision</td>
                <td>TIF160001</td>
                <td>4</td>
                <td>Iya</td>
                <td>85</td>
            </tr>
            <tr>
                <td>3</td>
                <td>Wokshop Pengolahan Citra dan Vision</td>
                <td>TIF160001</td>
                <td>4</td>
                <td>Iya</td>
                <td>85</td>
            </tr>
            <tr>
                <td>3</td>
                <td>Wokshop Pengolahan Citra dan Vision</td>
                <td>TIF160001</td>
                <td>4</td>
                <td>Iya</td>
                <td>85</td>
            </tr>
            <tr>
                <th>

                </th>
                <th>Total</th>
                <th></th>
                <th>20</th>
                <th></th>
                <th></th>
            </tr>
        </table>
        <br><br>
        <div class="footer">
            <div class="footer-ttd">
                <div class="footer-ttd-item">
                    <div>Jember, 24 Juli 2023</div>
                    <div>Mengetahui</div>
                    <div>Koord. Program Studi</div>
                    <div>D4 Teknik Informatika</div>
                    <br><br><br>
                    <div>Bety Etikasari S.kom, M.Cs</div>
                    <div>NIP. 199002272018032001</div>
                </div>
                <div class="footer-ttd-item">
                    <div><br></div>
                    <div><br></div>
                    <div><br></div>
                    <div>Pembimbing</div>
                    <br><br><br>
                    <div>Bu Arvita</div>
                    <div>NIP. 199002272018032001</div>
                </div>
                <div class="footer-ttd-item">
                    <div>Menyetujui</div>
                    <div>Ketua Jurusan Teknologi Informasi</div>
                    <br><br><br>
                    <div>Hendra Yufit Riskiawan, S.Kom, M.Cs</div>
                    <div>NIP. 199002272018032001</div>
                </div>
            </div>
        </div>
    </div>
    <script>
        // window.print();
    </script>
</body>

</html>
