<?php
declare(strict_types=1);

namespace Modules\Booking\Data;

use App\Enums\PaymentMethod;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Exists;
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
        public PaymentMethod $paymentMethod,
        public ?int $streamId,
        public ?int $teacherId,
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
            'paymentMethod' => PaymentMethod::CREDITS,
        ]);
    }
}
