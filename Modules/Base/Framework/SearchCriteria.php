<?php
declare(strict_types=1);

namespace Modules\Base\Framework;

use Modules\Base\Conracts\SearchCriteriaInterface;

/**
 * Class SearchCriteria
 *
 * @package Modules\Base\Framework
 */
class SearchCriteria implements SearchCriteriaInterface
{
    protected array $filters = [];
    protected array $with = [];
    protected array $whereHas = [];
    protected array $sorts = [];
    protected int $page = 1;
    protected int $pageSize = 20;

    public function __construct(array $data = [])
    {
        $this->filters = $data['filters'] ?? [];
        $this->sorts = $data['sorts'] ?? [];
        $this->page = $data['page'] ?? 1;
        $this->pageSize = $data['pageSize'] ?? 20;
    }

    public function setFilters(array $filters): self
    {
        $this->filters = $filters;
        return $this;
    }

    public function getFilters(): array
    {
        return $this->filters;
    }

    public function setWith(array $filters): self
    {
        $this->with = $filters;
        return $this;
    }

    public function getWith(): array
    {
        return $this->with;
    }

    public function setWhereHas(array $filters): self
    {
        $this->whereHas = $filters;
        return $this;
    }

    public function getWhereHas(): array
    {
        return $this->whereHas;
    }

    public function setSorts(array $sorts): self
    {
        $this->sorts = $sorts;
        return $this;
    }

    public function getSorts(): array
    {
        return $this->sorts;
    }

    public function setPage(int $page): self
    {
        $this->page = $page;
        return $this;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function setPageSize(int $pageSize): self
    {
        $this->pageSize = $pageSize;
        return $this;
    }

    public function getPageSize(): int
    {
        return $this->pageSize;
    }
}
