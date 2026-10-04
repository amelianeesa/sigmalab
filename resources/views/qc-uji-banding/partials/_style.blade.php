<style>
    /* ===== QC Uji Banding – disamakan dengan alat/index ===== */
    .qc-page { font-size: 0.82rem; padding: 4px 20px !important; }
    .qc-page .qc-title { font-size: 1.1rem; font-weight: 700; margin: 0; }
    .qc-page .qc-subtitle { font-size: 0.78rem; color: #6c757d; margin: 2px 0 0; }

    .qc-page .card-body { padding: 10px; }
    .qc-page .card-body.p-4,
    .qc-page .accordion-body.p-4 { padding: 12px !important; }
    .qc-page .card-header { padding: 0.5rem 0.75rem !important; font-size: 0.85rem; }
    .qc-page .card-header h5,
    .qc-page .card-header h6 { font-size: 0.88rem; margin: 0; }
    .qc-page .modal-title { font-size: 0.9rem; }
    .qc-page .modal-content { font-size: 0.82rem; }

    .qc-page .alert { font-size: 0.8rem; padding: 0.4rem 0.75rem; margin-bottom: 0.6rem; }
    .qc-page .alert ul { padding-left: 1.1rem; }

    .qc-page .btn { font-size: 0.8rem; }
    .qc-page .btn:not(.btn-sm):not(.btn-close) { padding: 0.3rem 0.75rem; }
    .qc-page .btn.rounded-pill { border-radius: 6px !important; }
    .qc-page .form-control:not(.form-control-sm),
    .qc-page .form-select:not(.form-select-sm) { font-size: 0.82rem; padding: 0.3rem 0.55rem; }
    .qc-page .form-select-lg { font-size: 0.82rem; padding: 0.3rem 0.55rem; }
    .qc-page .form-label { font-size: 0.78rem; margin-bottom: 0.2rem; }
    .qc-page .badge { font-size: 0.68rem; }

    /* Input group (kolom "Jumlah Digunakan", pencarian, dll.) */
    .qc-page .input-group-sm > .form-control,
    .qc-page .input-group-sm > .form-select,
    .qc-page .input-group-sm > .input-group-text,
    .qc-page .input-group-sm > .btn {
        font-size: 0.72rem !important;
        padding: 0.2rem 0.5rem !important;
        min-height: 0;
        line-height: 1.4;
    }
    .qc-page .input-group-sm > .input-group-text { white-space: nowrap; }

    /* Tabel umum (bukan tabel input) */
    .qc-page .table:not(.param-table):not(.input-table) th,
    .qc-page .table:not(.param-table):not(.input-table) td {
        padding: 8px 10px; vertical-align: middle; font-size: 0.72rem;
    }
    .qc-page .table:not(.param-table):not(.input-table) thead th,
    .qc-page .table-corporate thead th {
        background-color: #1b3152 !important; color: #fff !important;
        border-color: #fff !important; font-size: 0.75rem;
        letter-spacing: 0; text-transform: none; padding: 8px 10px;
    }
    .qc-page .table-responsive > .table:not(.param-table):not(.input-table) { min-width: 480px; }
    .qc-page .qc-table-min { min-width: 760px !important; }

    /* Tombol */
    .btn-corporate-blue { background-color: #1b3152 !important; border-color: #1b3152 !important; color: #fff !important; }
    .btn-corporate-blue:hover, .btn-corporate-blue:focus, .btn-corporate-blue:active {
        background-color: #14253e !important; border-color: #14253e !important; color: #fff !important;
    }
    .btn-outline-corporate { color: #1b3152 !important; border-color: #1b3152 !important; }
    .btn-outline-corporate:hover { background-color: #1b3152 !important; color: #fff !important; }
    .qc-action-btn {
        width: 30px; height: 30px; display: inline-flex; align-items: center; justify-content: center;
        font-size: 0.75rem; padding: 0 !important; border-radius: 6px;
    }

    /* Tab, pills, accordion */
    .qc-page .nav-tabs { flex-wrap: nowrap; overflow-x: auto; overflow-y: hidden; }
    .qc-page .nav-tabs .nav-link { font-size: 0.8rem; padding: 0.4rem 0.75rem; white-space: nowrap; }
    .qc-page .nav-pills .nav-link { font-size: 0.8rem; padding: 0.4rem 0.75rem; }
    .qc-page .nav-pills .nav-link.active { background-color: #1b3152 !important; color: #fff !important; }
    .qc-page .nav-pills .nav-link:not(.active) { color: #1b3152; }
    .qc-page .nav-pills .nav-link:hover:not(.active) { background-color: #e9ecef; }
    .qc-page .accordion-button { font-size: 0.85rem; }

    /* Pagination */
    .qc-page .pagination .page-link { font-size: 0.72rem; padding: 0.2rem 0.55rem; }

    .qc-search { width: 300px; max-width: 100%; }

    /* ===== Partial (aft_correction, gcv_table, dst.) ===== */
    .qc-page .qc-partial { padding: 10px; }
    .qc-page .qc-section-title {
        font-size: 0.88rem; font-weight: 700; color: #1b3152;
        border-bottom: 1px solid #dee2e6; padding-bottom: 6px; margin-bottom: 10px;
    }
    .qc-page .qc-partial .alert { font-size: 0.8rem; padding: 0.4rem 0.75rem; margin-bottom: 0.6rem; border-radius: 6px; }

    /* Kotak info parameter (Reference No, BLNC ID, dst.) */
    .qc-page .param-ext-box { background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 6px; padding: 8px; margin-bottom: 10px; }
    .qc-page .param-ext-box label { font-size: 0.72rem; font-weight: 600; margin-bottom: 2px; display: block; }
    .qc-page .param-ext-box .form-control { font-size: 0.72rem; padding: 0.2rem 0.5rem; }

    /* Tabel input (param-table / input-table) */
    .qc-page .param-table th, .qc-page .param-table td,
    .qc-page .input-table th, .qc-page .input-table td {
        padding: 6px 8px; vertical-align: middle; font-size: 0.72rem; white-space: nowrap;
    }
    .qc-page .param-table thead th, .qc-page .input-table thead th {
        background-color: #1b3152 !important; color: #fff !important;
        border-color: #fff !important; font-size: 0.75rem; font-weight: 600;
    }
    .qc-page .param-table thead th.th-final { background-color: #f0ad4e !important; color: #1b3152 !important; }
    .qc-page .param-table .form-control,
    .qc-page .param-table .form-select,
    .qc-page .input-table .form-control,
    .qc-page .input-table .form-select { font-size: 0.72rem; padding: 0.2rem 0.4rem; }
    .qc-page .param-table .form-control[readonly] { background-color: #f1f3f5; border: 0; }
    .qc-page .param-table tfoot td { background: #fff; }
    .qc-page .param-table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; border: 1px solid #dee2e6; border-radius: 6px; }
    .qc-page .param-table-wrap > .table { margin-bottom: 0; }
    .qc-page .scroll-hint { display: none; font-size: 0.7rem; color: #6c757d; margin-bottom: 4px; }

    /* Tombol aksi baris */
    .qc-page .qc-row-actions { display: flex; flex-wrap: wrap; gap: 6px; }

    /* ===== Responsif ===== */
    @media (max-width: 767.98px) {
        .qc-page { padding: 4px 10px !important; }
        .qc-page .qc-title { font-size: 1rem; }
        .qc-search { width: 100%; }
        .qc-page .qc-stack-sm > .btn,
        .qc-page .qc-stack-sm > a.btn { width: 100%; }
        .qc-page .qc-side-nav { flex-direction: row !important; flex-wrap: wrap; gap: 4px; }
        .qc-page .qc-side-nav > div { flex: 0 0 100%; }
        .qc-page .border-end-md { border-right: 0 !important; border-bottom: 1px solid #dee2e6; padding-bottom: 8px; margin-bottom: 10px; }

        .qc-page .scroll-hint { display: block; }
        .qc-page .qc-partial { padding: 6px 2px; }
        .qc-page .qc-row-actions > .btn { flex: 1 1 100%; }
        .qc-page .param-ext-box .col-md-2 { flex: 0 0 50%; max-width: 50%; }
    }
</style>