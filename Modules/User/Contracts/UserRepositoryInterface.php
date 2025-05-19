<?php

namespace Modules\User\Contracts;

use Modules\Base\Conracts\SearchCriteriaInterface;
use Modules\User\Models\User;

/**
 * Interface UserRepositoryInterface
 *
 * @package Modules\User\Contracts
 */
interface UserRepositoryInterface
{
    public function create(array $data);

    public function save($entity);

    public function getById($entityId);

    public function delete($entity);

    public function deleteById($entityId);

    public function getList(SearchCriteriaInterface $searchCriteria);

    public function savePreferredTime(User $entity, string $startTime, string $endTime);
}