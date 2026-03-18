<?php

namespace Tests\Unit\Models;

use App\Models\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function test_is_customer_returns_true_for_customer_role(): void
    {
        $user = new User([
            'role' => 'customer',
        ]);

        $this->assertTrue($user->isCustomer());
        $this->assertFalse($user->isEmployee());
        $this->assertFalse($user->isManager());
    }

    public function test_is_employee_returns_true_for_employee_role(): void
    {
        $user = new User([
            'role' => 'employee',
        ]);

        $this->assertTrue($user->isEmployee());
        $this->assertFalse($user->isCustomer());
        $this->assertFalse($user->isManager());
    }

    public function test_is_manager_returns_true_for_manager_role(): void
    {
        $user = new User([
            'role' => 'manager',
        ]);

        $this->assertTrue($user->isManager());
        $this->assertFalse($user->isCustomer());
        $this->assertFalse($user->isEmployee());
    }
}