@if(($jenisGrafik ?? 'in_house') === 'in_house')
<div class="alert {{ $newPointsCount >= 20 ? 'alert-success border-success' : 'alert-info border-info' }} shadow-sm mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h6 class="fw-bold mb-1">
                @if($newPointsCount >= 20)
                    <i class="fas fa-check-circle me-2 text-success"></i> Status Re-evaluasi Limit: Siap Diperbarui!
                @else
                    <i class="fas fa-sync-alt me-2 text-info"></i> Status Re-evaluasi Limit: Mengumpulkan Data
                @endif
            </h6>
            @if($newPointsCount >= 20)
                <span class="text-dark small">Terdapat cukup data baru untuk mengevaluasi ulang batas statistik grafik ini. Silakan ke menu Master Parameter untuk menghitung batas baru.</span>
            @else
                <span class="text-dark small">Sistem sedang mengumpulkan data pengujian baru sejak batas grafik terakhir diperbarui. Belum cukup untuk re-evaluasi (minimal 20 data).</span>
            @endif
        </div>
        <div class="text-right">
            <h4 class="fw-bold mb-0 {{ $newPointsCount >= 20 ? 'text-success' : 'text-primary' }}">{{ $newPointsCount }} / 20</h4>
            <small class="text-muted fw-bold">Data Baru Terkumpul</small>
        </div>
    </div>
</div>
@endif
