{{--
    resources/views/qc-crm/_qc-compact.blade.php
    Gaya kompak bersama untuk semua halaman QC CRM (mengikuti alat/index).
    Semua aturan di-scope ke .qc-compact sehingga tidak mengganggu halaman lain.
    Pakai: @include('qc-crm._qc-compact')  +  tambahkan class "qc-compact" di container utama.
--}}
<style>
    /* ============ BASE ============ */
    .qc-compact { font-size: 0.82rem; padding-top: 4px !important; }
    .qc-compact .card-body { padding: 10px !important; }
    .qc-compact .card-header { padding: 10px 14px !important; }
    .qc-compact .card-header h5,
    .qc-compact .card-header .h5 { font-size: 0.9rem !important; margin-bottom: 0; }
    .qc-compact .p-4 { padding: 12px !important; }
    .qc-compact .p-3 { padding: 10px !important; }
    .qc-compact .mb-4 { margin-bottom: 0.9rem !important; }
    .qc-compact .mt-3 { margin-top: 0.6rem !important; }

    /* ============ JUDUL & TEKS ============ */
    .qc-compact h2 { font-size: 1.1rem !important; font-weight: 700; margin-bottom: 0.2rem !important; }
    .qc-compact h2 i { font-size: 0.95rem; }
    .qc-compact h4 { font-size: 1rem !important; }
    .qc-compact h5 { font-size: 0.9rem !important; }
    .qc-compact h6 { font-size: 0.82rem !important; }
    .qc-compact p.text-muted, .qc-compact .text-muted.mb-0 { font-size: 0.78rem; }
    .qc-compact .small, .qc-compact small { font-size: 0.72rem !important; }
    .qc-compact .fs-1 { font-size: 1.6rem !important; }
    .qc-compact .fs-3 { font-size: 1.1rem !important; }
    .qc-compact .fs-4 { font-size: 1rem !important; }
    .qc-compact .fs-5 { font-size: 0.9rem !important; }
    .qc-compact .fs-6 { font-size: 0.75rem !important; }
    .qc-compact .fa-4x { font-size: 2.2rem !important; }
    .qc-compact .fa-3x { font-size: 1.8rem !important; }

    /* ============ BREADCRUMB ============ */
    .qc-compact .breadcrumb { font-size: 0.78rem; margin-bottom: 0.5rem; }
    .qc-compact .breadcrumb-item, .qc-compact .breadcrumb-item a { font-size: 0.78rem; }
    .qc-compact .breadcrumb .dropdown-toggle { font-size: 0.78rem; }

    /* ============ FORM ============ */
    .qc-compact .form-label { font-size: 0.75rem; margin-bottom: 0.2rem; }
    .qc-compact .form-control,
    .qc-compact .form-select,
    .qc-compact .form-select-lg,
    .qc-compact .form-control-lg {
        font-size: 0.8rem !important;
        padding: 0.3rem 0.6rem !important;
        min-height: 0;
    }
    .qc-compact .form-text { font-size: 0.68rem; }
    .qc-compact .form-check-label { font-size: 0.78rem; }
    .qc-compact .input-group-text { font-size: 0.75rem; padding: 0.3rem 0.6rem; }
    .qc-compact .form-check-input { margin-top: 0.2em; }
    .qc-compact textarea.form-control { font-size: 0.8rem !important; }

    /* ============ TOMBOL (samakan dengan btn-corporate-blue alat/index) ============ */
    .qc-compact .btn { font-size: 0.78rem; padding: 0.3rem 0.75rem; }
    .qc-compact .btn-sm { font-size: 0.72rem; padding: 0.22rem 0.55rem; }
    .qc-compact .btn-lg { font-size: 0.85rem; padding: 0.4rem 1rem; }
    .qc-compact .btn i { font-size: 0.72rem; }
    .qc-compact .rounded-pill.btn { border-radius: 6px !important; }

    .btn-corporate-blue {
        background-color: #1b3152 !important; border-color: #1b3152 !important; color: #fff !important;
    }
    .btn-corporate-blue:hover, .btn-corporate-blue:focus, .btn-corporate-blue:active {
        background-color: #14253e !important; border-color: #14253e !important; color: #fff !important;
    }
    .btn-outline-corporate { color: #1b3152 !important; border-color: #1b3152 !important; }
    .btn-outline-corporate:hover { background-color: #1b3152 !important; color: #fff !important; }

    /* Tombol utama (btn-primary) ikut warna korporat */
    .qc-compact .btn-primary { background-color: #1b3152; border-color: #1b3152; }
    .qc-compact .btn-primary:hover, .qc-compact .btn-primary:focus { background-color: #14253e; border-color: #14253e; }
    .qc-compact .btn-outline-primary { color: #1b3152; border-color: #1b3152; }
    .qc-compact .btn-outline-primary:hover { background-color: #1b3152; color: #fff; }
    .qc-compact .text-primary { color: #1b3152 !important; }
    .qc-compact .badge.bg-primary { background-color: #1b3152 !important; }

    /* ============ BADGE ============ */
    .qc-compact .badge { font-size: 0.68rem; font-weight: 600; padding: 0.3em 0.55em; }
    .qc-compact .badge.fs-6 { font-size: 0.72rem !important; }

    /* ============ ALERT ============ */
    .qc-compact .alert { font-size: 0.78rem; padding: 0.45rem 0.75rem; margin-bottom: 0.6rem; }
    .qc-compact .alert h4, .qc-compact .alert h6 { font-size: 0.85rem !important; }

    /* ============ TABEL (sama seperti alat/index) ============ */
    .qc-compact .table th,
    .qc-compact .table td {
        padding: 8px 10px !important;
        vertical-align: middle !important;
        font-size: 0.72rem !important;
    }
    .qc-compact .table thead th { font-size: 0.75rem !important; }

    .qc-compact .table-corporate thead th,
    .qc-compact .table thead.table-corporate th {
        background-color: #1b3152 !important;
        color: #fff !important;
        border-color: #fff !important;
        font-size: 0.72rem !important;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        padding-top: 10px !important;
        padding-bottom: 10px !important;
    }

    /* Tabel input (param-table) diperkecil agar muat */
    .qc-compact .param-table th,
    .qc-compact .param-table td { padding: 5px 8px !important; }
    .qc-compact .param-table td input.form-control { min-width: 80px; font-size: 0.75rem !important; padding: 0.22rem 0.4rem !important; }
    .qc-compact .param-table td select.form-select { min-width: 110px; font-size: 0.75rem !important; }

    /* ============ TAB (navy, tanpa ungu) ============ */
    .qc-compact .nav-tabs { margin-bottom: 0.75rem !important; }
    .qc-compact .nav-tabs .nav-link {
        font-size: 0.8rem; padding: 0.45rem 0.9rem !important;
        color: #6c757d; border: none; border-bottom: 3px solid transparent; border-radius: 0;
    }
    .qc-compact .nav-tabs .nav-link:hover { color: #1b3152; border-bottom: 3px solid #dee2e6; }
    .qc-compact .nav-tabs .nav-link.active {
        color: #1b3152 !important; background: transparent !important;
        border: none !important; border-bottom: 3px solid #1b3152 !important;
    }
    .qc-compact .nav-tabs .nav-link i { font-size: 0.75rem; }
    .qc-compact .nav-tabs .nav-link.active i { color: #1b3152 !important; }

    /* ============ ACCORDION (input harian) ============ */
    .qc-compact .accordion-button { font-size: 0.8rem; padding: 0.5rem 0.9rem !important; }
    .qc-compact .accordion-body { padding: 12px !important; }
    .qc-compact .param-enable-check { transform: scale(1.15) !important; }

    /* ============ MODAL ============ */
    .qc-compact-modal .modal-title,
    .modal .modal-title { font-size: 0.88rem; }
    .modal .modal-body { font-size: 0.8rem; }
    .modal .modal-header { padding: 0.5rem 0.9rem; }
    .modal .modal-footer .btn { font-size: 0.75rem; padding: 0.25rem 0.7rem; }

    /* ============ DATATABLES ============ */
    .qc-compact .dataTables_wrapper { font-size: 0.75rem; }
    .qc-compact .dataTables_wrapper .form-select,
    .qc-compact .dataTables_wrapper select { font-size: 0.75rem; padding: 0.15rem 1.5rem 0.15rem 0.5rem; }
    .qc-compact .dataTables_wrapper .page-item .page-link { padding: 4px 10px !important; font-size: 0.72rem; }
    .qc-compact .dataTables_wrapper .dataTables_info { font-size: 0.72rem; }

    /* ============ LIVE PREVIEW ============ */
    .qc-compact .preview-box .card-body { padding: 8px 10px !important; }
    .qc-compact .preview-box strong.fs-5 { font-size: 0.85rem !important; }
</style>