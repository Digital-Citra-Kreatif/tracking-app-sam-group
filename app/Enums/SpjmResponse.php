<?php

/**
 * File: app/Enums/SpjmResponse.php
 * Responsibility: The second customs response that closes the SPJM branch.
 * What it does:
 * - SPPB, Lab or Other, recorded at "Waiting change status SPJM to SPPB";
 *   Other is described in the free-text `spjm_response_other` column.
 * How to use: ImportShipment casts `spjm_response` to this enum (nullable).
 * How to extend: Add new response codes as cases plus a label.
 */

namespace App\Enums;

use App\Enums\Concerns\HasSelectOptions;

enum SpjmResponse: string
{
    use HasSelectOptions;

    case Sppb = 'SPPB';
    case Lab = 'LAB';
    case Other = 'OTHER';

    public function label(): string
    {
        return match ($this) {
            self::Sppb => 'SPPB',
            self::Lab => 'Lab',
            self::Other => 'Other',
        };
    }
}
