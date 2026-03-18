<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoNotificationSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $serviceRequests = ServiceRequest::all();

        if ($users->isEmpty() || $serviceRequests->isEmpty()) {
            return;
        }

        foreach ($users as $user) {
            Notification::factory()
                ->count(rand(2, 4))
                ->unread()
                ->create([
                    'user_id' => $user->id,
                    'service_request_id' => $serviceRequests->random()->id,
                ]);

            Notification::factory()
                ->count(rand(2, 5))
                ->read()
                ->create([
                    'user_id' => $user->id,
                    'service_request_id' => $serviceRequests->random()->id,
                ]);
        }
    }
}