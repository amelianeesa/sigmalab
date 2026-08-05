<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Curriculum Vitae - {{ $personil->nama }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-secondary d-flex flex-column min-vh-100">
    <nav class="navbar navbar-dark bg-dark px-3">
        <span class="navbar-brand mb-0 h1">CV Personil: {{ $personil->nama }}</span>
        <a href="{{ route('sdm.index') }}" class="btn btn-outline-light btn-sm">Tutup / Kembali</a>
    </nav>
    <div class="container-fluid flex-grow-1 d-flex flex-column p-3">
        @if(!empty($cvUrl))
            <iframe src="{{ $cvUrl }}" class="w-100 flex-grow-1 rounded shadow" style="min-height: 85vh;" frameborder="0"></iframe>
        @else
            <div class="alert alert-warning text-center mt-5">File CV tidak ditemukan atau belum diunggah untuk personil ini.</div>
        @endif
    </div>
</body>
</html>