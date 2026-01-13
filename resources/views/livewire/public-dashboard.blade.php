<div class="grid grid-cols-1 gap-4 lg:grid-cols-2 lg:gap-8">
    @foreach ($this->getHeaderWidgets() as $widget)
        <div class="col-span-1">
            @livewire($widget)
        </div>
    @endforeach
</div>

