{{-- <!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i>{{ trans('backpack::base.dashboard') }}</a></li>
<!-- <li class='nav-item'><a class='nav-link' href='{{ backpack_url('students') }}'><i class='nav-icon la la-question'></i>Students</a></li> -->
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('partner') }}'><i class='nav-icon la la-handshake'></i>Partners</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('acctive-account-mitra') }}'><i class='nav-icon la la-user-check'></i>validasi Partners</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('validasi-mbkm') }}'><i class='nav-icon la la-book-check'></i>Validasi mbkms</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('departmen') }}'><i class='nav-icon la la-question'></i>Departmens</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('register-mbkm') }}'><i class='nav-icon la la-question'></i>Validasi Peserta mbkms</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('lecturer') }}'><i class='nav-icon la la-question'></i>Lecturers</a></li>
<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon las la-level-down-alt"></i>Mahasiswa</a>
    <ul class="nav-dropdown-items">
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('mbkm') }}'><i class='nav-icon la la-book'></i>Program MBKM</a></li>
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('mbkm-report') }}'><i class='nav-icon la la-file-alt'></i>Laporan</a></li>
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('status-reg') }}'><i class='nav-icon la la-user-tag'></i>Program Saya</a></li>
    </ul>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('management-m-b-k-m') }}'><i class='nav-icon la la-question'></i>Management m b k ms</a></li>

<li class='nav-item'><a class='nav-link' href='{{ backpack_url('penilaian-mitra') }}'><i class='nav-icon la la-question'></i>Penilaian mitras</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('validasilaporan') }}'><i class='nav-icon la la-question'></i>Validasilaporans</a></li> --}}

<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
@php
    $level = backpack_auth()->user()->level;
@endphp

<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i>
        {{ trans('backpack::base.dashboard') }}</a></li>
@if ($level == 'admin')
    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('partner') }}'><i
                class='nav-icon la la-handshake'></i>Mitra</a></li>
    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('lecturer') }}'><i
                class='nav-icon la la-user-edit'></i>Dosen</a></li>
    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('jenis-mbkm') }}'><i
                class='nav-icon la la-copy'></i>Jenis MBKM</a></li>
    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('template-nilai') }}'><i
                class='nav-icon la la-question'></i> Format File </a></li>
@endif
@if ($level == 'mitra')
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('datamitra') }}'><i class='nav-icon la la-user'></i>
        Profile</a></li>
    <li class="nav-title">Mitra</li>
    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('management-m-b-k-m') }}'><i
                class='nav-icon la la-edit'></i>Buat MBKM</a></li>
    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('register-mbkm') }}'><i
                class='nav-icon la la-random'></i>Konfirmasi Pendaftar</a></li>
                <li class='nav-item'><a class='nav-link' href='{{ backpack_url('validasilaporan') }}'><i
                    class='nav-icon la la-folder'></i>Konfirmasi Laporan</a></li>
                    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('penilaian-mitra') }}'><i
                        class='nav-icon la la-calculator'></i>Penilaian Mahasiswa</a></li>
                        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('riwayat-pendaftar') }}'><i
                                    class='nav-icon la la-random'></i>Riwayat Pendaftar</a></li>
@endif
@if ($level == 'kaprodi')
    <li class="nav-title">Kaprodi</li>
    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('acctive-account-mitra') }}'><i
                class='nav-icon la la-user-check'></i>Konfirmasi Mitra</a></li>
    <li class="nav-item nav-dropdown"><a class="nav-link nav-dropdown-toggle" href="#"><i
                class="nav-icon la la-lg la-book"></i>Konfirmasi MBKM</a>
        <ul class="nav-dropdown-items">
            <li class='nav-item'><a class='nav-link' href='{{ backpack_url('validasi-mbkm') }}'><i
                        class='nav-icon la la-university'></i>Dalam Kampus</a></li>
            {{-- <li class='nav-item'><a class='nav-link' href='{{ backpack_url('validasi-mbkm-eksternal') }}'><i
                        class='nav-icon la la-question'></i>Eksternal</a></li> --}}
            <li class='nav-item'><a class='nav-link' href='{{ backpack_url('mbkm-external') }}'><i
                        class='nav-icon la la-city'></i>Luar Kampus</a></li>

        </ul>
    </li>
    
    {{-- <li class='nav-item'><a class='nav-link' href='{{ backpack_url('departmen') }}'><i
        class='nav-icon la la-question'></i>Departmens</a></li> --}}
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('lecturer') }}'><i
            class='nav-icon la la-user-edit'></i>Dosen</a></li>
            <li class='nav-item'><a class='nav-link' href='{{ backpack_url('manage-student') }}'><i
                class='nav-icon la la-graduation-cap'></i>Kelola Mahasiswa</a></li>
              
                    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('progress-mahasiswa') }}'><i
                        class='nav-icon la la-copy'></i>Progres Mahasiswa</a></li>
                        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('acc-nilai') }}'><i
                            class='nav-icon la la-award'></i>Konfirmasi Nilai</a></li>
                            <li class="nav-item nav-dropdown"><a class="nav-link nav-dropdown-toggle" href="#"><i
                                        class="nav-icon la la-lg la-book"></i>Riwayat Mahasiswa MBKM</a>
                                <ul class="nav-dropdown-items">
                                    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('riwayatmhs-mbkminternal') }}'><i
                                                class='nav-icon la la-university'></i>Dalam Kampus</a></li>
                                    {{-- <li class='nav-item'><a class='nav-link' href='{{ backpack_url('validasi-mbkm-eksternal') }}'><i
                                                class='nav-icon la la-question'></i>Eksternal</a></li> --}}
                                    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('riwayatmhs_mbkmeksternal') }}'><i
                                                class='nav-icon la la-city'></i>Luar Kampus</a></li>
                        
                                </ul>
                            </li>
@endif
@if ($level == 'dospem')
    <li class="nav-title">Dosen</li>
    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('nilaimbkm') }}'><i
                class='nav-icon la la-award'></i>
            Nilai Mahasiswa</a></li>
    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('progress-mahasiswa') }}'><i
                class='nav-icon la la-copy'></i>Progres Mahasiswa</a></li>
@endif
@if ($level == 'student')
    <li class="nav-title">Students</li>

    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('mbkm-report') }}'><i
                class='nav-icon la la-file-alt'></i>Laporan Internal</a></li>

    <li class="nav-item nav-dropdown"><a class="nav-link nav-dropdown-toggle" href="#"><i
                class="nav-icon la la-lg la-user-tag"></i>Program Saya</a>
        <ul class="nav-dropdown-items">
            <li class='nav-item'><a class='nav-link' href='{{ backpack_url('status-reg') }}'><i
                        class='nav-icon la la-university'></i>Dalam Kampus</a></li>
            <li class='nav-item'><a class='nav-link' href='{{ backpack_url('mbkm-eksternal') }}'><i
                        class='nav-icon la la-city'></i>Luar Kampus</a></li>
        </ul>
    </li>
    <li class="nav-item nav-dropdown"><a class="nav-link nav-dropdown-toggle" href="#"><i
                class="nav-icon la la-lg la-file"></i>Program MBKM</a>
        <ul class="nav-dropdown-items">
            <li class='nav-item'><a class='nav-link' href='{{ backpack_url('mbkm') }}'><i
                        class='nav-icon la la-university'></i>Dalam Kampus</a></li>
            <li class='nav-item'><a class='nav-link' href='{{ backpack_url('m-b-k-m-eksternal') }}'><i
                        class='nav-icon la la-city'></i>Luar Kampus</a></li>
        </ul>
    </li>
@endif
