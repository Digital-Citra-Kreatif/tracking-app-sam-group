<?php

/**
 * File: database/migrations/2026_10_16_090000_add_revision_fields_to_shipment_tables.php
 * Responsibility: Schema changes for the October 2026 process revision.
 * What it does:
 * - Export: `stuffing_date` becomes a date (no time); the Checking PEB & NPE
 *   step gets a passed / not passed outcome plus a note.
 * - Import: the SPJM branch gets its second response (SPPB / Lab / Other with
 *   a free-text value for Other); DO release gets the DO valid date and its
 *   extension; containers get the vehicle / truck number.
 * How to use: `php artisan migrate` (or migrate:fresh --seed).
 * How to extend: nothing — the forms and the portal timeline read the columns.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('export_shipments', function (Blueprint $table) {
            $table->date('stuffing_date')->nullable()->change();
            $table->string('peb_npe_check_status', 20)->nullable()->after('aju_number');
            $table->text('peb_npe_check_note')->nullable()->after('peb_npe_check_status');
        });

        Schema::table('import_shipments', function (Blueprint $table) {
            $table->date('do_valid_until')->nullable()->after('port_of_discharge');
            $table->date('do_extended_until')->nullable()->after('do_valid_until');
            $table->string('spjm_response', 20)->nullable()->after('billing_response');
            $table->string('spjm_response_other')->nullable()->after('spjm_response');
        });

        Schema::table('import_containers', function (Blueprint $table) {
            $table->string('vehicle_number', 100)->nullable()->after('license_number');
        });
    }

    public function down(): void
    {
        Schema::table('import_containers', function (Blueprint $table) {
            $table->dropColumn('vehicle_number');
        });

        Schema::table('import_shipments', function (Blueprint $table) {
            $table->dropColumn(['do_valid_until', 'do_extended_until', 'spjm_response', 'spjm_response_other']);
        });

        Schema::table('export_shipments', function (Blueprint $table) {
            $table->dropColumn(['peb_npe_check_status', 'peb_npe_check_note']);
            $table->timestamp('stuffing_date')->nullable()->change();
        });
    }
};
