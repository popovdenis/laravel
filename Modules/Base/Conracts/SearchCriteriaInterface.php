<?php

namespace Modules\Base\Conracts;

/**
 * Interface SarchCriteriaInterface
 *
 * @package Modules\Base\Conracts
 */
interface SearchCriteriaInterface
{
    public function setFilters(array $filters): self;

    public function getFilters(): array;

    public function setWith(array $filters): self;

    public function getWith(): array;

    public function setWhereHas(array $filters): self;

    public function getWhereHas(): array;

    public function setSorts(array $sorts): self;

    public function getSorts(): array;

    public function setPage(int $page): self;

    public function getPage(): int;

    public function setPageSize(int $pageSize): self;

    public function getPageSize(): int;
}
