<?php
declare(strict_types=1);

namespace Modules\BookingGridFlat\Console;

use Illuminate\Console\Command;
use Modules\Booking\Models\BookingManager;
use Modules\Booking\Models\ConfigProvider;
use Modules\BookingGridFlat\Factories\BookingGridFlatFactory;
use Modules\BookingGridFlat\Models\BookingGridFlat;
use Modules\User\Models\User;

class SyncBookingGridFlat extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'booking_grid {--clear-all : Clear all records before sync}';

    /**
     * The console command description.
     */
    protected $description = 'Synchronize booking_grid_flat table with bookings data';

    private BookingGridFlatFactory $bookingGridFlatFactory;
    private BookingManager $bookingManager;
    private ConfigProvider $configProvider;

    /**
     * Create a new command instance.
     * @param BookingGridFlatFactory $bookingGridFlatFactory
     * @param BookingManager $bookingManager
     */
    public function __construct(
        BookingGridFlatFactory $bookingGridFlatFactory,
        BookingManager $bookingManager,
        ConfigProvider $configProvider
    )
    {
        parent::__construct();
        $this->bookingGridFlatFactory = $bookingGridFlatFactory;
        $this->bookingManager = $bookingManager;
        $this->configProvider = $configProvider;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $reindexRequired = $this->isReindexRequired();

        if ($this->option('clear-all')) {
            $this->info('Clearing all records...');
            BookingGridFlat::truncate();
            $reindexRequired = true;
        }

        if (!$reindexRequired) {
            return Command::SUCCESS;
        }

        $bookings = $this->bookingManager->getConfirmedBookings();

        $count = 0;

        foreach ($bookings as $booking) {
            $stream = $booking->stream;
            $teacher = $stream->substitute_teacher_id
                ? User::find($stream->substitute_teacher_id)
                : $stream->teacher;
            $student = $booking->student;

            $bookingRow = [
                'booking_id' => $booking->id,
                'student_id' => $student->id,
                'student_fullname' => $student->name,
                'teacher_id' => $teacher->id,
                'teacher_fullname' => $teacher->name,
                'stream_id' => $stream->id,
                'level_title' => $stream->languageLevel->title,
                'subject_title' => $stream->currentSubject->title ?? null,
                'current_subject_number' => $stream->current_subject_number,
                //'subject_category' => $booking->subject->category ?? null,
                'start_time' => $booking->timeslot->start_time,
                'end_time' => $booking->timeslot->end_time,
                'status' => $booking->status,
            ];

            $this->bookingGridFlatFactory->create($bookingRow)->save();

            $count++;
        }

        $this->markBookingReindexFinished();

        $this->info("Synced {$count} new booking(s).");

        return Command::SUCCESS;
    }

    private function isReindexRequired(): bool
    {
        return $this->configProvider->getBookingReindexRequiredFlag();
    }

    private function markBookingReindexFinished(): void
    {
        $this->configProvider->markBookingReindex(false);
    }
}
