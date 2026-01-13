<div class="mx-auto lg:max-w-[50vw]">
    <div class="grid grid-cols-1 gap-4">
        @foreach ($this->getHeaderWidgets() as $widget)
            <div class="col-span-1">
                @livewire($widget)
            </div>
        @endforeach
    </div>
</div>

