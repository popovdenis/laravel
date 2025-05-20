<?php
declare(strict_types=1);

namespace Modules\Booking\Models;

use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Base\Conracts\SearchCriteriaInterface;
use Modules\Base\Services\CustomerTimezone;
use Modules\Booking\Contracts\BookingRepositoryInterface;
use Modules\Booking\Enums\BookingStatus;
use Modules\User\Models\User;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * Class BookingRepository
 *
 * @package Modules\Booking\Models
 */
class BookingRepository implements BookingRepositoryInterface
{
    private CustomerTimezone $timezone;

    public function __construct(CustomerTimezone $timezone)
    {
        $this->timezone = $timezone;
    }

    public function create(array $data)
    {
        return Booking::create($data);
    }

    public function save($entity)
    {
        if (!$entity->save()) {
            throw new \RuntimeException('Failed to save model.');
        }

        return $entity;
    }

    public function getById($entityId)
    {
        return Booking::findOrFail($entityId);
    }

    public function delete($entity)
    {
        return $entity->delete();
    }

    public function deleteById($entityId)
    {
        $stream = Booking::findOrFail($entityId);

        return $stream->delete();
    }

    public function getList(SearchCriteriaInterface $searchCriteria): LengthAwarePaginator
    {
        $query = QueryBuilder::for(Booking::class);

        if ($searchCriteria->getWith()) {
            $query->with($searchCriteria->getWith());
        }

        if ($searchCriteria->getFilters()) {
            foreach ($searchCriteria->getFilters() as $field => $value) {
                if (is_array($value)) {
                    $query->whereIn($field, $value);
                } else {
                    $query->where($field, $value);
                }
            }
        }

        if ($searchCriteria->getWhereHas()) {
            foreach ($searchCriteria->getWhereHas() as $relation => $conditions) {
                $query->whereHas($relation, $conditions);
            }
        }

        if ($searchCriteria->getSorts()) {
            foreach ($searchCriteria->getSorts() as $field => $direction) {
                $query->orderBy($field, $direction);
            }
        }

        return $query->paginate($searchCriteria->getPageSize(), ['*'], 'page', $searchCriteria->getPage());
    }

    public function getUserBookingsByType(User $user, string $type, $limit = 10): LengthAwarePaginator
    {
        $currentDate = $this->timezone->date(null, $user->timeZoneId);

        $bookingsCollection = $user->bookings()->where('status', '!=', BookingStatus::CANCELLED);
        if ($type === BookingRepositoryInterface::SCHEDULED_CLASSES) {
            $bookingsCollection->where('slot_end_at', '>', $currentDate);
        } elseif ($type === BookingRepositoryInterface::PAST_CLASSES) {
            $bookingsCollection->where('slot_end_at', '<', $currentDate);
        }

        return $bookingsCollection->latest()->paginate($limit);
    }
}