<?php
declare(strict_types=1);

namespace Modules\Stream\Models;

use Modules\Base\Conracts\SearchCriteriaInterface;
use Modules\Stream\Contracts\StreamRepositoryInterface;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

/**
 * Class StreamRepository
 *
 * @package Modules\Stream\Models
 */
class StreamRepository implements StreamRepositoryInterface
{
    public function save($entity)
    {
        $entity->save();

        return $entity;
    }

    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $query = QueryBuilder::for(Stream::class);

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

    public function getById($entityId)
    {
        return Stream::findOrFail($entityId);
    }

    public function delete($entity)
    {
        return $entity->delete();
    }

    public function deleteById($entityId)
    {
        $stream = Stream::findOrFail($entityId);

        return $stream->delete();
    }
}
