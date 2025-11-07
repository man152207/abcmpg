<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdReceipt extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     * ['Pending', 'Paused', 'FPY Received', 'eSewa Received', 'Baki', 'Paid', 'Refunded', 'Cancelled', 'Overpaid', 'PV Adjusted']
     */
    public $ad;
    public function __construct($ad)
    {
        $this->ad = $ad;
    }


    public function build()
    {
        return $this->view('email.ad-receipt')
            ->with(['ad' => $this->ad])
            ->subject('Ad Receipt');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        // return new Envelope(
        //     subject: 'Ad Receipt',
        // );
        if ($this->ad->Payment == "Paused") {
            return new Envelope(
                subject: 'Ad Campaign Paused',
            );
        }
        if ($this->ad->Payment == "Baki") {
            return new Envelope(
                subject: 'Partial Payment Received',
            );
        }
        if ($this->ad->Payment == "Refunded") {
            return new Envelope(
                subject: 'Refund Payment Initiated',
            );
        }
        if ($this->ad->Payment == "Cancelled") {
            return new Envelope(
                subject: 'Order Cancelled',
            );
        }
        if ($this->ad->Payment == "Overpaid") {
            return new Envelope(
                subject: 'Notice of Overpayment',
            );
        }
        if ($this->ad->Payment == "PV Adjusted") {
            return new Envelope(
                subject: 'Ad Campaign Applied',
            );
        }
        if ($this->ad->Payment == "Pending") {
            return new Envelope(
                subject: 'Ad Campaign Applied',
            );
        }
        if ($this->ad->Payment == "FPY Received") {
            return new Envelope(
                subject: 'Payment Received',
            );
        }
        if ($this->ad->Payment == "eSewa Received") {
            return new Envelope(
                subject: 'Payment Received',
            );
        }
        if ($this->ad->Payment == "Informed") {
            return new Envelope(
                subject: 'Payment Reminder',
            );
        }
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        if ($this->ad->Payment == "Paused") {
            return new Content(
                view: 'email.paused',
            );
        }
        if ($this->ad->Payment == "Baki") {
            return new Content(
                view: 'email.baki',
            );
        }
        if ($this->ad->Payment == "Refunded") {
            return new Content(
                view: 'email.refund',
            );
        }
        if ($this->ad->Payment == "Cancelled") {
            return new Content(
                view: 'email.cancelled',
            );
        }
        if ($this->ad->Payment == "Overpaid") {
            return new Content(
                view: 'email.overpaid',
            );
        }
        if ($this->ad->Payment == "PV Adjusted") {
            return new Content(
                view: 'email.pv_adjusted',
            );
        }
        if ($this->ad->Payment == "FPY Received") {
            return new Content(
                view: 'email.received',
            );
        }
        if ($this->ad->Payment == "eSewa Received") {
            return new Content(
                view: 'email.received',
            );
        }
        if ($this->ad->Payment == "Pending") {
            return new Content(
                view: 'email.ad-receipt',
            );
        }
        if ($this->ad->Payment == "Informed") {
            return new content(
                view: 'email.informed'
            );
        }
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
