<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard - SIGMALAB Sucofindo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        #content {
            margin-left: 0;
            padding: 30px;
        }

        .top-navbar {
            background: #fff;
            padding: 15px 30px;
            border-bottom: 1px solid #dee2e6;
            margin-left: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>
</head>

<body>
    <!-- Sidebar removed: content now full-width -->

    <div class="top-navbar shadow-sm">
        <div>
            <span class="text-uppercase text-secondary fs-7 fw-bold d-block mb-1" style="font-size: 20  px; letter-spacing: 1px;">MANAJEMEN SDM</span>
            <h7 class="mb-0 fw-bold">Kelola profil personil, kompetensi, dan kepatuhan sertifikasi laboratorium.</h7>
        </div>
        <div class="dropdown">
            <button class="btn btn-light dropdown-toggle border" type="button" data-bs-toggle="dropdown">
                <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->username ?? 'Admin' }}
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger"><i
                                class="bi bi-box-arrow-right me-2"></i> Logout</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>

    <div id="content">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>