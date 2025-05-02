<?php
declare(strict_types=1);

namespace Modules\Invoice\Http\Controllers;

use Modules\Base\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Invoice\Models\Invoice;
use Modules\Order\Models\Order;

/**
 * Class StripeInvoiceWebhookController
 *
 * @package Modules\Invoice\Http\Controllers
 */
class StripeInvoiceWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->all();

        if ($payload['type'] === 'invoice.paid') {
            $invoiceData = $payload['data']['object'];

            $order = Order::whereHas('subscription', function ($q) use ($invoiceData) {
                $q->where('stripe_id', $invoiceData['subscription']);
            })->latest()->first();

            if ($order) {
                Invoice::updateOrCreate(
                    ['stripe_invoice_id' => $invoiceData['id']],
                    [
                        'order_id'    => $order->id,
                        'status'      => $invoiceData['status'],
                        'amount'      => $invoiceData['amount_paid'] / 100,
                        'currency'    => $invoiceData['currency'],
                        'hosted_url'  => $invoiceData['hosted_invoice_url'],
                        'pdf_url'     => $invoiceData['invoice_pdf'],
                    ]
                );
            }
        }

        return response()->json(['status' => 'success']);
    }
}
