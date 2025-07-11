<?php

namespace Modules\LanguageLevel\Contracts;

use Modules\Base\Conracts\SearchCriteriaInterface;

/**
 * Interface LanguageLevelRepositoryInterface
 *
 * @package Modules\LanguageLevel\Contracts
 */
interface LanguageLevelRepositoryInterface
{
    public function create(array $data);

    public function save($entity);

    public function getById($entityId);

    public function delete($entity);

    public function deleteById($entityId);

    public function getList(SearchCriteriaInterface $searchCriteria);
}
