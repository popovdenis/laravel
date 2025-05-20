<?php

namespace Modules\Booking\Contracts;

use Modules\Base\Conracts\SearchCriteriaInterface;
use Modules\User\Models\User;

/**
 * Interface BookingRepositoryInterface
 *
 * @package Modules\Booking\Contracts
 */
interface BookingRepositoryInterface
{
    const SCHEDULED_CLASSES = 'scheduled';
    const PAST_CLASSES = 'past';

    public function create(array $data);

    public function save($entity);

    public function getById($entityId);

    public function delete($entity);

    public function deleteById($entityId);

    public function getList(SearchCriteriaInterface $searchCriteria);

    public function getUserBookingsByType(User $user, string $type, $limit = 10);
}