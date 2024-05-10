<?php

namespace App\Notifications;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Slack\BlockKit\Blocks\ActionsBlock;
use Illuminate\Notifications\Slack\SlackMessage;
use Illuminate\Notifications\Slack\BlockKit\Blocks\ContextBlock;
use Illuminate\Notifications\Slack\BlockKit\Blocks\SectionBlock;

class SendMailOrder extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected Order $order, protected $cost)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'slack'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('You have just created an order.')
            ->line('Please pay in advance ' . Carbon::now()->addDay(1)->format(config('define.date_format')))
            ->line('The content of the transfer is payment for order number ' . $this->order->id)
            ->line('Deposit amount is ' . formatCurrency($this->cost))
            ->line('Otherwise we will cancel your order.')
            ->action('View Order Details', url(config('define.fe.url_order') . $this->order->id));
    }

    public function toSlack()
    {
        $orderUrl = config('define.fe.url') . "/admin/orders/{$this->order->id}";
        return (new SlackMessage)
            ->to(config('define.slack.channels.order'))
            ->text('Has one order created')
            ->headerBlock("[Order number #{$this->order->id}]")
            ->actionsBlock(function (ActionsBlock $block) use ($orderUrl) {
                $block->button('View Order Details')->url($orderUrl);
            })
            ->contextBlock(function (ContextBlock $block) {
                $block->text("Customer {$this->order->user_id}");
            })
            ->sectionBlock(function (SectionBlock $block) {
                $block->text("Date rent: {$this->order->start_date} to {$this->order->end_date}");
                foreach ($this->order->orderDetails as $detail) {
                    $block->field("Moto: {$detail->moto->name}")->markdown();
                    $block->field("Price: {$detail->price}")->markdown();
                }
            });
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
