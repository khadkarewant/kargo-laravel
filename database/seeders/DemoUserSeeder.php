<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DemoUserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->demoManager('Kargo Manager', 'manager@kargo.test')->create();

        User::factory()->demoEmployee('Aarav Shrestha', 'aarav.employee@kargo.test')->create();
        User::factory()->demoEmployee('Sanjana Karki', 'sanjana.employee@kargo.test')->create();
        User::factory()->demoEmployee('Ritesh Lama', 'ritesh.employee@kargo.test')->create();

        User::factory()->demoCustomer('Nabin Traders', 'nabin.customer@kargo.test')->create();
        User::factory()->demoCustomer('Everest Imports', 'everest.customer@kargo.test')->create();
        User::factory()->demoCustomer('Himal Suppliers', 'himal.customer@kargo.test')->create();
        User::factory()->demoCustomer('City Cargo Client', 'city.customer@kargo.test')->create();

        User::factory()->customer()->count(6)->create();
    }
}