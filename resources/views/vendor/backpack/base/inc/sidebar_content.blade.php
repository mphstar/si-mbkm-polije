{{-- <!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>
<!-- <li class='nav-item'><a class='nav-link' href='{{ backpack_url('students') }}'><i class='nav-icon la la-question'></i> Students</a></li> -->
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('partner') }}'><i class='nav-icon la la-handshake'></i> Partners</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('acctive-account-mitra') }}'><i class='nav-icon la la-user-check'></i>validasi Partners</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('validasi-mbkm') }}'><i class='nav-icon la la-book-check'></i> Validasi mbkms</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('departmen') }}'><i class='nav-icon la la-question'></i> Departmens</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('register-mbkm') }}'><i class='nav-icon la la-question'></i> Validasi Peserta mbkms</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('lecturer') }}'><i class='nav-icon la la-question'></i> Lecturers</a></li>
<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon las la-level-down-alt"></i>Mahasiswa</a>
    <ul class="nav-dropdown-items">
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('mbkm') }}'><i class='nav-icon la la-book'></i> Program MBKM</a></li>
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('mbkm-report') }}'><i class='nav-icon la la-file-alt'></i> Laporan</a></li>
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('status-reg') }}'><i class='nav-icon la la-user-tag'></i> Program Saya</a></li>
    </ul>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('management-m-b-k-m') }}'><i class='nav-icon la la-question'></i> Management m b k ms</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('penilaian-mitra') }}'><i class='nav-icon la la-question'></i> Penilaian mitras</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('validasilaporan') }}'><i class='nav-icon la la-question'></i> Validasilaporans</a></li> --}}

<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
@php
    $level = backpack_auth()->user()->level;
@endphp

<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i>
        {{ trans('backpack::base.dashboard') }}</a></li>
@if ($level == 'admin')
    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('partner') }}'><i class='nav-icon la la-handshake'></i>
            Partners</a></li>
    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('lecturer') }}'><i class='nav-icon la la-question'></i>
            Lecturers</a></li>
@endif
@if ($level == 'mitra')
    <li class="nav-title">Mitra</li>
    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('management-m-b-k-m') }}'><i
                class='nav-icon la la-edit'></i> Register MBKM</a></li>
    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('register-mbkm') }}'><i
                class='nav-icon la la-random'></i> Valdasi Pendaftar</a></li>
    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('validasilaporan') }}'><i
                class='nav-icon la la-folder'></i> Validasi Laporan</a></li>
    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('penilaian-mitra') }}'><i
                class='nav-icon la la-calculator'></i> Penilaian Mitra</a></li>
@endif
@if ($level == 'kaprodi')
    <li class="nav-title">Kaprodi</li>
    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('acctive-account-mitra') }}'><i
                class='nav-icon la la-user-check'></i>Validasi Partners</a></li>
    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('validasi-mbkm') }}'><i
                class='nav-icon la la-book'></i> Validasi MBKM</a></li>
    {{-- <li class='nav-item'><a class='nav-link' href='{{ backpack_url('departmen') }}'><i
                class='nav-icon la la-question'></i> Departmens</a></li> --}}
    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('lecturer') }}'><i
                class='nav-icon la la-question'></i> Lecturers</a></li>
    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('manage-student') }}'><i
                class='nav-icon la la-question'></i> Manage students</a></li>
    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('progress-mahasiswa') }}'><i
                class='nav-icon la la-question'></i> Progress Mahasiswa</a></li>
@endif
@if ($level == 'dospem')
    <li class="nav-title">Dosen</li>
    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('nilaimbkm') }}'><i
                class='nav-icon la la-question'></i>
            Nilai MBKM</a></li>
    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('progress-mahasiswa') }}'><i
                class='nav-icon la la-question'></i> Progress Mahasiswa</a></li>
@endif
@if ($level == 'student')
    <li class="nav-title">Students</li>

    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('mbkm-report') }}'><i
                class='nav-icon la la-file-alt'></i> Laporan</a></li>
    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('status-reg') }}'><i
                class='nav-icon la la-user-tag'></i> Program Saya</a></li>
    <li class="nav-item nav-dropdown"><a class="nav-link nav-dropdown-toggle" href="#"><i
                class="nav-icon la la-lg la-hand-pointer-o"></i> Program MBKM</a>
        <ul class="nav-dropdown-items">
            <li class='nav-item'><a class='nav-link' href='{{ backpack_url('mbkm') }}'><i
                        class='nav-icon la la-book'></i>
                    Program
                    MBKM</a></li>
            <li class='nav-item'><a class='nav-link' href='{{ backpack_url('m-b-k-m-eksternal') }}'><i
                        class='nav-icon la la-question'></i> MBKM Eksternal</a></li>
        </ul>
    </li>
@endif
