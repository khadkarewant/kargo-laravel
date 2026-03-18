<?php

namespace Tests\Feature;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationReadBehaviorTest extends TestCase
{
    use RefreshDatabase;

    public function test_marking_an_already_read_notification_keeps_it_read(): void
    {
        $user = User::factory()->create();

        $notification = Notification::factory()->read()->create([
            'user_id' => $user->id,
        ]);

        $originalReadAt = $notification->read_at;

        $response = $this
            ->actingAs($user)
            ->patch(route('notifications.read', $notification));

        $response->assertSessionHasNoErrors();

        $notification->refresh();

        $this->assertNotNull($notification->read_at);
        $this->assertTrue($notification->read_at->equalTo($originalReadAt));
    }

    public function test_owner_can_still_view_notifications_after_marking_one_as_read(): void
    {
        $user = User::factory()->create();

        $notification = Notification::factory()->unread()->create([
            'user_id' => $user->id,
            'title' => 'Read Behavior Notification',
        ]);

        $this->actingAs($user)
            ->patch(route('notifications.read', $notification))
            ->assertSessionHasNoErrors();

        $this->actingAs($user)
            ->get(route('notifications.index'))
            ->assertOk()
            ->assertSee('Read Behavior Notification');
    }
}