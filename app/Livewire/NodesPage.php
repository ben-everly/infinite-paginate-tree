<?php

namespace App\Livewire;

use App\Models\Node;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

#[On(NodesPage::REFRESH_EVENT.'{pageIndex}')]
class NodesPage extends Component
{
    public const REFRESH_EVENT = 'nodes_page.refresh.';

    public NodesPageData $pageData;

    public int $pageIndex;

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

    public function createChild(int $parentId)
    {
        $this->dispatch(Nodes::CREATE_NODE_EVENT, $parentId);
    }
}
