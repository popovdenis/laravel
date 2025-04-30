<?php
declare(strict_types=1);

namespace Modules\Booking\Data;

use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Exists;
use Modules\Booking\Enums\BookingTypeEnum;
use Modules\User\Models\User;
use Spatie\LaravelData\Data;

/**
 * Class BookingData
 *
 * @package App\DTO
 */
class BookingData extends Data
{
    public function __construct(
        public User $student,
        public int $slotId,
        public ?int $streamId,
        public ?int $teacherId,
        public BookingTypeEnum $bookingType = BookingTypeEnum::BOOKING_TYPE_GROUPED,
        public array $extra = []
    ) {
    }

    public static function rules(): array
    {
        return [
            'stream_id' => ['required', 'integer', new Exists('streams', 'id')],
            'slot_id'   => ['required', 'integer', new Exists('schedule_timeslots', 'id')],
        ];
    }

    public static function fromRequest(Request $request): static
    {
        return static::from([
            'student' => $request->user(),
            'streamId' => $request->input('stream_id') ?? null,
            'slotId' => $request->input('slot_id'),
        ]);
    }
}
