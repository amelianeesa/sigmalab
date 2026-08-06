@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm border-0 p-5 text-center bg-white">
            <div class="card-body">
                <h2 class="fw-bold text-primary mb-3">Selamat Datang di SIGMALAB, {{ Auth::user()->username ?? 'Pengguna' }}!</h2>
                <p class="text-muted fs-5">Sistem Informasi Laboratorium Pengujian & Kalibrasi - PT Sucofindo Cabang Cilacap.</p>
                <hr class="w-25 mx-auto my-4">
                <p class="text-secondary small">Silakan gunakan menu di sebelah kiri untuk mulai mengelola data operasional laboratorium.</p>
            </div>
        </div>
    </div>
</div>
@endsection