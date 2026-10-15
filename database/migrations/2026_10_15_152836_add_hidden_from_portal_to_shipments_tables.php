<?php

/**
 * File: database/migrations/2026_10_15_152836_add_hidden_from_portal_to_shipments_tables.php
 * Responsibility: Adds the hidden_from_portal flag to both shipment tables.
 * What it does:
 * - Lets an admin keep a shipment out of the customer portal at any step.
 *   Unlike the draft status, milestone moves never change it.
 * How to use: `php artisan migrate` (or migrate:fresh --seed).
 * How to extend: nothing — the visibleInPortal scope reads the flag.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['export_shipments', 'import_shipments'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->boolean('hidden_from_portal')->default(false)->after('status');
            });
        }
    }

    public function down(): void
    {
        foreach (['export_shipments', 'import_shipments'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropColumn('hidden_from_portal');
            });
        }
    }
};
