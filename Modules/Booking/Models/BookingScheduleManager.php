<?php
declare(strict_types=1);

namespace Modules\Booking\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Base\Conracts\SearchCriteriaInterface;
use Modules\Base\Framework\AbstractSimpleObject;
use Modules\Base\Services\CustomerTimezone;
use Modules\Booking\Contracts\BookingScheduleInterface;
use Modules\Booking\Enums\BookingStatus;
use Modules\Booking\Enums\BookingTypeEnum;
use Modules\LanguageLevel\Contracts\LanguageLevelRepositoryInterface;
use Modules\LanguageLevel\DTO\SlotResult;
use Modules\Stream\Contracts\StreamRepositoryInterface;
use Modules\Stream\Models\Stream;
use Modules\User\Models\User;

/**
 * Class BookingScheduleManager
 *
 * @package Modules\Booking\Models
 */
class BookingScheduleManager extends AbstractSimpleObject implements BookingScheduleInterface
{
    private StreamRepositoryInterface $streamRepository;
    private SearchCriteriaInterface $searchCriteria;
    private CustomerTimezone $timezone;
    private LanguageLevelRepositoryInterface $languageLevelRepository;
    private ConfigProvider $configProvider;

    public function __construct(
        CustomerTimezone $timezone,
        SearchCriteriaInterface $searchCriteria,
        StreamRepositoryInterface $streamRepository,
        LanguageLevelRepositoryInterface $languageLevelRepository,
        ConfigProvider $configProvider,
        array $data = []
    )
    {
        parent::__construct($data);
        $this->timezone = $timezone;
        $this->searchCriteria = $searchCriteria;
        $this->streamRepository = $streamRepository;
        $this->languageLevelRepository = $languageLevelRepository;
        $this->configProvider = $configProvider;
    }

    public function setStudent(User $student): self
    {
        return $this->setData(self::STUDENT, $student);
    }

    public function getStudent()
    {
        return $this->_get(self::STUDENT);
    }

    public function setFilters(array $filters): self
    {
        return $this->setData(self::FILTERS, $filters);
    }

    public function getFilters(): array
    {
        return (array) $this->_get(self::FILTERS);
    }

    public function setStreams(LengthAwarePaginator $streams): self
    {
        return $this->setData(self::STREAMS, $streams);
    }

    public function setLevels($levels): self
    {
        return $this->setData(self::LEVELS, $levels);
    }

    public function setSubjects($subjects): self
    {
        return $this->setData(self::SUBJECTS, $subjects);
    }

    public function getDefaultLessonType()
    {
        return $this->configProvider->getDefaultLessonType();
    }

    public function getInitialDaysToShow()
    {
        return $this->configProvider->getInitialDaysToShow();
    }

    public function getStreams(): LengthAwarePaginator
    {
        if (empty($this->_get(self::STREAMS))) {
            $this->searchCriteria->setWith([
                'languageLevel.subjects',
                'teacher.scheduleTimeslots',
                'currentSubject',
                'teacher',
            ])->setFilters([
                'status' => ['planned', 'started'],
            ])->setWhereHas([
                'languageLevel' => fn($q) => $q->where('is_active', true),
            ]);

            $this->setStreams($this->streamRepository->getList($this->searchCriteria));
        }

        return $this->_get(self::STREAMS);
    }

    public function getStreamLevels()
    {
        if (empty($this->_get(self::LEVELS))) {
            $this->setLevels($this->getStreams()
                ->map(fn($stream) => $stream->languageLevel)
                ->filter()
                ->unique('id')
                ->values()
            );
        }

        return $this->_get(self::LEVELS);
    }

    public function getFirstStreamLevel()
    {
        if (empty($this->_get(self::FIRST_LEVEL))) {
            $this->setData(self::FIRST_LEVEL, $this->getStreamLevels()->first());
        }

        return $this->_get(self::FIRST_LEVEL);
    }

