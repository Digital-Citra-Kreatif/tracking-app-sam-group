<?php

/**
 * File: database/migrations/2026_10_20_090000_replace_peb_npe_check_with_peb_response_on_export_shipments.php
 * Responsibility: Turns the export PEB & NPE check into the PEB response.
 * What it does:
 * - Renames `peb_npe_check_status` to `peb_response` (NPE / PPB (SPJM) /
 *   Other) and `peb_npe_check_note` to `peb_response_other`, the free text
 *   that describes an Other response.
 * - Maps saved outcomes: passed becomes NPE, not passed becomes Other so
 *   its note is kept as the description. Other notes stay in the column;
 *   the model clears them on the next save of that shipment.
 * How to use: `php artisan migrate` (or migrate:fresh --seed).
 * How to extend: nothing — the form and the portal timeline read the columns.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('export_shipments', function (Blueprint $table) {
            $table->renameColumn('peb_npe_check_status', 'peb_response');
            $table->renameColumn('peb_npe_check_note', 'peb_response_other');
        });

        DB::table('export_shipments')->where('peb_response', 'passed')->update(['peb_response' => 'NPE']);
        DB::table('export_shipments')->where('peb_response', 'not_passed')->update(['peb_response' => 'OTHER']);
    }

    public function down(): void
    {
        DB::table('export_shipments')->where('peb_response', 'NPE')->update(['peb_response' => 'passed']);
        DB::table('export_shipments')->whereIn('peb_response', ['PPB_SPJM', 'OTHER'])->update(['peb_response' => 'not_passed']);

        Schema::table('export_shipments', function (Blueprint $table) {
            $table->renameColumn('peb_response', 'peb_npe_check_status');
            $table->renameColumn('peb_response_other', 'peb_npe_check_note');
        });
    }
};
