<?php

namespace Tests\Feature;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_their_notifications(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $ownNotification = Notification::factory()->create([
            'user_id' => $user->id,
            'title' => 'Own Notification',
        ]);

        $otherNotification = Notification::factory()->create([
            'user_id' => $otherUser->id,
            'title' => 'Other Notification',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('notifications.index'));

        $response->assertOk();
        $response->assertSee($ownNotification->title);
        $response->assertDontSee($otherNotification->title);
    }

    public function test_user_can_mark_their_own_notification_as_read(): void
    {
        $user = User::factory()->create();

        $notification = Notification::factory()->unread()->create([
            'user_id' => $user->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->patch(route('notifications.read', $notification));

        $response->assertSessionHasNoErrors();

        $this->assertDatabaseMissing('notifications', [
            'id' => $notification->id,
            'read_at' => null,
        ]);
    }

    public function test_user_cannot_mark_someone_elses_notification_as_read(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();

        $notification = Notification::factory()->unread()->create([
            'user_id' => $owner->id,
        ]);

        $response = $this
            ->actingAs($otherUser)
            ->patch(route('notifications.read', $notification));

        $response->assertForbidden();
    }
}