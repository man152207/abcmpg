<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Notifications\GmailReceivedEmailNotification;
use Illuminate\Support\Facades\Notification;

// Inside your controller or where you receive Gmail emails
$emailSubject = "Your Gmail Email Subject";
$emailSnippet = "A brief preview of your Gmail email";
$emailUrl = "URL to view the full Gmail email";

// Notify a notifiable entity (e.g., user) with the GmailReceivedEmailNotification
Notification::send($notifiable, new GmailReceivedEmailNotification($emailSubject, $emailSnippet, $emailUrl));

class GmailReceivedEmailNotification extends Notification
{
    use Queueable;

    /**
     * The email subject.
     *
     * @var string
     */
    public $emailSubject;

    /**
     * The email snippet (a brief preview of the email).
     *
     * @var string
     */
    public $emailSnippet;

    /**
     * The URL to view the full email.
     *
     * @var string
     */
    public $emailUrl;

    /**
     * Create a new notification instance.
     *
     * @param string $emailSubject
     * @param string $emailSnippet
     * @param string $emailUrl
     */
    public function __construct($emailSubject, $emailSnippet, $emailUrl)
    {
        $this->emailSubject = $emailSubject;
        $this->emailSnippet = $emailSnippet;
        $this->emailUrl = $emailUrl;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('You have received a new Gmail email:')
            ->line($this->emailSubject)
            ->line($this->emailSnippet)
            ->action('Read Email', $this->emailUrl);
    }
}
