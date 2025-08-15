<?php

namespace App\Livewire;

use App\Models\Node;
use Illuminate\Pagination\Cursor;
use Illuminate\Support\Collection;
use Livewire\Component;

class Nodes extends Component
{
    /** @var Collection<int, NodesPageData> */
    public Collection $pages;

    public string $nextCursor = '';

    public bool $morePages = true;

    public function mount()
    {
        $this->pages = collect();
        $this->addPage();
    }

    public function addPage()
    {
        if (! $this->morePages) {
            return;
        }
        $nodes = Node::orderBy('path')->cursorPaginate(
            10, ['*'], 'path',
            Cursor::fromEncoded($this->nextCursor)
        );
        if ($this->morePages = $nodes->hasMorePages()) {
            $this->nextCursor = $nodes->nextCursor()->encode();
        }

        $items = collect($nodes->items());
        if ($items->isNotEmpty()) {
            $this->pages->push(NodesPageData::fromNodes($items));
        }
    }

    public function createNode(?int $parentId = null)
    {
        $node = Node::create(['parent_id' => $parentId]);
        if (! $this->morePages) {
            $this->pages->push(NodesPageData::fromNodes(collect([$node])));
        }
    }
}
