<?php
/**
 * @package Modules\Invoice\Enums
 */

namespace Modules\Invoice\Enums;

enum InvoiceStatusEnum: string
{
    case PAID          = 'paid';
    case DRAFT         = 'draft';
    case OPEN          = 'open';
    case UNCOLLECTIBLE = 'uncollectible';
    case VOID          = 'void';

    public function color(): string
    {
        return match ($this) {
            self::PAID          => 'success',
            self::DRAFT         => 'primary',
            self::OPEN          => 'warning',
            self::UNCOLLECTIBLE => 'danger',
            self::VOID          => 'gray',
        };
    }
}
