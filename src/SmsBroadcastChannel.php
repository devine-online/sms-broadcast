<?php

namespace NotificationChannels\SmsBroadcast;

use DevineOnline\SmsBroadcast\Api\Client;
use Illuminate\Notifications\Notification;

class SmsBroadcastChannel
{
    /** @var Client */
    private $smsBroadcastClient;

    public function __construct(Client $smsBroadcastClient)
    {
        $this->smsBroadcastClient = $smsBroadcastClient;
    }

    public function send($notifiable, Notification $notification): void
    {
        if (! $to = $notifiable->routeNotificationFor('smsbroadcast')) {
            return;
        }

        $message = $notification->toSmsBroadcast($notifiable);

        if (is_string($message)) {
            $message = new SmsBroadcastMessage($message);
        }

        if (! $message instanceof SmsBroadcastMessage) {
            return;
        }

        $this->smsBroadcastClient->send(
            $to,
            $message->content,
            $message->sender,
            $message->reference,
            true,
            $message->delay
        );
    }
}
