@if(($jenisGrafik ?? 'in_house') === 'in_house')
<div class="alert pu-limit-alert {{ $newPointsCount >= 20 ? 'pu-limit-success' : 'pu-limit-info' }} shadow-sm mb-3">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 pu-limit-inner">
        <div class="pu-limit-text">
            <h6 class="fw-bold mb-1 pu-limit-title">
                @if($newPointsCount >= 20)
                    <i class="fas fa-check-circle me-2 text-success"></i> Status Re-evaluasi Limit: Siap Diperbarui!
                @else
                    <i class="fas fa-sync-alt me-2 text-info"></i> Status Re-evaluasi Limit: Mengumpulkan Data
                @endif
            </h6>
            @if($newPointsCount >= 20)
                <span class="text-dark pu-limit-desc">Terdapat cukup data baru untuk mengevaluasi ulang batas statistik grafik ini. Silakan ke menu Master Parameter untuk menghitung batas baru.</span>
            @else
                <span class="text-dark pu-limit-desc">Sistem sedang mengumpulkan data pengujian baru sejak batas grafik terakhir diperbarui. Belum cukup untuk re-evaluasi (minimal 20 data).</span>
            @endif
        </div>
        <div class="text-end pu-limit-count">
            <h4 class="fw-bold mb-0 pu-limit-number {{ $newPointsCount >= 20 ? 'text-success' : 'text-primary' }}">{{ $newPointsCount }} / 20</h4>
            <small class="text-muted fw-bold pu-limit-label">Data Baru Terkumpul</small>
        </div>
    </div>
</div>

<style>
    .pu-limit-alert {
        padding: 0.75rem 1rem;
    }
    .pu-limit-title { font-size: 0.85rem !important; }
    .pu-limit-desc { font-size: 0.75rem !important; }
    .pu-limit-number { font-size: 1.3rem !important; }
    .pu-limit-label { font-size: 0.68rem !important; }

    .pu-limit-success { background-color: #eafaf1; border-color: #a7e8bf; }
    .pu-limit-info { background-color: #eaf4fb; border-color: #a9d4ee; }

    @media (max-width: 575.98px) {
        .pu-limit-inner { flex-direction: column; align-items: flex-start !important; }
        .pu-limit-count { text-align: left !important; margin-top: 0.4rem; }
    }
</style>
@endif