<?php

namespace App\Notifications;

use App\Models\ContactMessage; // Import the model
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InquiryReply extends Notification
{
    use Queueable;

    protected $inquiry; // Define the property

    /**
     * Create a new notification instance.
     */
    public function __construct(ContactMessage $inquiry)
    {
        $this->inquiry = $inquiry; // Assign the data
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Re: ' . ($this->inquiry->subject ?? 'Your Safari Inquiry'))
            ->greeting('Hello ' . $this->inquiry->name . ',')
            ->line('Thank you for your patience. Regarding your inquiry:')
            ->line('"' . $this->inquiry->reply_message . '"') // This now has data!
            ->action('Visit Fantera Safaris', url('/'))
            ->line('If you have more questions, just reply to this email!')
            ->salutation('Best Regards, Fantera Safaris Team');
    }
}