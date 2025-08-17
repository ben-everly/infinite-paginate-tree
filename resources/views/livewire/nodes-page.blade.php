@php
    use App\Livewire\Nodes;
@endphp
<div>
    @foreach ($this->nodes as $node)
        @php
            $depth = substr_count($node->path, '/') - 2;
        @endphp
        <div class="p-2 my-2 flex gap-2" style="margin-left: {{ $depth * 20 }}px;">
            <h3>Node {{ $node->id }}</h3>
            <button class="border" wire:click="createChild({{ $node->id }})">
                Create Child
            </button>
        </div>
    @endforeach
</div>
