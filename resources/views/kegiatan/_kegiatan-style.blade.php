<style>
    .kg-page { padding: 4px 20px !important; font-size: 0.82rem; }
    .kg-page .card-body { padding: 10px !important; }

    /* ===== Breadcrumb & judul ===== */
    .kg-page .breadcrumb { font-size: 0.75rem; margin: 6px 0 10px; padding: 0; flex-wrap: wrap; }
    .kg-page .breadcrumb-item a { color: #1b3152; }
    .kg-page .page-title { font-size: 1.1rem; font-weight: 700; margin: 0; }
    .kg-page .section-title {
        font-size: 0.9rem; font-weight: 700; color: #1b3152;
        border-bottom: 1px solid #dee2e6; padding-bottom: 0.4rem; margin: 0 0 0.75rem;
    }
    .kg-page .cursor-pointer { cursor: pointer; }

    /* ===== Notifikasi (sama dengan alat/index) ===== */
    .kg-page .alert { font-size: 0.8rem; padding: 0.5rem 1rem; }

    /* ===== Form ===== */
    .kg-page .form-label { font-size: 0.75rem; font-weight: 600; color: #495057; margin-bottom: 0.25rem; }
    .kg-page .form-control,
    .kg-page .form-select { font-size: 0.82rem; border-radius: 6px; min-width: 0; }
    .kg-page .form-select {
        padding-right: 2.25rem; background-position: right 0.75rem center; background-size: 12px 10px;
        text-overflow: ellipsis; white-space: nowrap; overflow: hidden;
        -webkit-appearance: none; -moz-appearance: none; appearance: none;
    }
    .kg-page .form-control:focus,
    .kg-page .form-select:focus {
        border-color: #1b3152; box-shadow: 0 0 0 0.15rem rgba(27, 49, 82, 0.15);
    }
    .kg-page .form-check-input.check-lg { width: 1.15rem; height: 1.15rem; margin-top: 0; cursor: pointer; }
    .kg-page .form-check-input:checked { background-color: #1b3152; border-color: #1b3152; }
    .kg-page .crm-locked { pointer-events: none; opacity: 0.6; }

    /* Input di dalam tabel: kompak, sama dengan ukuran teks tabel */
    .kg-page .table .form-control,
    .kg-page .table .form-select,
    .kg-page .table .input-group-text {
        font-size: 0.72rem; padding: 0.2rem 0.5rem; height: 30px; min-height: 0; line-height: 1.4;
    }
    .kg-page .table td .input-group { flex-wrap: nowrap; }
    .kg-page .table .input-group > .form-control { min-width: 0; }
    .kg-page .table .input-group > .input-group-text {
        min-width: 54px; justify-content: center; background: #f1f3f5; color: #495057; font-weight: 600;
    }
    .kg-page .table .form-control:disabled { background-color: #e9ecef; }

    /* Item alat (kotak yang mudah disentuh) */
    .kg-page .alat-item {
        border: 1px solid #e9ecef; border-radius: 6px; padding: 0.45rem 0.6rem 0.45rem 2rem; height: 100%;
    }
    .kg-page .alat-item .form-check-input { margin-left: -1.5rem; }
    .kg-page .alat-item label { font-size: 0.78rem; cursor: pointer; }

    /* ===== Tombol ===== */
    .kg-page .btn-corporate-blue {
        background-color: #1b3152 !important; border-color: #1b3152 !important; color: #fff !important;
    }
    .kg-page .btn-corporate-blue:hover,
    .kg-page .btn-corporate-blue:focus,
    .kg-page .btn-corporate-blue:active {
        background-color: #14253e !important; border-color: #14253e !important; color: #fff !important;
    }
    .kg-page .btn-sm { font-size: 0.8rem; }

    /* ===== Tabel (sama dengan alat/index) ===== */
    .kg-page .table th,
    .kg-page .table td { padding: 8px 10px !important; vertical-align: middle !important; font-size: 0.72rem !important; }
    .kg-page .table thead th {
        font-size: 0.75rem !important; background-color: #1b3152 !important; color: #fff !important;
        border-color: #fff !important; white-space: nowrap;
    }
    .kg-page .table .badge { font-size: 0.68rem; }
    .kg-page .table-bordered > :not(caption) > * > * { border-color: #dee2e6; }

    /* ===== Kartu info (halaman detail) ===== */
    .kg-page .card-header { background: #fff; border-bottom: 1px solid #e9ecef; padding: 10px 12px !important; }
    .kg-page .card-header .card-title-sm { font-size: 0.88rem; font-weight: 700; color: #1b3152; margin: 0; }
    .kg-page .card-header.card-header-corp { background: #1b3152; border-bottom: 0; }
    .kg-page .card-header.card-header-corp .card-title-sm { color: #fff; }
    .kg-page .info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 10px 24px; }
    .kg-page .info-label { display: block; font-size: 0.7rem; font-weight: 600; color: #6c757d; }
    .kg-page .info-value { font-size: 0.82rem; font-weight: 600; word-break: break-word; }

    /* ===== Sel CRM di matriks parameter ===== */
    .kg-page .crm-cell { display: flex; align-items: center; justify-content: center; gap: 8px; flex-wrap: wrap; }
    .kg-page .crm-dropdown-wrapper { flex: 1 1 180px; min-width: 0; max-width: 250px; }

    /* ===== Mobile ===== */
    @media (max-width: 767.98px) {
        .kg-page { padding: 4px 10px !important; }

        /* Input umum di HP: kompak, sama dengan alat/index */
        .kg-page .form-control,
        .kg-page .form-select { font-size: 0.82rem; min-height: 34px; padding-top: 0.25rem; padding-bottom: 0.25rem; }
        .kg-page .form-check-input.check-lg { width: 1.05rem; height: 1.05rem; }

        /* Tabel jadi kartu */
        .table-stack thead { display: none; }
        .table-stack, .table-stack tbody, .table-stack tr, .table-stack td { display: block; width: 100%; }
        .table-stack tbody tr {
            border: 1px solid #dee2e6; border-radius: 8px; margin: 0 0 10px; padding: 6px 12px;
            background: #fff; box-shadow: 0 1px 3px rgba(0, 0, 0, .06);
        }
        .table-stack td {
            display: flex; justify-content: space-between; align-items: center; gap: 12px;
            text-align: right !important; border: 0 !important; padding: 5px 0 !important; background: transparent !important;
        }
        .table-stack td[data-label]::before {
            content: attr(data-label); font-weight: 600; color: #1b3152; text-align: left; flex-shrink: 0; max-width: 42%;
        }
        .table-stack td .form-control,
        .table-stack td .input-group { max-width: 58%; }
        .table-stack td .form-control,
        .table-stack td .input-group { max-width: 55%; }
        .kg-page .table-stack .form-control,
        .kg-page .table-stack .input-group-text { height: 30px; font-size: 0.75rem; padding: 0.15rem 0.5rem; min-height: 0; }
        .kg-page .table-stack .input-group > .input-group-text { min-width: 46px; font-weight: 600; }
        .table-stack td.td-aksi { justify-content: flex-end; border-top: 1px solid #eee !important; margin-top: 4px; padding-top: 8px !important; }
        .table-stack td.td-aksi::before { display: none; }
        .table-stack td.td-empty { display: block; text-align: center !important; }
        .table-stack td.td-crm { flex-wrap: wrap; }
        .table-stack td.td-crm .crm-cell { flex: 1; justify-content: flex-end; }
        .table-stack td.td-crm .crm-dropdown-wrapper { flex: 1 1 100%; max-width: none; }
        .table-stack td.td-crm .crm-dropdown-wrapper .form-select { max-width: 100%; }
    }
</style>