    public function getLevelSubjects(): ?Collection
    {
        if (empty($this->_get(self::SUBJECTS))) {
            try {
                $filters = $this->getFilters();
                $levelId = $filters['level_id'] ?? $this->getFirstStreamLevel()->id;

                $level = $this->languageLevelRepository->getById($levelId);

                $this->setSubjects($level->subjects);
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $exception) {
                report($exception);
            }
        }

        return $this->_get(self::SUBJECTS);
    }

    public function getFilterStartDate(): Carbon
    {
        if (empty($this->_get(self::FILTER_START_DATE))) {
            $filters = $this->getFilters();
            $systemTimezone = $this->timezone->getConfigTimezone();

            $startDate = isset($filters['start_date'])
                ? $this->timezone->date($filters['start_date'])->setTimeFrom(Carbon::now($systemTimezone))
                : $this->timezone->date()->startOfDay();

            $this->setData(self::FILTER_START_DATE, $startDate);
        }

        return $this->_get(self::FILTER_START_DATE);
    }

    public function getFilterEndDate(): Carbon
    {
        if (empty($this->_get(self::FILTER_END_DATE))) {
            $filters = $this->getFilters();
            $daysRange = $this->getInitialDaysToShow();

            $endDate = isset($filters['end_date'])
                ? $this->timezone->date($filters['end_date'])->endOfDay()
                : $this->timezone->date()->addDays($daysRange)->startOfDay();

            $this->setData(self::FILTER_END_DATE, $endDate);
        }

        return $this->_get(self::FILTER_END_DATE);
    }

