<?php
declare(strict_types=1);

namespace Modules\Booking\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

/**
 * Class CreditPaymentPlaceStart
 *
 * @package Modules\Booking\Listeners
 */
class CreditPaymentPlaceStart
{
    // Если нужно обрабатывать асинхронно, реализуй ShouldQueue
    // implements ShouldQueue
    public function handle(array $data): void
    {
        $payment = $data['payment'];

        // логика отправки письма, уведомления, логирования и т.д.
//        logger()->info('Order placed, confirmation sent.', ['order_id' => $order->id]);
    }
}
