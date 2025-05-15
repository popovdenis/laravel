<?php
declare(strict_types=1);

namespace Modules\Booking\Data;

use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Exists;
use Modules\Booking\Enums\BookingTypeEnum;
use Modules\Booking\Models\Booking;
use Modules\Payment\Contracts\RequestDataInterface;
use Modules\User\Models\User;
use Spatie\LaravelData\Data;

/**
 * Class BookingData
 *
 * @package App\DTO
 */
class BookingData extends Data implements RequestDataInterface
{
    public function __construct(
        public User $student,
        public int $slotId,
        public string $slotStartAt,
        public ?int $streamId,
        public ?int $teacherId,
        public ?string $method,
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
            'slotStartAt' => $request->input('slotStartAt'),
            'method' => setting('booking.applicable_payment_method')
        ]);
    }

    public static function fromModel(Booking $booking): static
    {
        return static::from([
            'student' => $booking->student,
            'streamId' => $booking->stream->id,
            'slotId' => $booking->timeslot->id,
            'method' => setting('booking.applicable_payment_method')
        ]);
    }
}
