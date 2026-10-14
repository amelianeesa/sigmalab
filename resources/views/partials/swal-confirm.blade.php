{{--
    Konfirmasi SweetAlert2 yang seragam dan responsif (pengganti confirm() bawaan browser).

    Pemakaian pada tombol submit di dalam <form>:
        <button type="submit"
                data-swal-confirm="Catat alat ini sudah dikembalikan?"
                data-swal-title="Alat Sudah Dikembalikan?"   (opsional)
                data-swal-ok="Ya, Catat"                      (opsional)
                data-swal-icon="question"                     (opsional: question|warning|info|error)
                data-swal-danger>                             (opsional: tombol merah + fokus ke Batal)

    Sertakan di dalam @push('scripts') ... @endpush, SESUDAH SweetAlert2 dimuat oleh layout.
--}}
<style>
    .swal2-popup {
        font-size: 0.85rem !important;
        padding: 1.5rem 1.25rem 1.75rem !important;
        border-radius: 1rem !important;
        width: 30em !important;
        max-width: calc(100vw - 2rem) !important;
    }
    .swal2-title {
        font-size: 1.15rem !important;
        font-weight: 700 !important;
        padding: 0 !important;
        margin-bottom: 0.4rem !important;
        color: #212529 !important;
    }
    .swal2-html-container {
        font-size: 0.85rem !important;
        color: #6c757d !important;
        margin: 0.4rem 0.25rem 0 !important;
        overflow-wrap: anywhere;
    }
    .swal2-icon {
        width: 3.6em !important;
        height: 3.6em !important;
        margin: 0.3em auto 0.7em !important;
    }
    .swal2-icon .swal2-icon-content { font-size: 2em !important; }
    .swal2-actions {
        margin-top: 1.35rem !important;
        gap: 0.75rem !important;
    }
    .swal2-styled {
        font-size: 0.85rem !important;
        font-weight: 600 !important;
        padding: 0.55rem 1.3rem !important;
        border-radius: 0.4rem !important;
        line-height: 1.3 !important;
    }
    /* HP: tombol memenuhi lebar dan tersusun vertikal (aksi utama di atas) supaya mudah disentuh */
    @media (max-width: 480px) {
        .swal2-popup { padding: 1.25rem 1rem 1.5rem !important; }
        .swal2-actions { flex-direction: column-reverse; width: 100%; }
        .swal2-actions .swal2-styled { width: 100%; margin: 0 !important; padding: 0.7rem 1rem !important; }
    }
</style>
<script>
(function () {
    if (window.__swalConfirmReady) return;
    window.__swalConfirmReady = true;

    document.addEventListener('click', function (e) {
        var btn = e.target.closest('[data-swal-confirm]');
        if (!btn) return;

        e.preventDefault();
        var form = btn.closest('form');

        // Validasi bawaan (required, min, dll.) tetap jalan sebelum dialog muncul.
        if (form && !form.reportValidity()) return;

        var danger = btn.hasAttribute('data-swal-danger');

        Swal.fire({
            title: btn.dataset.swalTitle || 'Apakah Anda yakin?',
            text: btn.dataset.swalConfirm,
            icon: btn.dataset.swalIcon || (danger ? 'warning' : 'question'),
            showCancelButton: true,
            confirmButtonColor: danger ? '#d33' : '#1a7fc4',
            cancelButtonColor: '#6c757d',
            confirmButtonText: btn.dataset.swalOk || 'Ya, Lanjutkan',
            cancelButtonText: 'Batal',
            focusCancel: danger
        }).then(function (result) {
            if (result.isConfirmed && form) {
                btn.disabled = true;   // cegah klik ganda
                form.submit();
            }
        });
    });
})();
</script>