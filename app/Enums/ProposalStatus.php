<?php

namespace App\Enums;

enum ProposalStatus: string
{
    case Draft = 'draft';
    case Sent = 'sent';
    case Accepted = 'accepted';
    case Rejected = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'مسودة',
            self::Sent => 'بانتظار الرد',
            self::Accepted => 'مقبول',
            self::Rejected => 'مرفوض',
        };
    }

    public function pillClass(): string
    {
        return match ($this) {
            self::Draft => 'p-grey',
            self::Sent => 'p-amber',
            self::Accepted => 'p-green',
            self::Rejected => 'p-red',
        };
    }
}
