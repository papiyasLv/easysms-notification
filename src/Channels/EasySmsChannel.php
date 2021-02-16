<?php


namespace Papiyas\Notifications\EasySms\Channels;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Notifications\Notification;

class EasySmsChannel
{
    /**
     * Send the given notification.
     *
     * @param $notifiable
     * @param Notification $notification
     */
    public function send($notifiable, Notification $notification)
    {
        if ($notifiable instanceof Model) {
            $to = $notifiable->sendNotificationViaEasySms($notification);
        } elseif ($notifiable instanceof AnonymousNotifiable) {
            $to = $notifiable->routes[__CLASS__];
        }

        $message = $notification->toEasySms($notifiable);

        app()->make('easysms')->safeSend($to, $message);
    }
}
