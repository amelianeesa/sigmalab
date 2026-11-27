<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QcHarian;
use App\Models\QcCrm;
use App\Models\CrmVerifikasiTeknis;
use App\Models\QcUjiBandingParameter;
use App\Models\SampelInhouse;

class LaporanInvestigasiController extends Controller
{
    public function index()
    {
        $items = collect();

        // 1. QC Harian In-House
        $qcHarians = QcHarian::with(['parameterUji', 'sampelInhouse', 'analis'])
            ->where('status_evaluasi', 'outlier')
            ->get();

        foreach ($qcHarians as $row) {
            $items->push([
                'tanggal' => $row->tanggal_uji ? $row->tanggal_uji->format('Y-m-d') : $row->created_at->format('Y-m-d'),
                'tanggal_fmt' => $row->tanggal_uji ? $row->tanggal_uji->format('d M Y') : $row->created_at->format('d M Y'),
                'jenis_qc' => 'QC Harian In-House',
                'badge_qc' => 'bg-primary',
                'kode_sampel' => $row->sampelInhouse->kode_batch ?? ($row->data_mentah['nama_sampel_uji'] ?? '-'),
                'parameter' => strtoupper($row->parameterUji->nama_parameter ?? '-'),
                'analis' => $row->analis ? ($row->analis->name ?? $row->analis->username) : '-',
                'nilai_uji' => number_format($row->nilai_akhir, 2),
                'nilai_acuan' => number_format($row->mean_acuan, 2) . ' (± ' . number_format($row->sd_acuan, 2) . ')',
                'status_investigasi' => $row->status_investigasi ?? 'menunggu_investigasi',
                'akar_masalah' => $row->akar_masalah ?? '-',
                'catatan_investigasi' => $row->catatan_investigasi ?? '-',
                'tindakan_perbaikan' => $row->tindakan_perbaikan ?? '-',
                'url_isi' => route('qc-harian.investigasi', $row->id),
                'url_pdf' => $row->status_investigasi === 'selesai_investigasi' ? route('qc-harian.investigasi.pdf', $row->id) : null,
            ]);
        }

        // 2. QC CRM Harian
        $qcCrms = QcCrm::with(['parameterUji', 'crmKatalog', 'analis'])
            ->where('status_evaluasi', 'outlier')
            ->get();

        foreach ($qcCrms as $row) {
            $items->push([
                'tanggal' => $row->tanggal_uji ? $row->tanggal_uji->format('Y-m-d') : $row->created_at->format('Y-m-d'),
                'tanggal_fmt' => $row->tanggal_uji ? $row->tanggal_uji->format('d M Y') : $row->created_at->format('d M Y'),
                'jenis_qc' => 'QC CRM Harian',
                'badge_qc' => 'bg-info text-dark',
                'kode_sampel' => $row->crmKatalog->nomor_lot ?? '-',
                'parameter' => strtoupper($row->parameterUji->nama_parameter ?? '-'),
                'analis' => $row->analis->nama ?? '-',
                'nilai_uji' => number_format($row->nilai_akhir, 2),
                'nilai_acuan' => number_format($row->cert_value, 2) . ' ± ' . number_format($row->cert_u, 4),
                'status_investigasi' => $row->status_investigasi ?? 'menunggu_investigasi',
                'akar_masalah' => $row->akar_masalah ?? '-',
                'catatan_investigasi' => $row->catatan_investigasi ?? '-',
                'tindakan_perbaikan' => $row->tindakan_perbaikan ?? '-',
                'url_isi' => route('qc-crm.investigasi', $row->id),
                'url_pdf' => $row->status_investigasi === 'selesai_investigasi' ? route('qc-crm.investigasi.pdf', $row->id) : null,
            ]);
        }

        // 3. Verifikasi Teknis CRM
        $verifCrms = CrmVerifikasiTeknis::with(['parameterUji', 'crmKatalog'])
            ->where('status_evaluasi', 'outlier')
            ->get();

        foreach ($verifCrms as $row) {
            $items->push([
                'tanggal' => $row->created_at->format('Y-m-d'),
                'tanggal_fmt' => $row->created_at->format('d M Y'),
                'jenis_qc' => 'Verifikasi Teknis CRM',
                'badge_qc' => 'bg-secondary',
                'kode_sampel' => $row->crmKatalog->nomor_lot ?? '-',
                'parameter' => strtoupper($row->parameterUji->nama_parameter ?? '-'),
                'analis' => '-',
                'nilai_uji' => number_format($row->nilai_akhir, 2),
                'nilai_acuan' => ($row->crmKatalog ? 'Botol CRM' : '-'),
                'status_investigasi' => $row->status_investigasi ?? 'menunggu_investigasi',
                'akar_masalah' => $row->akar_masalah ?? '-',
                'catatan_investigasi' => $row->catatan_investigasi ?? '-',
                'tindakan_perbaikan' => $row->tindakan_perbaikan ?? '-',
                'url_isi' => route('crm-katalog.verifikasi-teknis.investigasi', [$row->crm_katalog_id, $row->id]),
                'url_pdf' => null,
            ]);
        }

        // 4. QC Uji Banding
        $ujiBandings = QcUjiBandingParameter::with(['parameterUji', 'qcUjiBanding', 'analis'])
            ->where('status_evaluasi', 'outlier')
            ->get();

        foreach ($ujiBandings as $row) {
            $items->push([
                'tanggal' => $row->created_at->format('Y-m-d'),
                'tanggal_fmt' => $row->created_at->format('d M Y'),
                'jenis_qc' => 'QC Uji Banding',
                'badge_qc' => 'bg-dark',
                'kode_sampel' => $row->qcUjiBanding->kode_sampel ?? ($row->qcUjiBanding->nama_program ?? '-'),
                'parameter' => strtoupper($row->parameterUji->nama_parameter ?? '-'),
                'analis' => $row->analis->nama ?? '-',
                'nilai_uji' => number_format($row->nilai_akhir, 2),
                'nilai_acuan' => 'Z-Score: ' . ($row->z_score ?? '-'),
                'status_investigasi' => $row->status_investigasi ?? 'menunggu_investigasi',
                'akar_masalah' => $row->akar_masalah ?? '-',
                'catatan_investigasi' => $row->catatan_investigasi ?? '-',
                'tindakan_perbaikan' => $row->tindakan_perbaikan ?? '-',
                'url_isi' => route('qc-uji-banding.investigasi', [$row->qc_uji_banding_id, $row->id]),
                'url_pdf' => $row->status_investigasi === 'selesai_investigasi' ? route('qc-uji-banding.cetak.pdf', [$row->qc_uji_banding_id, $row->id]) : null,
            ]);
        }

        // 5. QC In-House (Gagal Homogenitas / Stabilitas)
        $inhouseFails = SampelInhouse::with('investigator')
            ->where(function ($q) {
                $q->whereIn('status', ['gagal_homogenitas', 'gagal_stabilitas'])
                ->orWhereNotNull('akar_masalah');
            })
            ->get();

        foreach ($inhouseFails as $row) {
            $sudahDiisi = !empty($row->akar_masalah);
            $items->push([
                'tanggal' => $row->tanggal_investigasi ? \Carbon\Carbon::parse($row->tanggal_investigasi)->format('Y-m-d') : $row->updated_at->format('Y-m-d'),
                'tanggal_fmt' => $row->tanggal_investigasi ? \Carbon\Carbon::parse($row->tanggal_investigasi)->format('d M Y') : $row->updated_at->format('d M Y'),
                'jenis_qc' => 'QC In-House',
                'badge_qc' => 'bg-warning text-dark',
                'kode_sampel' => $row->kode_batch ?? '-',
                'parameter' => $row->status === 'gagal_homogenitas' ? 'Homogenitas' : ($row->status === 'gagal_stabilitas' ? 'Stabilitas' : 'Homogenitas / Stabilitas'),
                'analis' => $row->investigator->username ?? '-',
                'nilai_uji' => 'Gagal',
                'nilai_acuan' => 'Uji Batch In-House',
                'status_investigasi' => $sudahDiisi ? 'selesai_investigasi' : 'menunggu_investigasi',
                'akar_masalah' => $row->akar_masalah ?? '-',
                'catatan_investigasi' => '-',
                'tindakan_perbaikan' => $row->tindakan_perbaikan ?? '-',
                'url_isi' => route('qc-inhouse.show', $row->sampel_inhouse_id),
                'url_pdf' => null,
            ]);
        }

        $laporanList = $items->sortByDesc('tanggal')->values();

        $totalOutlier = $laporanList->count();
        $menungguCount = $laporanList->where('status_investigasi', 'menunggu_investigasi')->count();
        $selesaiCount = $laporanList->where('status_investigasi', 'selesai_investigasi')->count();

        return view('laporan-investigasi.index', compact('laporanList', 'totalOutlier', 'menungguCount', 'selesaiCount'));
    }
}