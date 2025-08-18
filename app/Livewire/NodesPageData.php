<?php

namespace App\Livewire;

use Illuminate\Support\Collection;
use Livewire\Wireable;

class NodesPageData implements Wireable
{
    public string $key;

    public function __construct(
        public string $start_cursor,
        public string $end_cursor,
        public ?Collection $nodes = null
    ) {
        $this->key = $this->start_cursor.'-'.$this->end_cursor;
    }

    public function toLivewire(): array
    {
        return [
            'start_cursor' => $this->start_cursor,
            'end_cursor' => $this->end_cursor,
        ];
    }

    public static function fromLivewire($value): static
    {
        return new self($value['start_cursor'], $value['end_cursor']);
    }

    public static function fromNodes(Collection $nodes): static
    {
        if ($nodes->isEmpty()) {
            throw new \InvalidArgumentException(
                'Cannot create from an empty collection of nodes.'
            );
        }

        $startCursor = $nodes->first()->path;
        $endCursor = $nodes->last()->path;

        return new self($startCursor, $endCursor, $nodes);
    }
}
