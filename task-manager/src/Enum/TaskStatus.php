<?php

namespace App\Enum;

use Symfony\Contracts\Translation\TranslatorInterface;

enum TaskStatus: string
{
    case PENDING = 'pending';
    case DONE = 'completed';
    case REJECTED = 'rejected';

    /**
     * Sprawdza, czy podany status jest poprawnym statusem zadania.
     * To metoda jest przydatna do walidacji statusów przed ich użyciem.
     * @param string $status
     * @return bool
     */
    public static function isValidStatus(string $status): bool
    {
        return in_array($status, array_map(fn($case) => $case->value, self::cases()));
    }
    public static function getAvailableStatuses(TranslatorInterface $translator): array
    {
        return [
            self::PENDING->value => $translator->trans('status.pending'),
            self::DONE->value => $translator->trans('status.completed'),
            self::REJECTED->value => $translator->trans('status.rejected'),
        ];
    }
}