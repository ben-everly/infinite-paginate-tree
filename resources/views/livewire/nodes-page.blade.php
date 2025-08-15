@php
    use App\Livewire\Nodes;
@endphp
<div>
    @foreach ($this->nodes as $node)
        <div class="border p-2 my-2 flex gap-2">
            <h3>Node {{ $node->id }}</h3>
        </div>
    @endforeach
</div>
