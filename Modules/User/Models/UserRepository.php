<?php
declare(strict_types=1);

namespace Modules\User\Models;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Base\Conracts\SearchCriteriaInterface;
use Modules\Base\Services\CustomerTimezone;
use Modules\User\Contracts\UserRepositoryInterface;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * Class UserRepository
 *
 * @package Modules\User\Models
 */
class UserRepository implements UserRepositoryInterface
{
    private CustomerTimezone $timezone;

    public function __construct(CustomerTimezone $timezone)
    {
        $this->timezone = $timezone;
    }

    public function create(array $data)
    {
        return User::create($data);
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
        return User::findOrFail($entityId);
    }

    public function delete($entity)
    {
        return $entity->delete();
    }

    public function deleteById($entityId)
    {
        $stream = User::findOrFail($entityId);

        return $stream->delete();
    }

    public function getList(SearchCriteriaInterface $searchCriteria): LengthAwarePaginator
    {
        $query = QueryBuilder::for(User::class);

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

    public function savePreferredTime(User $entity, string $startTime, string $endTime)
    {
        $startTime = $this->timezone->createFromFormat('H:i', $startTime, $entity->timeZoneId);
        $endTime = $this->timezone->createFromFormat('H:i', $endTime, $entity->timeZoneId);

        $entity->setAttribute('preferred_start_time', $startTime);
        $entity->setAttribute('preferred_end_time', $endTime);

        $this->save($entity);
    }
}