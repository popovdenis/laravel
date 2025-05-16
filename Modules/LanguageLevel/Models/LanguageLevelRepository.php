<?php
declare(strict_types=1);

namespace Modules\LanguageLevel\Models;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Base\Conracts\SearchCriteriaInterface;
use Modules\LanguageLevel\Contracts\LanguageLevelRepositoryInterface;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * Class LanguageLevelRepository
 *
 * @package Modules\LanguageLevel\Models
 */
class LanguageLevelRepository implements LanguageLevelRepositoryInterface
{
    public function save($entity)
    {
        $entity->save();

        return $entity;
    }

    public function getById($entityId)
    {
        return LanguageLevel::findOrFail($entityId);
    }

    public function delete($entity)
    {
        return $entity->delete();
    }

    public function deleteById($entityId)
    {
        $stream = LanguageLevel::findOrFail($entityId);

        return $stream->delete();
    }

    public function getList(SearchCriteriaInterface $searchCriteria): LengthAwarePaginator
    {
        $query = QueryBuilder::for(LanguageLevel::class);

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
}
