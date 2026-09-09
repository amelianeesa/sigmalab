@props(['status'])

@php
    $color = match(strtolower($status)) {
        'aktif', 'selesai', 'terima', 'yes', 'in control' => 'success',
        'nonaktif', 'batal', 'tolak', 'no', 'out of control', 'gagal_duplo', 'rusak' => 'danger',
        'pending', 'proses', 'warning', 'perbaikan' => 'warning',
        default => 'secondary'
    };
@endphp

<span class="badge bg-{{ $color }}">{{ strtoupper(str_replace('_', ' ', $status)) }}</span>
