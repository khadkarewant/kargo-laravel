<?php

namespace Database\Seeders;

use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoServiceRequestSeeder extends Seeder
{
    public function run(): void
    {
        $customers = User::where('role', 'customer')->get();
        $employees = User::where('role', 'employee')->get();
        $manager = User::where('role', 'manager')->first();

        if ($customers->isEmpty() || $employees->isEmpty() || ! $manager) {
            return;
        }

        foreach ($customers as $customer) {
            ServiceRequest::factory()->create([
                'user_id' => $customer->id,
            ]);

            ServiceRequest::factory()->pending()->create([
                'user_id' => $customer->id,
                'processed_by' => $employees->random()->id,
            ]);

            ServiceRequest::factory()->completed()->create([
                'user_id' => $customer->id,
                'processed_by' => $employees->random()->id,
            ]);

            ServiceRequest::factory()->approved()->create([
                'user_id' => $customer->id,
                'processed_by' => $employees->random()->id,
            ]);

            ServiceRequest::factory()->revisionRequired()->create([
                'user_id' => $customer->id,
                'processed_by' => $employees->random()->id,
            ]);
        }

        for ($i = 0; $i < 4; $i++) {
            ServiceRequest::factory()->approved()->create([
                'user_id' => $customers->random()->id,
                'processed_by' => $employees->random()->id,
            ]);
        }

        for ($i = 0; $i < 2; $i++) {
            ServiceRequest::factory()->trashed()->create([
                'user_id' => $customers->random()->id,
                'processed_by' => $employees->random()->id,
                'trashed_by' => $manager->id,
            ]);
        }
    }
}