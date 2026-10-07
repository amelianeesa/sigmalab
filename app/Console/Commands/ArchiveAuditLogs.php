<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Activitylog\Models\Activity;
use App\Models\AuditlogArchive;
use Carbon\Carbon;

class ArchiveAuditLogs extends Command
{
    protected $signature = 'audit:archive';
    protected $description = 'Memindahkan audit log lama ke tabel arsip secara berkala';
    public function handle()
    {
        $threshold = Carbon::now()->subDays(7);
        $oldLogs = Activity::where('created_at', '<', $threshold)->get();

        if ($oldLogs->count() > 0) {
            foreach ($oldLogs as $log) {
                AuditlogArchive::create($log->toArray());
                $log->delete();
            }
            $this->info("Berhasil mengarsipkan {$oldLogs->count()} log audit.");
        } else {
            $this->info("Tidak ada log audit yang perlu diarsip.");
        }

        $thresholdArchive = Carbon::now()->subDays(730);
        $deletedCount = AuditlogArchive::where('created_at', '<', $thresholdArchive)->delete();

        if ($deletedCount > 0) {
            $this->info("Berhasil menghapus {$deletedCount} data arsip lama (> 2 tahun).");
        }
    }
}