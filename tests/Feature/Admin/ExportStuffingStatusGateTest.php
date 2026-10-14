<?php

/**
 * File: tests/Feature/Admin/ExportStuffingStatusGateTest.php
 * Responsibility: Guards the gated "Stuffing status at Factory" validation.
 * What it does:
 * - Proves a blank, locked stuffing status does not block saving the shipment.
 * - Proves the same blank value is rejected once Step 5 is reached.
 * How to use: `php artisan test --filter=ExportStuffingStatusGateTest`.
 * How to extend: add a pair of tests per field gated with requiredOnceUnlocked.
 */

namespace Tests\Feature\Admin;

use App\Enums\ExportMilestone;
use App\Filament\Resources\ExportShipments\Pages\EditExportShipment;
use App\Models\ExportShipment;
use App\Models\Role;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;
use Tests\TestCase;

class ExportStuffingStatusGateTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        Filament::setCurrentPanel(Filament::getPanel('admin'));

        $admin = User::factory()->create();
        $admin->assignRole(Role::ADMIN);
        $this->actingAs($admin);
    }

    private function saveWithBlankStuffingStatus(ExportMilestone $milestone): Testable
    {
        $shipment = ExportShipment::query()->where('bl_number', 'BL-EXP-0001')->firstOrFail();
        $shipment->update(['current_milestone' => $milestone]);

        $component = Livewire::test(EditExportShipment::class, ['record' => $shipment->getRouteKey()]);
        $containerKey = array_key_first($component->get('data.containers'));

        return $component
            ->set("data.containers.{$containerKey}.stuffing_status", null)
            ->call('save');
    }

    public function test_a_blank_stuffing_status_does_not_block_saving_while_it_is_locked(): void
    {
        $this->saveWithBlankStuffingStatus(ExportMilestone::PickupEmptyContainer)
            ->assertHasNoFormErrors();
    }

    public function test_a_blank_stuffing_status_is_required_once_step_5_is_reached(): void
    {
        $this->saveWithBlankStuffingStatus(ExportMilestone::StuffingPebNpe)
            ->assertHasFormErrors();
    }
}
