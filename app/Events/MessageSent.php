<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Notifications\Notification;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use NotificationChannels\OneSignal\OneSignalChannel;
use NotificationChannels\OneSignal\OneSignalMessage;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MessageSent extends Notification
{
    // use Dispatchable, InteractsWithSockets, SerializesModels;
    use Queueable;
    // use InteractsWithSockets, SerializesModels;
    public $message;
    public function __construct(private array $data)
    {
    }

    // public function broadcastOn()
    // {
    //     return new PrivateChannel('chat.' . $this->message->channel_id);
    // }
    // public function broadcastWith()
    // {
    //     return [
    //         'message' => $this->message->load('user')
    //     ];
    // }
    public function via()
    {
        return [OneSignalChannel::class];
    }

    public function toOneSignal()
    {
        $messageData = $this->data['messageData'];

        return OneSignalMessage::create()
            ->setSubject($messageData['senderName'] . " sent you a message.")
            ->setBody($messageData['message'])
            ->setData('data', $messageData);
    }
}
