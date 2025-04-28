<?php
declare(strict_types=1);

namespace Modules\Stream\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Stream\Enums\StreamStatus;
use Modules\Stream\Models\Stream;
use Throwable;

/**
 * Class StreamProcessorService
 *
 * @package App\Services
 */
class StreamProcessorService
{
    public function run(): void
    {
        $today = now()->startOfDay();

        try {
            DB::transaction(function () use ($today) {
                $this->processStartingStreams($today);
                $this->processFinishingStreams($today);
            });
        } catch (Throwable $e) {
            Log::error('Stream processing failed: ' . $e->getMessage(), [
                'exception' => $e,
            ]);
        }
    }

    private function processStartingStreams($today): void
    {
        $streams = Stream::where('status', StreamStatus::PLANNED)
            ->whereDate('start_date', $today)
            ->get();

        foreach ($streams as $stream) {
            try {
                $firstSubject = $stream->languageLevel->subjects()->orderBy('id')->first();
                if ($firstSubject) {
                    $stream->update([
                        'status'                 => StreamStatus::STARTED,
                        'current_subject_id'     => $firstSubject->id,
                        'current_subject_number' => 1,
                    ]);
                    Log::info("Stream #{$stream->id} started successfully.");
                } else {
                    Log::warning("Stream #{$stream->id} has no subjects to start.");
                }
            } catch (Throwable $e) {
                Log::error("Failed to start stream #{$stream->id}: " . $e->getMessage(), [
                    'exception' => $e,
                ]);
            }
        }
    }

    private function processFinishingStreams($today): void
    {
        $streams = Stream::where('status', 'started')
            ->whereDate('end_date', '<', $today)
            ->get();

        foreach ($streams as $stream) {
            try {
                $firstSubject = $stream->languageLevel->subjects()->orderBy('id')->first();

                if ($stream->repeat) {
                    $stream->update([
                        'current_subject_id'     => $firstSubject?->id,
                        'current_subject_number' => 1,
                    ]);
                    Log::info("Stream #{$stream->id} repeated successfully.");
                } else {
                    $stream->update([
                        'status'                 => 'finished',
                        'current_subject_id'     => $firstSubject?->id,
                        'current_subject_number' => 1,
                    ]);
                    Log::info("Stream #{$stream->id} finished successfully.");
                }
            } catch (Throwable $e) {
                Log::error("Failed to finish stream #{$stream->id}: " . $e->getMessage(), [
                    'exception' => $e,
                ]);
            }
        }
    }
}
