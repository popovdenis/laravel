<?php
declare(strict_types=1);

namespace Modules\LanguageLevel\Services;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Modules\Base\Services\CustomerTimezone;
use Modules\Booking\Enums\BookingStatus;
use Modules\Stream\Models\Stream;
use Modules\User\Models\User;

/**
 * Class CatalogSlotsListService
 *
 * @package Modules\LanguageLevel\Services
 */
class CatalogSlotsListService
{
    private CustomerTimezone $timezone;
    private static ?Collection $allStreams;

    public function __construct(CustomerTimezone $timezone)
    {
        $this->timezone = $timezone;
    }

    public function getStreams(): Collection
    {
        if (empty(self::$allStreams)) {
            self::$allStreams = Stream::with([
                'languageLevel.subjects',
                'teacher.scheduleTimeslots',
                'currentSubject',
                'teacher',
            ])
                ->whereIn('status', ['planned', 'started'])
                ->whereHas('languageLevel', fn($q) => $q->where('is_active', true))
                ->get();
        }

        return self::$allStreams;
    }

    public function filterStreamsByLevel($levelId): Collection
    {
        $streams = $this->getStreams();

        return $levelId
            ? $streams->filter(fn($stream) => $stream->language_level_id == $levelId)
            : $streams;
    }

    public function getLevels(Collection $streams)
    {
        return $streams->map(fn($stream) => $stream->languageLevel)->filter()->unique('id')->values();
    }

    public function getSubjects(?int $levelId, \Illuminate\Support\Collection $levels): ?Collection
    {
        $levelId = $levelId ?? $levels->first()?->id;

        return $levels->firstWhere('id', $levelId)?->subjects;
    }

    public function getUserBookedSlots(User $user): array
    {
        return $user->bookings()
            ->whereIn('status', [BookingStatus::PENDING->value, BookingStatus::CONFIRMED->value])
            ->get(['id', 'schedule_timeslot_id', 'status'])
            ->mapWithKeys(fn($booking) => [
                $booking->schedule_timeslot_id => [
                    'booking_id' => $booking->id,
                    'status' => $booking->status,
                ]
            ])
            ->toArray();
    }

    public function getFilterStartDate(array $filters): Carbon
    {
        return $filters['start_date']
            ? $this->timezone->date($filters['start_date'])
            : $this->timezone->date()->startOfDay();
    }

    public function getFilterEndDate(array $filters): Carbon
    {
        return $filters['end_date']
            ? $this->timezone->date($filters['end_date'])
            : $this->timezone->date()->addDays(7)->startOfDay();
    }

    public function groupSlots(array $filters, Carbon $filterStartDate, Carbon $filterEndDate, User $user): array
    {
        $groupedSlots = [];

        $userBookedSlotIds = auth()->check() ? $this->getUserBookedSlots($user) : [];
        $streams = $this->filterStreamsByLevel($filters['level_id']);

        foreach ($streams as $stream) {
            $subjectId = $stream->current_subject_id;

            if (!empty($filters['subject_ids']) && !in_array($subjectId, $filters['subject_ids'])) {
                continue;
            }

            $streamStart = Carbon::parse($stream->start_date, 'UTC')->setTimezone($user->timeZoneId);
            $streamEnd = Carbon::parse($stream->end_date, 'UTC')->setTimezone($user->timeZoneId);

            $currentDate = $streamStart->greaterThan($filterStartDate)
                ? $streamStart->copy()
                : $filterStartDate->copy();
            while ($currentDate->lte($streamEnd)) {
                $daySlots = $this->getDaySlots($stream, $currentDate);

                foreach ($daySlots as $slot) {
                    $teacherTz = $stream->teacher->timeZoneId ?? 'UTC';

                    $currentDateInTeacherTz = $currentDate->copy()->setTimezone($teacherTz);

                    $slotStart = Carbon::parse($currentDateInTeacherTz->format('Y-m-d') . ' ' . $slot->start, $teacherTz);
                    $slotEnd = Carbon::parse($currentDateInTeacherTz->format('Y-m-d') . ' ' . $slot->end, $teacherTz);

                    $slotStartUtc = $slotStart->copy()->setTimezone('UTC');
                    $slotEndUtc = $slotEnd->copy()->setTimezone('UTC');

                    $filters['lesson_type'] = 'individual';
                    $chunkLength = $filters['lesson_type'] === 'group' ? 90 : 60;

                    $chunkStart = $slotStartUtc->copy();
                    while ($chunkStart->copy()->addMinutes($chunkLength)->lte($slotEndUtc)) {
                        $chunkStartInTz = $chunkStart->copy()->setTimezone($user->timeZoneId);

                        if ($chunkStartInTz->between($filterStartDate, $filterEndDate)) {
                            $dateKey = $chunkStartInTz->toDateString();
                            $groupedSlots[$dateKey][] = $this->formatSlot($chunkStartInTz, $stream, $slot, $userBookedSlotIds);
                        }

                        $chunkStart->addMinutes($chunkLength);
                    }
                }

                $currentDate->addDay();
            }
        }

        ksort($groupedSlots);
        foreach ($groupedSlots as &$slots) {
            usort($slots, fn($a, $b) => $this->compareSlots($a, $b));
        }

        return $groupedSlots;
    }

    private function getDaySlots(Stream $stream, Carbon $currentDate): Collection
    {
        $dayOfWeek = $currentDate->format('l');
        $minStartTime = $this->timezone->date()->addMinutes(5);

        return $stream->teacher->scheduleTimeslots->filter(function ($slot) use ($dayOfWeek, $minStartTime) {
            if (strtolower($slot->day) !== strtolower($dayOfWeek)) {
                return false;
            }
            if (strtolower($slot->day) === strtolower($minStartTime->format('l'))) {
                return $this->timezone->date($slot->start, null, false)->greaterThanOrEqualTo($minStartTime);
            }
            return true;
        });
    }

    private function formatSlot(Carbon $slotStart, Stream $stream, $slot, array $userBookedSlotIds): array
    {
        return [
            'time' => $slotStart->format('H:i'),
            'stream' => $stream,
            'teacher' => $stream->teacher,
            'subject' => $stream->currentSubject,
            'current_subject_number' => $stream->current_subject_number,
            'slot' => $slot,
            'booking_id' => $this->isSlotBooked($userBookedSlotIds[$slot->id] ?? null)
                ? $userBookedSlotIds[$slot->id]['booking_id']
                : null,
        ];
    }

    private function compareSlots($a, $b): int
    {
        $timeA = Carbon::createFromFormat('H:i', $a['time']);
        $timeB = Carbon::createFromFormat('H:i', $b['time']);

        return $timeA->eq($timeB)
            ? $a['current_subject_number'] <=> $b['current_subject_number']
            : ($timeA->lt($timeB) ? -1 : 1);
    }

    private function isSlotBooked($slotStatus = null): ?bool
    {
        return isset($slotStatus['status']) && in_array($slotStatus['status'], [
                BookingStatus::PENDING->value,
                BookingStatus::CONFIRMED->value,
            ], true);
    }
}
