<?php
declare(strict_types=1);

namespace Modules\LanguageLevel\Services;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Modules\Base\Services\CustomerTimezone;
use Modules\Booking\Enums\BookingStatus;
use Modules\Booking\Enums\BookingTypeEnum;
use Modules\LanguageLevel\DTO\SlotResult;
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

    public function getUserBookedSlots(User $user)
    {
        return $user->bookings()
            ->whereIn('status', [BookingStatus::PENDING->value, BookingStatus::CONFIRMED->value])
            ->get();
    }

    public function getFilterStartDate(array $filters): Carbon
    {
        return $filters['start_date']
            ? $this->timezone->date($filters['start_date'])->setTimeFrom(Carbon::now($this->timezone->getConfigTimezone()))
            : $this->timezone->date()->startOfDay();
    }

    // TODO: user Config for addDays
    public function getFilterEndDate(array $filters): Carbon
    {
        return $filters['end_date']
            ? $this->timezone->date($filters['end_date'])->endOfDay()
            : $this->timezone->date()->addDays(7)->startOfDay();
    }

    public function groupSlots(array $filters, Carbon $filterStartDate, Carbon $filterEndDate, User $user): array
    {
        $groupedSlots = [];
        $userBookedSlots = auth()->check() ? $this->getUserBookedSlots($user) : [];
        $streams = $this->filterStreamsByLevel($filters['level_id']);

        foreach ($streams as $stream) {
            $slots = $this->getChunksForStream($stream, $filters, $filterStartDate, $filterEndDate, $user, $userBookedSlots);

            foreach ($slots as $date => $slotList) {
                $groupedSlots[$date] = array_merge($groupedSlots[$date] ?? [], $slotList);
            }
        }

        ksort($groupedSlots);
        foreach ($groupedSlots as &$slots) {
            usort($slots, fn($a, $b) => $this->compareSlots($a, $b));
        }

        return $groupedSlots;
    }

    private function getChunksForStream(Stream $stream, array $filters, Carbon $filterStartDate, Carbon $filterEndDate, User $user, Collection $userBookedSlots): array
    {
        $results = [];

        // TODO: get start/end by Repeat
        $streamStart = Carbon::parse($stream->start_date, 'UTC')->setTimezone($user->timeZoneId)->startOfDay();
        $streamEnd = Carbon::parse($stream->end_date, 'UTC')->setTimezone($user->timeZoneId)->endOfDay();
        $currentDate = $streamStart->greaterThan($filterStartDate) ? $streamStart->copy() : $filterStartDate->copy();
        $slotIndex = $streamStart->diffInDays($currentDate);

        while ($currentDate->lte($streamEnd)) {
            $teacherDaySlots = $this->getDaySlots($stream->teacher, $currentDate);
            $subjectIds = $stream->languageLevel->subjects->pluck('id')->values();
            $currentIndex = $subjectIds->search($stream->current_subject_id);
            $shiftedIndex = ($currentIndex + $slotIndex) % $subjectIds->count();
            $subjectId = $subjectIds[$shiftedIndex];

            foreach ($teacherDaySlots as $teacherDaySlot) {
                $chunks = $this->splitSlotToChunks(
                    $teacherDaySlot,
                    $stream,
                    $currentDate,
                    $filters,
                    $filterStartDate,
                    $filterEndDate,
                    $user,
                    $userBookedSlots,
                    $subjectId
                );
                foreach ($chunks as $date => $chunkList) {
                    $results[$date] = array_merge($results[$date] ?? [], $chunkList);
                }
            }

            $slotIndex++;
            $currentDate->addDay();
        }

        return $results;
    }

    private function getChunkLength(array $filters): int
    {
        return $this->getLessonDuration($filters['lesson_type']);
    }

    private function getLessonDuration($lessonType): int
    {
        return match ($this->getLessonEnumType($lessonType)) {
            BookingTypeEnum::BOOKING_TYPE_GROUPED => 90,
            BookingTypeEnum::BOOKING_TYPE_INDIVIDUAL => 60,
        };
    }

    private function getLessonEnumType($lessonType)
    {
        return BookingTypeEnum::from($lessonType);
    }

    private function calculateSlotWindow($teacherSlot, Carbon $currentDate, string $teacherTz, string $studentTz): array
    {
        $currentDateInTz = $currentDate->copy()->setTimezone($teacherTz);

        return [
            Carbon::parse($currentDateInTz->format('Y-m-d') . ' ' . $teacherSlot->start, $teacherTz)->setTimezone($studentTz),
            Carbon::parse($currentDateInTz->format('Y-m-d') . ' ' . $teacherSlot->end, $teacherTz)->setTimezone($studentTz),
        ];
    }

    private function generateAvailableChunks(
        Carbon $tSlotStartInStz,
        Carbon $tSlotEndInStz,
        int $chunkLength,
        string $studentTz,
        Carbon $filterStart,
        Carbon $filterEnd,
        User $user,
        callable $callback
    ): void
    {
        $chunkStart = $tSlotStartInStz->copy();

        while ($chunkStart->copy()->addMinutes($chunkLength)->lte($tSlotEndInStz)) {
            $preferredStart = $user->preferred_start_time
                ? Carbon::createFromFormat('H:i', $user->preferred_start_time->format('H:i'), $studentTz)
                : null;
            $preferredEnd = $user->preferred_end_time
                ? Carbon::createFromFormat('H:i', $user->preferred_end_time->format('H:i'), $studentTz)
                : null;

            if (
                $chunkStart->between($filterStart, $filterEnd) &&
                (!$preferredStart || $chunkStart->gte($chunkStart->copy()->setTimeFrom($preferredStart))) &&
                (!$preferredEnd || $chunkStart->lte($chunkStart->copy()->setTimeFrom($preferredEnd)))
            ) {
                $callback($chunkStart);
            }

            $chunkStart->addMinutes($chunkLength);
        }
    }

    private function splitSlotToChunks(
        $tDaySlot,
        Stream $stream,
        Carbon $currentDate,
        array $filters,
        Carbon $filterStartDate,
        Carbon $filterEndDate,
        User $user,
        Collection $userBookedSlots,
        int $subjectId
    ): array {
        $results = [];
        $teacherTz = $stream->teacher->timeZoneId ?? 'UTC';
        $studentTz = $user->timeZoneId ?? 'UTC';
        $chunkLength = $this->getChunkLength($filters);

        [$tSlotStartInStz, $tSlotEndInStz] = $this->calculateSlotWindow($tDaySlot, $currentDate, $teacherTz, $studentTz);

        $subject = $stream->languageLevel->subjects->firstWhere('id', $subjectId);

        if (!empty($filters['subject_ids']) && $subject && !in_array($subjectId, $filters['subject_ids'])) {
            return [];
        }

        $this->generateAvailableChunks(
            $tSlotStartInStz,
            $tSlotEndInStz,
            $chunkLength,
            $user->timeZoneId,
            $filterStartDate,
            $filterEndDate,
            $user,
            function (Carbon $chunkStartInTz) use (&$results, $stream, $tDaySlot, $userBookedSlots, $subjectId, $filters, $user) {
                $dateKey = $chunkStartInTz->toDateString();
                $results[$dateKey][] = $this->formatSlot($chunkStartInTz, $stream, $tDaySlot, $userBookedSlots, $subjectId, $filters, $user);
            }
        );

        return $results;
    }

    private function getDaySlots(User $teacher, Carbon $currentDate): Collection
    {
        $sDayOfWeek = $currentDate->format('l');
        $tDayOfWeek = $currentDate->copy()->setTimezone($teacher->timeZoneId)->format('l');
        $minStartTime = $this->timezone->date(null, null, false)->addMinutes(5);

        return $teacher->scheduleTimeslots->filter(function ($slot) use ($tDayOfWeek, $sDayOfWeek, $minStartTime) {
            if (strtolower($slot->day) !== strtolower($tDayOfWeek)) {
                return false;
            }
//            if (strtolower($slot->day) === strtolower($minStartTime->format('l'))) {
//                return $this->timezone->date($slot->start, null, false)->greaterThanOrEqualTo($minStartTime);
//            }
            return true;
        });
    }

    private function formatSlot(Carbon $slotStart, Stream $stream, $daySlot, Collection $userBookedSlots, $subjectId, array $filters, User $user): ?SlotResult
    {
        $subject = $stream->languageLevel->subjects->firstWhere('id', $subjectId);

        if (!empty($filters['subject_ids']) && $subject && !in_array($subject->id, $filters['subject_ids'])) {
            return null;
        }

        $slotStartUTC = $slotStart->copy()->setTimezone('UTC')->format('Y-m-d H:i:s');

        $booking = $userBookedSlots->first(fn($b) =>
            $b->student_id === $user->id &&
            $b->slot_start_at->equalTo($slotStartUTC)
        );

        return new SlotResult(
            time: $slotStart->format('H:i'),
            slotStartAt: $slotStart->format('Y-m-d H:i'),
            lessonType: $this->getLessonEnumType($filters['lesson_type'])->value,
            stream: $stream,
            teacher: $stream->teacher,
            subject: $subject,
            currentSubjectNumber: $subjectId,
            slot: $daySlot,
            uid: md5($slotStart->format('Y-m-d H:i')),
            bookingId: $booking ? $booking->id : null,
        );
    }

    private function compareSlots($a, $b): int
    {
        $timeA = Carbon::createFromFormat('H:i', $a->time);
        $timeB = Carbon::createFromFormat('H:i', $b->time);

        return $timeA->eq($timeB)
            ? $a->currentSubjectNumber <=> $b->currentSubjectNumber
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
