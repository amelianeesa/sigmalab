@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Dashboard</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Ringkasan operasional laboratorium</li>
    </ol>

    <div class="card mb-4 shadow-sm">
        <div class="card-body p-5 text-center">
            <h2 class="fw-bold text-primary mb-3">Selamat Datang di SIGMA-LAB</h2>
            <p class="text-muted fs-5">Sistem Integrasi Manajemen Laboratorium — PT Sucofindo Cilacap</p>
            <!-- <hr class="my-4 w-25 mx-auto">
            <a href="{{ route('alat.index') }}" class="btn btn-primary btn-lg mt-2">
                <i class="fas fa-tools me-2"></i> Kelola Data Alat & Aset
            </a> -->
        </div>
    </div>
</div>
@endsection