<?php
declare(strict_types=1);

namespace Modules\Invoice\Console\Commands;

use Illuminate\Console\Command;

use Laravel\Cashier\Invoice;
use Modules\User\Models\User;
use Modules\Order\Models\Order;
use Modules\Invoice\Models\Invoice as LocalInvoice;

/**
 * Class SyncStripeInvoices
 *
 * @package Modules\Invoice\Console\Commands
 */
class SyncStripeInvoices extends Command
{
    protected $signature = 'payment:sync-stripe-invoices {--email= : The email of the user(optional)}';

    protected $description = 'Fetch and store Stripe invoices for each user';

    public function handle(): int
    {
        $email = $this->option('email');
        if ($email) {
            $user = User::where('email', $email)->first();

            if (! $user) {
                $this->error("User with email {$email} not found.");
                return self::FAILURE;
            }
            if (! $user->stripe_id) {
                $this->error("User with email {$email} has empty stripe ID.");
                return self::FAILURE;
            }

            $this->info("Syncing invoices for user: {$user->email}");
            $this->fetchUserInvoice($user);
        } else {
            $users = User::whereNotNull('stripe_id')->get();

            foreach ($users as $user) {
                $this->info("Fetching invoices for user: {$user->email}");
                $this->fetchUserInvoice($user);
            }

            $this->info('All invoices synced.');
        }

        return self::SUCCESS;
    }

    private function fetchUserInvoice($user): void
    {
        $stripeInvoices = $user->invoices();

        foreach ($stripeInvoices as $invoiceData) {
            /** @var Invoice $invoiceData */
            $invoiceId = $invoiceData->id;

            if (LocalInvoice::where('stripe_id', $invoiceId)->exists()) {
                continue;
            }

            $order = Order::where('user_id', $user->id)
                ->where('status', '!=', 'cancelled')
                ->latest()
                ->first();

            LocalInvoice::create([
                'order_id'          => $order?->id,
                'user_id'           => $user->id,
                'stripe_id'         => $invoiceId,
                'amount_due'        => $this->formatNumber($invoiceData->asStripeInvoice()->amount_due),
                'due_date'          => $invoiceData->asStripeInvoice()->due_date ? now()->timestamp($invoiceData->asStripeInvoice()->due_date) : null,
                'is_paid'           => $invoiceData->isPaid(),
                'currency'          => $invoiceData->asStripeInvoice()->currency ?? null,
                'hosted_url'        => $invoiceData->asStripeInvoice()->hosted_invoice_url ?? null,
                'pdf_url'           => $invoiceData->asStripeInvoice()->invoice_pdf ?? null,
                'increment_id'      => $invoiceData->asStripeInvoice()->number ?? null,
                'status'            => $invoiceData->asStripeInvoice()->status,
                'subtotal'          => $this->formatNumber($invoiceData->asStripeInvoice()->subtotal),
                'total'             => $this->formatNumber($invoiceData->asStripeInvoice()->total),
                'tax'               => $this->formatNumber($invoiceData->asStripeInvoice()->tax),
                'total_excl_tax'    => $this->formatNumber($invoiceData->asStripeInvoice()->total_excluding_tax),
            ]);

            $this->info("Stored invoice: $invoiceId");
        }
    }

    private function formatNumber($number)
    {
        if ($number) {
            return number_format($number / 100, 2);
        }

        return 0;
    }
}
