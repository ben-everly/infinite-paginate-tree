<?php

namespace App\Livewire;

use App\Models\Node;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;

class NodesPage extends Component
{
    public NodesPageData $pageData;

    #[Computed]
    public function nodes(): Collection
    {
        if ($this->pageData->nodes !== null) {
            return $this->pageData->nodes;
        }

        return Node::query()
            ->orderBy('path')
            ->where('path', '>=', $this->pageData->start_cursor)
            ->where('path', '<=', $this->pageData->end_cursor)
            ->get();
    }
}
