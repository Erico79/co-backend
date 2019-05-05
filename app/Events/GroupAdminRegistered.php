<?php

namespace App\Events;

use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class GroupAdminRegistered
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $otp;

  /**
   * Create a new event instance.
   *
   * @param User $user
   * @param $otp
   */
    public function __construct(User $user, $otp)
    {
      $this->user = $user;
      $this->otp = $otp;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
      return new PrivateChannel('channel-name');
    }
}
