<?php

namespace App\Livewire;

use App\Models\Node;
use Illuminate\Pagination\Cursor;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;
use Livewire\Component;

class Nodes extends Component
{
    public const CREATE_NODE_EVENT = 'node.create';

    /** @var array<int, NodesPageData> */
    public array $pages = [];

    public string $nextCursor = '';

    public bool $morePages = true;

    public function mount()
    {
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

        collect($nodes->items())
            ->whenNotEmpty(fn (Collection $items) => (
                $this->pages[] = NodesPageData::fromNodes($items)
            ));
    }

    #[On(self::CREATE_NODE_EVENT)]
    public function createNode(?int $parentId = null)
    {
        $node = Node::create(['parent_id' => $parentId]);

        foreach ($this->pages as $index => $page) {
            if (
                $node->path >= $page->start_cursor
                    && $node->path <= $page->end_cursor
            ) {
                $this->dispatch(NodesPage::REFRESH_EVENT.$index);
            } elseif ($node->path > $page->end_cursor) {
                $nextPageLoaded = array_key_exists($index + 1, $this->pages);
                if ($nextPageLoaded
                    && $node->path < $this->pages[$index + 1]->start_cursor
                    || (! $nextPageLoaded && ! $this->morePages)
                ) {
                    array_splice($this->pages, $index + 1, 0, [
                        NodesPageData::fromNodes(collect([$node])),
                    ]);
                }
            }
        }
    }
}
