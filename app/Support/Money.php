<?php

namespace App\Support;

use InvalidArgumentException;

class Money
{
    public static function toCents(string|int|float $amount): int
    {
        $value = trim((string) $amount);

        if (! preg_match('/^(\d+)(?:\.(\d{1,2}))?$/', $value, $matches)) {
            throw new InvalidArgumentException('El importe no tiene un formato monetario valido.');
        }

        $whole = (int) $matches[1];
        $fraction = str_pad($matches[2] ?? '', 2, '0');

        return ($whole * 100) + (int) $fraction;
    }

    public static function fromCents(int $cents): string
    {
        $whole = intdiv($cents, 100);
        $fraction = str_pad((string) ($cents % 100), 2, '0', STR_PAD_LEFT);

        return $whole.'.'.$fraction;
    }

    public static function format(int $cents): string
    {
        $whole = intdiv($cents, 100);
        $fraction = str_pad((string) ($cents % 100), 2, '0', STR_PAD_LEFT);

        return '$'.number_format($whole).'.'.$fraction.' MXN';
    }
}