    public function groupSlots(): array
    {
        $filters = $this->getFilters();

        $groupedSlots = [];

        $student = $this->getStudent();
        $streams = $this->filterStreamsByLevel($filters['level_id']);

        foreach ($streams as $stream) {
            $slots = $this->getChunksForStream($stream, $student);

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

    public function getBookingScheduleSlots(): array
    {
        $filters = $this->getFilters();

        return [
            'levels' => $this->getStreamLevels(),
            'subjects' => $this->getLevelSubjects(),
            'lessonType' => $filters['lesson_type'],
            'slots' => $this->groupSlots(),
            'selectedLevelId' => $filters['level_id'] ?? $this->getFirstStreamLevel()?->id,
            'selectedSubjectIds' => $filters['subject_ids'],
            'filterStartDate' => $this->getFilterStartDate()->toDateString(),
            'filterEndDate' => $this->getFilterEndDate()->toDateString(),
        ];
    }

    protected function filterStreamsByLevel($levelId = null): LengthAwarePaginator|Collection
    {
        $streams = $this->getStreams();

        return $levelId
            ? $streams->filter(fn($stream) => $stream->language_level_id == $levelId)
            : $streams;
    }

    protected function getUserBookedSlots(User $user): Collection
    {
        if (empty($this->_get(self::USER_BOOKED_SLOTS))) {
            $bookings = $user->bookings()
                ->whereIn('status', [BookingStatus::PENDING->value, BookingStatus::CONFIRMED->value])
                ->get();

            $this->setData(self::USER_BOOKED_SLOTS, $bookings);
        }

        return $this->_get(self::USER_BOOKED_SLOTS);
    }

    private function compareSlots($a, $b): int
    {
        $timeA = Carbon::createFromFormat('H:i', $a->time);
        $timeB = Carbon::createFromFormat('H:i', $b->time);

        return $timeA->eq($timeB)
            ? $a->currentSubjectNumber <=> $b->currentSubjectNumber
            : ($timeA->lt($timeB) ? -1 : 1);
    }

    private function getStreamStartDate($stream)
    {
        return Carbon::parse($stream->start_date, 'UTC')->setTimezone($this->getStudent()->timeZoneId)->startOfDay();
    }

    private function getStreamEndDate($stream)
    {
        return Carbon::parse($stream->end_date, 'UTC')->setTimezone($this->getStudent()->timeZoneId)->endOfDay();
    }

    private function getCurrentStreamDate($streamStart)
    {
        $filterStartDate = $this->getFilterStartDate();

        return $streamStart->greaterThan($filterStartDate) ? $streamStart->copy() : $filterStartDate->copy();
    }

    private function getChunksForStream(Stream $stream): array
    {
        $results = [];

        // TODO: get start/end by Repeat
        $streamStart = $this->getStreamStartDate($stream);
        $streamEnd = $this->getStreamEndDate($stream);
        $currentDate = $this->getCurrentStreamDate($streamStart);
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

    private function splitSlotToChunks(
        $tDaySlot,
        Stream $stream,
        Carbon $currentDate,
        int $subjectId
    ): array {
        $filters = $this->getFilters();

        $subject = $stream->languageLevel->subjects->firstWhere('id', $subjectId);

        if (!empty($filters['subject_ids']) && $subject && !in_array($subjectId, $filters['subject_ids'])) {
            return [];
        }

        $results = [];

        $student = $this->getStudent();
        $teacherTz = $stream->teacher->timeZoneId ?? 'UTC';
        $studentTz = $student->timeZoneId ?? 'UTC';

        [$tSlotStartInStz, $tSlotEndInStz] = $this->calculateSlotWindow($tDaySlot, $currentDate, $teacherTz, $studentTz);
        $chunkLength = $this->getChunkLength($filters['lesson_type']);

        $this->generateAvailableChunks(
            $tSlotStartInStz,
            $tSlotEndInStz,
            $chunkLength,
            function (Carbon $chunkStartInTz) use (&$results, $stream, $tDaySlot, $subjectId) {
                $dateKey = $chunkStartInTz->toDateString();
                $results[$dateKey][] = $this->formatSlot($chunkStartInTz, $stream, $tDaySlot, $subjectId);
            }
        );

        return $results;
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
        callable $callback
    ): void
    {
        $filterStartDate = $this->getFilterStartDate();
        $filterEndDate = $this->getFilterEndDate();
        $student = $this->getStudent();
        $studentTz = $student->timeZoneId;

        $chunkStart = $tSlotStartInStz->copy();

        while ($chunkStart->copy()->addMinutes($chunkLength)->lte($tSlotEndInStz)) {
            $preferredStart = $student->preferred_start_time
                ? Carbon::createFromFormat('H:i', $student->preferred_start_time->format('H:i'), $studentTz)
                : null;
            $preferredEnd = $student->preferred_end_time
                ? Carbon::createFromFormat('H:i', $student->preferred_end_time->format('H:i'), $studentTz)
                : null;

            if ($chunkStart->between($filterStartDate, $filterEndDate)
                && (!$preferredStart || $chunkStart->gte($chunkStart->copy()->setTimeFrom($preferredStart)))
                && (!$preferredEnd || $chunkStart->lte($chunkStart->copy()->setTimeFrom($preferredEnd)))
            ) {
                $callback($chunkStart);
            }

            $chunkStart->addMinutes($chunkLength);
        }
    }

    private function formatSlot(Carbon $slotStart, Stream $stream, $daySlot, $subjectId): ?SlotResult
    {
        $filters = $this->getFilters();
        $student = $this->getStudent();
        $userBookedSlots = $this->getUserBookedSlots($student);

        $subject = $stream->languageLevel->subjects->firstWhere('id', $subjectId);

        if (!empty($filters['subject_ids']) && $subject && !in_array($subject->id, $filters['subject_ids'])) {
            return null;
        }

        $slotStartUTC = $slotStart->copy()->setTimezone('UTC')->format('Y-m-d H:i:s');

        $booking = $userBookedSlots->first(fn($b) =>
            $b->student_id === $student->id &&
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

    private function getChunkLength(string $lessonType): int
    {
        return match ($this->getLessonEnumType($lessonType)) {
            BookingTypeEnum::BOOKING_TYPE_GROUP => $this->configProvider->getBookingGroupLessonDuration(),
            BookingTypeEnum::BOOKING_TYPE_INDIVIDUAL => $this->configProvider->getBookingIndividualLessonDuration(),
        };
    }

    private function getLessonEnumType($lessonType)
    {
        return BookingTypeEnum::from($lessonType);
    }
}
