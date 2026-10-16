<?php

/**
 * File: app/Enums/PebNpeCheckStatus.php
 * Responsibility: The outcome of the export "Checking PEB & NPE" step.
 * What it does:
 * - Passed / Not passed; the free-text `peb_npe_check_note` explains it.
 * How to use: ExportShipment casts `peb_npe_check_status` to this enum (nullable).
 * How to extend: Add outcomes as cases plus a label and color.
 */

namespace App\Enums;

use App\Enums\Concerns\HasSelectOptions;

enum PebNpeCheckStatus: string
{
    use HasSelectOptions;

    case Passed = 'passed';
    case NotPassed = 'not_passed';

    public function label(): string
    {
        return match ($this) {
            self::Passed => 'Passed',
            self::NotPassed => 'Not passed',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Passed => 'success',
            self::NotPassed => 'danger',
        };
    }
}
