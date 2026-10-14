<?php

namespace App\Support;

/**
 * Helper tunggal untuk workspace aktif (LAB / NON_LAB).
 * Nilainya selalu di-set ulang tiap request oleh middleware ResolveWorkspace,
 * jadi tidak bisa dipalsukan dari sisi browser.
 */
class Workspace
{
    public const LAB = 'LAB';
    public const NON_LAB = 'NON_LAB';
    public const ALL = 'ALL';

    /** Workspace aktif, atau null jika tidak ada konteks web (console, queue, halaman publik). */
    public static function current(): ?string
    {
        if (app()->runningInConsole()) {
            return null;
        }

        $ws = session('workspace');

        return in_array($ws, [self::LAB, self::NON_LAB], true) ? $ws : null;
    }

    public static function isLab(): bool
    {
        return self::current() === self::LAB;
    }

    public static function isNonLab(): bool
    {
        return self::current() === self::NON_LAB;
    }

    public static function label(?string $ws): string
    {
        return match ($ws) {
            self::NON_LAB => 'Non-Lab',
            self::ALL => 'Lab & Non-Lab',
            default => 'Lab',
        };
    }
}
