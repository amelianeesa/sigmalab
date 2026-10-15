<?php

namespace App\Console\Commands;

use App\Models\AuditlogArchive;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Activitylog\Models\Activity;

class ArchiveAuditLogs extends Command
{
    protected $signature = 'audit:archive';

    protected $description = 'Pindahkan audit log lebih dari 7 hari ke arsip dan hapus arsip lebih dari 2 tahun';

    public function handle()
    {
        $batasArsip = Carbon::now()->subDays(7);
        $batasHapus = Carbon::now()->subYears(2);
        $kolomArsip = array_diff(Schema::getColumnListing('auditlog_archives'), ['id']);
        $totalDiarsip = 0;

        Activity::where('created_at', '<', $batasArsip)
            ->orderBy('id')
            ->chunkById(500, function ($logs) use ($kolomArsip, &$totalDiarsip) {
                $rows = $logs->map(function ($log) use ($kolomArsip) {
                    return array_intersect_key($log->getAttributes(), array_flip($kolomArsip));
                })->all();

                DB::transaction(function () use ($rows, $logs) {
                    DB::table('auditlog_archives')->insert($rows);
                    Activity::whereIn('id', $logs->pluck('id'))->delete();
                });

                $totalDiarsip += count($rows);
            });

        $this->info($totalDiarsip > 0
            ? "Berhasil mengarsipkan {$totalDiarsip} log audit."
            : 'Tidak ada log audit yang perlu diarsip.');

        $totalDihapus = AuditlogArchive::where('created_at', '<', $batasHapus)->delete();

        if ($totalDihapus > 0) {
            $this->info("Berhasil menghapus {$totalDihapus} data arsip lama (lebih dari 2 tahun).");
        }

        return self::SUCCESS;
    }
}