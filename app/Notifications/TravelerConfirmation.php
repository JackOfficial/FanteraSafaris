<?php

namespace App\Notifications;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TravelerConfirmation extends Notification
{
    use Queueable;

    protected $inquiry;

    public function __construct(ContactMessage $inquiry)
    {
        $this->inquiry = $inquiry;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('We’ve Received Your Safari Inquiry - Fantera Safaris')
            ->greeting('Hi ' . $this->inquiry->name . '!')
            ->line('Thank you for reaching out to Fantera Safaris.')
            ->line('We have received your message regarding: "' . ($this->inquiry->subject ?? 'General Inquiry') . '"')
            ->line('One of our safari experts is reviewing your request and will get back to you within 24 hours to help plan your perfect adventure.')
            ->line('In the meantime, feel free to browse our latest tour packages on our website.')
            ->action('Explore Safari Packages', url('/posts'))
            ->line('Adventure awaits!')
            ->salutation('Best Regards, The Fantera Safaris Team');
    }
}