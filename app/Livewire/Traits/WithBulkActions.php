<?php

namespace App\Livewire\Traits;

trait WithBulkActions
{
    public array $selected = [];
    public bool $selectAll = false;

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selected = $this->getSelectedPageItems();
        } else {
            $this->selected = [];
        }
    }

    public function updatedSelected()
    {
        $pageIds = $this->getSelectedPageItems();
        if (count($pageIds) > 0 && count(array_intersect($pageIds, $this->selected)) === count($pageIds)) {
            $this->selectAll = true;
        } else {
            $this->selectAll = false;
        }
    }

    public function resetSelection()
    {
        $this->selected = [];
        $this->selectAll = false;
    }

    /**
     * Override this method in the component to return array of IDs present on current view/page.
     */
    public function getSelectedPageItems(): array
    {
        return [];
    }
}
