<?php

namespace Database\Factories;

use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ServiceRequest>
 */
class ServiceRequestFactory extends Factory
{
    protected $model = ServiceRequest::class;

    public function definition(): array
    {
        $serviceType = fake()->randomElement(ServiceRequest::SERVICE_TYPES);

        $cargoPresets = [
            ['quantity' => '12 cartons', 'product_detail' => 'Garments and textile samples', 'weight' => '48 kg', 'dimension' => '120x80x60 cm'],
            ['quantity' => '8 boxes', 'product_detail' => 'Mobile accessories and chargers', 'weight' => '22 kg', 'dimension' => '60x45x40 cm'],
            ['quantity' => '3 crates', 'product_detail' => 'Auto spare parts', 'weight' => '110 kg', 'dimension' => '140x100x90 cm'],
            ['quantity' => '15 packages', 'product_detail' => 'Household goods and kitchen items', 'weight' => '75 kg', 'dimension' => '130x85x70 cm'],
            ['quantity' => '6 boxes', 'product_detail' => 'Documents and business materials', 'weight' => '9 kg', 'dimension' => '40x30x25 cm'],
            ['quantity' => '10 bundles', 'product_detail' => 'Electrical fittings and tools', 'weight' => '58 kg', 'dimension' => '95x70x55 cm'],
        ];

        $cargo = fake()->randomElement($cargoPresets);

        return [
            'user_id' => User::factory()->customer(),
            'service_type' => $serviceType,
            'sender_name' => fake()->name(),
            'sender_country' => fake()->randomElement([
                'Nepal',
                'UAE',
                'Qatar',
                'Saudi Arabia',
                'Malaysia',
                'India',
            ]),
            'sender_contact' => fake()->numerify('98########'),
            'receiver_name' => fake()->name(),
            'receiver_country' => fake()->randomElement([
                'Nepal',
                'UAE',
                'Qatar',
                'Saudi Arabia',
                'Malaysia',
                'India',
            ]),
            'receiver_contact' => fake()->numerify('98########'),
            'notes' => fake()->randomElement([
                'Handle with care and confirm upon arrival.',
                'Customer requested standard processing.',
                'Urgent business shipment with document verification.',
                'Please notify on warehouse arrival.',
                'Fragile items inside, avoid rough handling.',
            ]),
            'quantity' => null,
            'product_detail' => null,
            'weight' => null,
            'dimension' => null,
            'employee_note' => null,
            'manager_note' => null,
            'status' => ServiceRequest::STATUS_REQUEST,
            'tracking_status' => null,
            'processed_by' => null,
            'processed_at' => null,
            'is_trashed' => false,
            'trashed_at' => null,
            'trashed_by' => null,
            'trash_reason' => null,
        ];
    }

    public function pending(): static
    {
        $cargo = $this->realisticCargo();

        return $this->state(fn () => [
            'status' => ServiceRequest::STATUS_PENDING,
            'quantity' => $cargo['quantity'],
            'product_detail' => $cargo['product_detail'],
            'weight' => $cargo['weight'],
            'dimension' => $cargo['dimension'],
            'employee_note' => 'Processing started and shipment details verified.',
            'processed_by' => User::factory()->employee(),
        ]);
    }

    public function completed(): static
    {
        $cargo = $this->realisticCargo();

        return $this->state(fn () => [
            'status' => ServiceRequest::STATUS_COMPLETED,
            'quantity' => $cargo['quantity'],
            'product_detail' => $cargo['product_detail'],
            'weight' => $cargo['weight'],
            'dimension' => $cargo['dimension'],
            'employee_note' => 'Shipment processed and ready for manager review.',
            'processed_by' => User::factory()->employee(),
            'processed_at' => now(),
        ]);
    }

    public function approved(): static
    {
        $cargo = $this->realisticCargo();

        return $this->state(fn () => [
            'status' => ServiceRequest::STATUS_APPROVED,
            'quantity' => $cargo['quantity'],
            'product_detail' => $cargo['product_detail'],
            'weight' => $cargo['weight'],
            'dimension' => $cargo['dimension'],
            'employee_note' => 'Shipment processed and approved for movement.',
            'processed_by' => User::factory()->employee(),
            'processed_at' => now(),
        ]);
    }

    public function revisionRequired(): static
    {
        $cargo = $this->realisticCargo();

        return $this->state(fn () => [
            'status' => ServiceRequest::STATUS_REVISION_REQUIRED,
            'quantity' => $cargo['quantity'],
            'product_detail' => $cargo['product_detail'],
            'weight' => $cargo['weight'],
            'dimension' => $cargo['dimension'],
            'employee_note' => 'Initial processing completed, awaiting correction.',
            'manager_note' => 'Please correct shipment detail mismatch before approval.',
            'processed_by' => User::factory()->employee(),
            'processed_at' => now(),
        ]);
    }

    public function trashed(): static
    {
        return $this->state(fn () => [
            'is_trashed' => true,
            'trashed_at' => now(),
            'trashed_by' => User::factory()->manager(),
            'trash_reason' => fake()->randomElement([
                'Duplicate submission detected.',
                'Customer entered invalid receiver details.',
                'Request created in error and marked inactive.',
            ]),
        ]);
    }

    public function import(): static
    {
        return $this->state(fn () => [
            'service_type' => ServiceRequest::SERVICE_IMPORT,
        ]);
    }

    public function export(): static
    {
        return $this->state(fn () => [
            'service_type' => ServiceRequest::SERVICE_EXPORT,
        ]);
    }

    public function clearance(): static
    {
        return $this->state(fn () => [
            'service_type' => ServiceRequest::SERVICE_CLEARANCE,
        ]);
    }

    public function courier(): static
    {
        return $this->state(fn () => [
            'service_type' => ServiceRequest::SERVICE_COURIER,
        ]);
    }

    protected function realisticCargo(): array
    {
        return fake()->randomElement([
            ['quantity' => '12 cartons', 'product_detail' => 'Garments and textile samples', 'weight' => '48 kg', 'dimension' => '120x80x60 cm'],
            ['quantity' => '8 boxes', 'product_detail' => 'Mobile accessories and chargers', 'weight' => '22 kg', 'dimension' => '60x45x40 cm'],
            ['quantity' => '3 crates', 'product_detail' => 'Auto spare parts', 'weight' => '110 kg', 'dimension' => '140x100x90 cm'],
            ['quantity' => '15 packages', 'product_detail' => 'Household goods and kitchen items', 'weight' => '75 kg', 'dimension' => '130x85x70 cm'],
            ['quantity' => '6 boxes', 'product_detail' => 'Documents and business materials', 'weight' => '9 kg', 'dimension' => '40x30x25 cm'],
            ['quantity' => '10 bundles', 'product_detail' => 'Electrical fittings and tools', 'weight' => '58 kg', 'dimension' => '95x70x55 cm'],
        ]);
    }
}