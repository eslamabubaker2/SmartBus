<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Son;
use Carbon\Carbon;

class AcceptanceNotifiction extends Notification
{
    use Queueable;
    protected $son;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Son $son)
    {
        $this->son = $son;

    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toDatabase($notifiable)
    {
        return [
           
            'title' => Carbon::now()->toDayDateTimeString(),
            'message_ar' => ' تم قبول الطالب '. ' ' .$this->son->name,
            'message_en' => 'Student'. ' ' .$this->son->name . ' ' .'was accepted',
            'sender_id' => $this->son->school->name,


        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
