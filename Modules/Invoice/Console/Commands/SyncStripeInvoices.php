<?php
declare(strict_types=1);

namespace Modules\Invoice\Console\Commands;

use Illuminate\Console\Command;

use Laravel\Cashier\Invoice;
use Modules\User\Models\User;
use Modules\Invoice\Models\Invoice as LocalInvoice;

/**
 * Class SyncStripeInvoices
 *
 * @package Modules\Invoice\Console\Commands
 */
class SyncStripeInvoices extends Command
{
    const CONSOLE_COMMAND_INVOICES_SYNC_STRIPE = 'stripe:pull-invoices';

    protected $signature = self::CONSOLE_COMMAND_INVOICES_SYNC_STRIPE . ' {--email= : The email of the user(optional)}';
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
            $subscriptionId = $invoiceData->subscription;

            if (LocalInvoice::where('stripe_id', $subscriptionId)->exists()) {
                continue;
            }
            $subscription = \Modules\Subscription\Models\Subscription::where('stripe_id', $subscriptionId)->first();

            if ($subscription && $subscription->order) {
                $order = $subscription->order;

                LocalInvoice::create([
                    'order_id'          => $order?->id,
                    'user_id'           => $user->id,
                    'stripe_id'         => $subscriptionId,
                    'amount_due'        => $this->formatNumber($invoiceData->asStripeInvoice()->amount_due),
                    'due_date'          => $invoiceData->asStripeInvoice()->due_date ? now()->timestamp($invoiceData->asStripeInvoice()->due_date) : null,
                    'invoice_created_at'=> $invoiceData->asStripeInvoice()->created ? now()->timestamp($invoiceData->asStripeInvoice()->created) : null,
                    'effective_at'      => $invoiceData->asStripeInvoice()->effective_at ? now()->timestamp($invoiceData->asStripeInvoice()->effective_at) : null,
                    'is_paid'           => $invoiceData->isPaid(),
                    'currency'          => $invoiceData->asStripeInvoice()->currency ?? null,
                    'hosted_url'        => $invoiceData->asStripeInvoice()->hosted_invoice_url ?? null,
                    'pdf_url'           => $invoiceData->asStripeInvoice()->invoice_pdf ?? null,
                    'increment_id'      => $invoiceData->asStripeInvoice()->number ?? null,
                    'status'            => $invoiceData->asStripeInvoice()->status,
                    'amount_paid'       => $this->formatNumber($invoiceData->asStripeInvoice()->subtotal),
                    'subtotal'          => $this->formatNumber($invoiceData->asStripeInvoice()->subtotal),
                    'total'             => $this->formatNumber($invoiceData->asStripeInvoice()->total),
                    'tax'               => $this->formatNumber($invoiceData->asStripeInvoice()->tax),
                    'total_excl_tax'    => $this->formatNumber($invoiceData->asStripeInvoice()->total_excluding_tax),
                ]);

                $this->info("Stored invoice: $subscriptionId");
            } else {
                $this->info("The order for the invoice # $subscriptionId is not found");
            }
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
