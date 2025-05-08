<?php

namespace Modules\Booking\Console;

use Illuminate\Console\Command;
use Modules\Booking\Services\BookingStatusTransitionService;

class ConfirmUpcomingBookingCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'booking:confirm-upcoming';

    /**
     * The console command description.
     */
    protected $description = 'Confirm upcoming bookings.';

    private BookingStatusTransitionService $transitionService;

    /**
     * Create a new command instance.
     */
    public function __construct(BookingStatusTransitionService $transitionService)
    {
        parent::__construct();
        $this->transitionService = $transitionService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $this->transitionService->handle();

            return Command::SUCCESS;
        } catch (\Throwable $exception) {
            $this->error($exception->getMessage());
            return Command::FAILURE;
        }
    }
}
