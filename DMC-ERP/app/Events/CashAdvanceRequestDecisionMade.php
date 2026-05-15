<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CashAdvanceRequestDecisionMade implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $requestId;
    public $decision;
    public $requestData;
    public $decidedBy;

    /**
     * Create a new event instance.
     */
    public function __construct($requestId, $decision, $requestData, $decidedBy = null)
    {
        $this->requestId = $requestId;
        $this->decision = $decision;
        $this->requestData = $requestData;
        $this->decidedBy = $decidedBy;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('cash-advance-decisions'),
        ];
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'request_id' => $this->requestId,
            'decision' => $this->decision,
            'request' => $this->requestData,
            'decided_by' => $this->decidedBy,
            'timestamp' => now()->toIso8601String(),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'cash-advance-decision-made';
    }
}
