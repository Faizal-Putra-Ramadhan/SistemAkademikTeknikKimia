<?php

declare(strict_types=1);

namespace App\Enums;

enum LabType: string
{
    case Penelitian = 'penelitian';
    case Pendidikan = 'pendidikan';

    public static function values(): array
    {
        return array_map(static fn (self $type) => $type->value, self::cases());
    }
}
