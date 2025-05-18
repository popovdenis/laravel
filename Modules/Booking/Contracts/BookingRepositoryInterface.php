<?php

namespace Modules\Booking\Contracts;

use Modules\Base\Conracts\SearchCriteriaInterface;

/**
 * Interface BookingRepositoryInterface
 *
 * @package Modules\Booking\Contracts
 */
interface BookingRepositoryInterface
{
    public function create(array $data);

    public function save($entity);

    public function getById($entityId);

    public function delete($entity);

    public function deleteById($entityId);

    public function getList(SearchCriteriaInterface $searchCriteria);
}