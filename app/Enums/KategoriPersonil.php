<?php


namespace App\Enums;

enum KategoriPersonil: string
{
    case CHEMIST = 'chemist';
    case ANALIST = 'analist';
    case PREPARATOR = 'preparator';
    case SAMPLER = 'sampler';

    public function label(): string
    {
        return match ($this) {
            self::CHEMIST => 'Chemist',
            self::ANALIST => 'Analist',
            self::PREPARATOR => 'Preparator',
            self::SAMPLER => 'Sampler',
        };
    }

    /**
     * Dipakai untuk isi <select> di form maupun dropdown filter.
     */
    public static function options(): array
    {
        return [
            self::CHEMIST->value => self::CHEMIST->label(),
            self::ANALIST->value => self::ANALIST->label(),
            self::PREPARATOR->value => self::PREPARATOR->label(),
            self::SAMPLER->value => self::SAMPLER->label(),
        ];
    }
}