<?php

namespace App\Notifications;

use App\Models\Donation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DonationStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    protected $donation;

    /**
     * Create a new notification instance.
     *
     * @param Donation $donation
     * @return void
     */
    public function __construct(Donation $donation)
    {
        $this->donation = $donation;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $formattedAmount = 'Rp ' . number_format($this->donation->amount, 0, ',', '.');
        $donationType = $this->donation->type === 'limit_increase' ? 'Limit Increase' : 'Premium Upgrade';
        
        $mail = (new MailMessage)
            ->subject('Donation Status Update - ImageConverter')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('We are writing to inform you about your recent donation:')
            ->line('Donation ID: #' . $this->donation->id)
            ->line('Amount: ' . $formattedAmount)
            ->line('Type: ' . $donationType);

        if ($this->donation->status === 'approved') {
            $mail->line('Your donation has been approved! Thank you for your support.');
            
            if ($this->donation->type === 'limit_increase') {
                $limitAmount = $notifiable->getLimitIncreaseAmount();
                $mail->line('You have received ' . $limitAmount . ' additional image conversions to your account.');
                $mail->action('View Your Conversions', url('/dashboard'));
            } else {
                $mail->line('Your account has been upgraded to Premium status!');
                $mail->line('You now have access to all premium features of ImageConverter.');
                $mail->action('Explore Premium Features', url('/dashboard'));
            }
        } else {
            $mail->line('Unfortunately, your donation could not be processed and has been rejected.')
                ->line('Reason: ' . $this->donation->admin_notes)
                ->line('If you have any questions about this decision, please contact our support team.')
                ->action('Try Again', url('/donations/create'));
        }

        return $mail->line('Thank you for using ImageConverter!');
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
            'donation_id' => $this->donation->id,
            'status' => $this->donation->status,
            'amount' => $this->donation->amount,
            'type' => $this->donation->type,
        ];
    }
} 