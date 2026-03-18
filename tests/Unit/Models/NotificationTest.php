<?php

namespace Tests\Unit\Models;

use App\Models\Notification;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    public function test_is_unread_returns_true_when_read_at_is_null(): void
    {
        $notification = new Notification([
            'read_at' => null,
        ]);

        $this->assertTrue($notification->isUnread());
        $this->assertFalse($notification->isRead());
    }

    public function test_is_read_returns_true_when_read_at_has_value(): void
    {
        $notification = new Notification([
            'read_at' => now(),
        ]);

        $this->assertTrue($notification->isRead());
        $this->assertFalse($notification->isUnread());
    }
}