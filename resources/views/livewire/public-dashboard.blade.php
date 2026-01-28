<div class="min-h-screen flex flex-col items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full lg:max-w-[60vw] mx-auto space-y-4">
        @foreach ($widgets as $widget)
            @livewire($widget->widget_class)
        @endforeach
    </div>
</div>
