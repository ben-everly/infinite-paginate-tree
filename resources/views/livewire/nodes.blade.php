<div>
    <button class="border" wire:click="createNode">Add Node</button>
    @foreach ($this->pages as $index => $page)
        <livewire:nodes-page :wire:key="$index" :pageData="$page" />
    @endforeach
    @if ($this->morePages)
        <button class="border" wire:click="addPage">Load More</button>
    @endif
</div>
