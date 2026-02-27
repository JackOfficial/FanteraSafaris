<?php

namespace App\Notifications;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewSafariInquiry extends Notification
{
    use Queueable;

    protected $messageData;

    public function __construct(ContactMessage $messageData)
    {
        $this->messageData = $messageData;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Safari Inquiry: ' . ($this->messageData->subject ?? 'General Inquiry'))
            ->greeting('Hello, Fantera Safaris!')
            ->line('You have received a new inquiry from ' . $this->messageData->name)
            ->line('Email: ' . $this->messageData->email)
            ->line('Message Preview: "' . str($this->messageData->message)->limit(100) . '"')
            ->action('View Full Inquiry', route('admin.messages.show', $this->messageData))
            ->line('Respond quickly to secure this booking!');
    }
}