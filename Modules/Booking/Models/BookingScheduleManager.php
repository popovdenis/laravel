<?php
declare(strict_types=1);

namespace Modules\Booking\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Base\Conracts\SearchCriteriaInterface;
use Modules\Base\Framework\AbstractSimpleObject;
use Modules\Base\Framework\DataObject;
use Modules\Base\Services\CustomerTimezone;
use Modules\Booking\Contracts\BookingScheduleInterface;
use Modules\Booking\Contracts\SlotContextInterface;
use Modules\Booking\Enums\BookingStatus;
use Modules\Booking\Exceptions\BookingValidationException;
use Modules\Booking\Services\BookingSlotService;
use Modules\LanguageLevel\Contracts\LanguageLevelRepositoryInterface;
use Modules\LanguageLevel\DTO\SlotResult;
use Modules\Stream\Contracts\StreamRepositoryInterface;
use Modules\Stream\Enums\StreamStatus;
use Modules\User\Models\User;

/**
 * Class BookingScheduleManager
 *
 * @package Modules\Booking\Models
 */
class BookingScheduleManager extends AbstractSimpleObject implements BookingScheduleInterface
{
    private StreamRepositoryInterface        $streamRepository;
    private SearchCriteriaInterface          $searchCriteria;
    private CustomerTimezone                 $timezone;
    private LanguageLevelRepositoryInterface $languageLevelRepository;
    private SlotContext                      $bookingSlot;
    private DataObject                       $dataObject;
    private BookingSlotService               $bookingSlotService;
    private SlotValidator                    $slotValidator;

    public function __construct(
        CustomerTimezone                 $timezone,
        SearchCriteriaInterface          $searchCriteria,
        StreamRepositoryInterface        $streamRepository,
        LanguageLevelRepositoryInterface $languageLevelRepository,
        SlotContext                      $bookingSlot,
        DataObject                       $dataObject,
        BookingSlotService               $bookingSlotService,
        SlotValidator                    $slotValidator,
        array                            $data = []
    )
    {
        parent::__construct($data);
        $this->timezone                = $timezone;
        $this->searchCriteria          = $searchCriteria;
        $this->streamRepository        = $streamRepository;
        $this->languageLevelRepository = $languageLevelRepository;
        $this->bookingSlot             = $bookingSlot;
        $this->dataObject              = $dataObject;
        $this->bookingSlotService      = $bookingSlotService;
        $this->slotValidator           = $slotValidator;
    }

    public function setStudent(User $student): self
    {
        return $this->setData(self::STUDENT, $student);
    }

    public function getStudent()
    {
        return $this->_get(self::STUDENT);
    }

    public function setTeacher(User $teacher): self
    {
        return $this->setData(self::TEACHER, $teacher);
    }

    public function getTeacher()
    {
        return $this->_get(self::TEACHER);
    }

    public function setFilters(array $filters): self
    {
        $filtersObj = $this->dataObject->create()->setData($filters);

        return $this->setData(self::FILTERS, $filtersObj);
    }

