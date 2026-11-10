<?php

namespace App\Enums;

enum ProjectStatus: string
{
    case New = 'new';
    case Doing = 'doing';
    case Review = 'review';
    case Done = 'done';

    public function label(): string
    {
        return match ($this) {
            self::New => 'جديد',
            self::Doing => 'قيد التنفيذ',
            self::Review => 'مراجعة العميل',
            self::Done => 'مكتمل',
        };
    }

    public function pillClass(): string
    {
        return match ($this) {
            self::New => 'p-grey',
            self::Doing => 'p-blue',
            self::Review => 'p-amber',
            self::Done => 'p-green',
        };
    }
}
