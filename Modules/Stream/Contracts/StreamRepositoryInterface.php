<?php

namespace Modules\Stream\Contracts;

use Modules\Base\Conracts\SearchCriteriaInterface;

/**
 * Interface StreamRepositoryInterface
 *
 * @package Modules\Stream\Contracts
 */
interface StreamRepositoryInterface
{
    public function save($entity);

    public function getById($entityId);

    public function delete($entity);

    public function deleteById($entityId);

    public function getList(SearchCriteriaInterface $searchCriteria);
}