    public function getFilters(): DataObject
    {
        return $this->_get(self::FILTERS);
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

    public function getStreams(): LengthAwarePaginator
    {
        if (empty($this->_get(self::STREAMS))) {
            $this->searchCriteria->setWith([
                'languageLevel.subjects',
                'teacher.scheduleTimeslots',
                'currentSubject',
                'teacher',
            ])->setFilters([
                'status' => [StreamStatus::PLANNED, StreamStatus::STARTED],
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
                $levelId = $filters->getLevelId() ?? $this->getFirstStreamLevel()->id;

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
            $sconfigTimezone = $this->timezone->getConfigTimezone();

            $startDate = $filters->hasData('start_date')
                ? $this->timezone->date($filters->getStartDate())->setTimeFrom(Carbon::now($sconfigTimezone))
                : $this->timezone->date()->startOfDay();

            $this->setData(self::FILTER_START_DATE, $startDate);
        }

        return $this->_get(self::FILTER_START_DATE);
    }

    public function getFilterEndDate(): Carbon
    {
        if (empty($this->_get(self::FILTER_END_DATE))) {
            $filters = $this->getFilters();
            $daysRange = $this->bookingSlotService->getInitialDaysToShow();

            $endDate = $filters->hasData('end_date')
                ? $this->timezone->date($filters->getEndDate())->endOfDay()
                : $this->timezone->date()->addDays($daysRange)->startOfDay();

            $this->setData(self::FILTER_END_DATE, $endDate);
        }

        return $this->_get(self::FILTER_END_DATE);
    }

    public function groupSlots(): array
    {
        $filters = $this->getFilters();
        $lessonTypeEnum = $this->bookingSlotService->getLessonEnumType($this->getFilters()->getLessonType());

        $bookingSlot = $this->bookingSlot->create();
        $bookingSlot->setData([
            'student'       => $this->getStudent(),
            'lesson_type'   => $lessonTypeEnum,
            'lesson_length' => $this->bookingSlotService->getChunkLength($lessonTypeEnum),
        ]);

        $groupedSlots = [];

        $streams = $this->filterStreamsByLevel($filters->getLevelId());

        foreach ($streams as $stream) {
            $bookingSlot->setStream($stream);
            $slots = $this->getChunksForStream($bookingSlot);

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
            'lessonType' => $filters->getLessonType(),
            'slots' => $this->groupSlots(),
            'selectedLevelId' => $filters->getLevelId() ?? $this->getFirstStreamLevel()?->id,
            'selectedSubjectIds' => $filters->getSubjectIds(),
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
        $filterStartDate = $this->getFilterStartDate();
        $streamStart = $this->timezone->date($stream->start_date)->startOfDay();

        return $filterStartDate ?? $streamStart;
    }

    private function getStreamEndDate($stream)
    {
        $filterEndDate = $this->getFilterEndDate();
        $streamEnd = $this->timezone->date($stream->end_date)->endOfDay();

        return $filterEndDate ?? $streamEnd;
    }

    private function getCurrentStreamDate($streamStart)
    {
        $filterStartDate = $this->getFilterStartDate();

        return $streamStart->greaterThan($filterStartDate) ? $streamStart->copy() : $filterStartDate->copy();
    }

    private function getChunksForStream(SlotContext $bookingSlot): array
    {
        $results = [];

        $stream = $bookingSlot->getStream();
        $bookingSlot->setTeacher($stream->teacher);

        // TODO: get start/end by Repeat
        $streamStart = $this->getStreamStartDate($stream);
        $streamEnd = $this->getStreamEndDate($stream);
        $currentDate = $this->getCurrentStreamDate($streamStart);
        $slotIndex = $streamStart->diffInDays($currentDate);

        while ($currentDate->lte($streamEnd)) {
            $teacherDaySlots = $this->getDaySlots($bookingSlot->getTeacher(), $currentDate);
            $subjectIds = $stream->languageLevel->subjects->pluck('id')->values();//TODO: move to repository
            $currentIndex = $subjectIds->search($stream->current_subject_id);
            $shiftedIndex = ($currentIndex + $slotIndex) % $subjectIds->count();
            $subjectId = $subjectIds[$shiftedIndex];

            foreach ($teacherDaySlots as $teacherDaySlot) {
                $bookingSlot->addData([
                    'day_slot'     => $teacherDaySlot,
                    'current_date' => $currentDate,
                    'subject_id'   => $subjectId,
                ]);
                $chunks = $this->splitSlotToChunks($bookingSlot);
                foreach ($chunks->toArray() as $date => $chunkList) {
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
        $tDayOfWeek = $currentDate->copy()->setTimezone($teacher->timeZoneId)->format('l');

        return $teacher->scheduleTimeslots->filter(function ($slot) use ($tDayOfWeek) {
            if (strtolower($slot->day_of_week) !== strtolower($tDayOfWeek)) {
                return false;
            }
            return true;
        });
    }

    private function splitSlotToChunks(SlotContext $bookingSlot): DataObject
    {
        $response = $this->dataObject->create();

        $filters = $this->getFilters();
        $subject = $bookingSlot
            ->getStream()
            ->languageLevel
            ->subjects
            ->firstWhere('id', $bookingSlot->getSubjectId());

        if ($filters->getSubjectIds()
            && $subject
            && !in_array($bookingSlot->getSubjectId(), $filters->getSubjectIds())
        ) {
            return $response;
        }

        $student = $this->getStudent();
        $teacherTz = $bookingSlot->getTeacher()->timeZoneId ?? 'UTC';
        $studentTz = $student->timeZoneId ?? 'UTC';

        [$tSlotStartInStz, $tSlotEndInStz] = $this->calculateSlotWindow($bookingSlot, $teacherTz, $studentTz);

        $this->generateAvailableChunks(
            $tSlotStartInStz,
            $tSlotEndInStz,
            $bookingSlot,
            function () use ($response, $bookingSlot) {
                $response->appendData(
                    $bookingSlot->getSlotStart()->toDateString(),
                    $this->formatSlot($bookingSlot)
                );
            }
        );

        return $response;
    }

    private function generateAvailableChunks(
        Carbon      $tSlotStartInStz,
        Carbon      $tSlotEndInStz,
        SlotContext $bookingSlot,
        callable    $callback
    ): void
    {
        $chunkLength = $bookingSlot->getSlotLength();
        $chunkStart = $tSlotStartInStz->copy();

        while ($chunkStart->copy()->addMinutes($chunkLength)->lte($tSlotEndInStz)) {
            $bookingSlot
                ->setSlotStart($chunkStart->copy())
                ->setSlotEnd($chunkStart->copy()->addMinutes($chunkLength));

            if ($this->isSlotBookable($bookingSlot)) {
                $callback();
            }

            $chunkStart->addMinutes($chunkLength);
        }
    }

    public function isSlotBookable(SlotContext $bookingSlot): bool
    {
        try {
            $this->slotValidator->validate($bookingSlot);
        } catch (BookingValidationException $e) {
            return false;
        }

        return true;
    }

    private function formatSlot(SlotContext $bookingSlot): ?SlotResult
    {
        $filters = $this->getFilters();
        $subject = $bookingSlot->getSubject();

        if (!empty($filters->getSubjectIds()) && $subject && !in_array($subject->id, $filters->getSubjectIds())) {
            return null;
        }

        $student = $bookingSlot->getStudent();
        $teacher = $bookingSlot->getTeacher();
        $userBookedSlots = $this->getUserBookedSlots($student);
        $slotStart = $bookingSlot->getSlotStart();
        $slotEnd = $bookingSlot->getSlotEnd();

        $booking = $userBookedSlots->first(fn($b) =>
            $b->student_id === $student->id &&
            $b->teacher_id === $teacher->id &&
            $b->stream_id === $bookingSlot->getStream()->id &&
            $b->lesson_type->value === $filters->getLessonType() &&
            $b->slot_start_at->equalTo($slotStart) &&
            $b->slot_end_at->equalTo($slotEnd) &&
            $b->status <> BookingStatus::CANCELLED
        );

        return $bookingSlot->getSlotResult($booking);
    }

    private function calculateSlotWindow(SlotContextInterface $bookingSlot, string $teacherTz, string $studentTz): array
    {
        $currentDateInTz = $bookingSlot->getCurrentDate()->copy()->setTimezone($teacherTz)->format('Y-m-d');
        $startTime = $bookingSlot->getDaySlot()->start_time;
        $endTime = $bookingSlot->getDaySlot()->end_time;

        $tSlotStart = Carbon::parse($currentDateInTz . ' ' . $startTime->format('H:i'), $teacherTz);
        $tSlotEnd = Carbon::parse($currentDateInTz . ' ' . $endTime->format('H:i'), $teacherTz);

        return [
            $tSlotStart->setTimezone($studentTz),
            $tSlotEnd->setTimezone($studentTz),
        ];
    }
}
