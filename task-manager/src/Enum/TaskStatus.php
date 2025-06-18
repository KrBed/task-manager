<?php

namespace App\Enum;

enum TaskStatus: string
{
    case PENDING = 'pending';
    case DONE = 'completed';
    case REJECTED = 'rejected';
    public static function isValidStatus(string $status): bool
    {
        return in_array($status, array_map(fn($case) => $case->value, self::cases()));
    }
    public static function getAvailableStatuses(): array
    {
        return [
            self::PENDING->value => 'Oczekujące',
            self::DONE->value => 'Zakończone',
            self::REJECTED->value => 'Odrzucone',
        ];
    }
}