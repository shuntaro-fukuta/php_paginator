<?php

class Paginator
{
    protected $current_page   = 1;
    protected $items_per_page = 10;
    protected $pager_count    = 5;
    protected $items_count    = 0;
    protected $param_name     = 'page';
    protected $path           = '/';

    public function __construct(int $items_count, ?int $items_per_page, ?int $pager_count)
    {
        $this->setItemsCount($items_count);

        if ($items_per_page !== null) {
            $this->setItemsPerPage($items_per_page);
        }

        if ($pager_count !== null) {
            $this->setPagerCount($pager_count);
        }
    }

    public function setItemsCount(int $items_count): void
    {
        if ($items_count < 1) {
            throw new InvalidArgumentException('Argument must be greater than or equal to 1');
        }

        $this->items_count = $items_count;
    }

    public function getItemsCount(): int
    {
        return $this->items_count;
    }

    public function setItemsPerPage(int $items_per_page): void
    {
        if ($items_per_page < 1) {
            throw new InvalidArgumentException('Argument must be greater than or equal to 1');
        }

        $this->items_per_page = $items_per_page;
    }

    public function getItemsPerPage(): int
    {
        return $this->items_per_page;
    }

    protected function setPagerCount(int $pager_count): void
    {
        if ($pager_count < 1) {
            throw new InvalidArgumentException('Argument must be greater than or equal to 1');
        }

        $this->pager_count = $pager_count;
    }

    public function getPagerCount(): int
    {
        return $this->pager_count;
    }

    public function setCurrentPage(int $page): void
    {
        $last_page = $this->getLastPage();

        if ($page >= 1 && $page <= $last_page) {
            $this->current_page = $page;
        } elseif ($page > $last_page) {
            echo $page;
            exit;
            $this->current_page = $last_page;
        } else {
            $this->current_page = 1;
        }
    }

    public function getCurrenPage(): int
    {
        return $this->current_page;
    }

    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    public function getOffset()
    {
        return ($this->current_page - 1) * $this->items_per_page;
    }

    public function hasPreviousPage(): bool
    {
        return ($this->current_page > 1);
    }

    public function getPreviousPage(): int
    {
        return $this->current_page - 1;
    }

    public function hasNextPage(): bool
    {
        return ($this->current_page < $this->getLastPage());
    }

    public function getNextPage(): int
    {
        return $this->current_page + 1;
    }

    public function getLastPage(): int
    {
        if ($this->items_count === 0) {
            return 1;
        }

        return (int) ceil($this->items_count / $this->items_per_page);
    }

    public function getPageNumbers(): array
    {
        $last_page = $this->getLastPage();
        if ($last_page <= 1) {
            return [];
        }

        $pager_count = $this->pager_count;

        $center  = (int) ceil($pager_count / 2);
        $current = $this->current_page;

        if ($current <= $center) {
            $start = 1;
            $end   = min($pager_count, $last_page);
        } else {
            $end   = min($last_page, $current + ($pager_count - $center));
            $start = max(1, $end - ($pager_count - 1));
        }

        return range($start, $end);
    }

    public function isCurrentPage(int $page): bool
    {
        return ($page === $this->current_page);
    }

    public function buildPageUrl(int $page): string
    {
        if ($page < 1) {
            throw new InvalidArgumentException('Argument must be greater than or equal to 1');
        }

        return "{$this->path}?{$this->param_name}={$page}";
    }

    public function render(): void
    {
        $html = '<ul class="pagination">';
        if ($this->hasPreviousPage()) {
            $html .= '<li><a href="' . $this->buildPageUrl($this->getPreviousPage()) . '">&laquo;</a></li>';
        }

        foreach ($this->getPageNumbers() as $page) {
            $html .= '<li' . ($this->isCurrentPage($page) ? ' class="disabled"' : '') . '><a href="' . $this->buildPageUrl($page) . '">' . $page . '</a></li>';
        }

        if ($this->hasNextPage()) {
            $html .= '<li><a href="' . $this->buildPageUrl($this->getNextPage()) . '">&raquo;</a></li>';
        }
        $html .= '</ul>';

        echo $html;
    }
}

