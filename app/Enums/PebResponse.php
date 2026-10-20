<?php

/**
 * File: app/Enums/PebResponse.php
 * Responsibility: The customs response to the export PEB, recorded at
 * "Checking PEB & NPE".
 * What it does:
 * - NPE, PPB (SPJM) or Other; Other is described in the free-text
 *   `peb_response_other` column.
 * How to use: ExportShipment casts `peb_response` to this enum (nullable).
 * How to extend: Add new response codes as cases plus a label.
 */

namespace App\Enums;

use App\Enums\Concerns\HasSelectOptions;

enum PebResponse: string
{
    use HasSelectOptions;

    case Npe = 'NPE';
    case PpbSpjm = 'PPB_SPJM';
    case Other = 'OTHER';

    public function label(): string
    {
        return match ($this) {
            self::Npe => 'NPE',
            self::PpbSpjm => 'PPB (SPJM)',
            self::Other => 'Other',
        };
    }
}
