<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StoryContentUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $storyId;
    public $content;
    public $userId;

    /**
     * Create a new event instance.
     */
    public function __construct($storyId, $content, $userId)
    {
        $this->storyId = $storyId;
        $this->content = $content;
        $this->userId = $userId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PresenceChannel('stories.' . $this->storyId),
        ];
    }

    /**
     * Nama event yang akan disiarkan di sisi frontend.
     */
    public function broadcastAs(): string
    {
        return 'StoryContentUpdated';
    }
}
