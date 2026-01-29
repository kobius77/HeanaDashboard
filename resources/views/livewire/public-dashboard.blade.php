<div class="min-h-screen flex flex-col items-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full lg:max-w-[60vw] mx-auto space-y-8">
        <div class="flex justify-between items-center bg-white p-6 rounded-none shadow-sm border border-gray-100">
            <h1 class="text-xl font-semibold text-gray-800">{{ __('Egg Production Dashboard') }}</h1>
            <a href="{{ route('heatmap') }}" class="inline-flex items-center gap-2 text-sm font-medium text-amber-600 hover:text-amber-700 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3"/><path d="M3 9h18"/><path d="M3 15h18"/><path d="M9 3v18"/><path d="M15 3v18"/></svg>
                {{ __('View Heatmap') }}
            </a>
        </div>

        <div class="space-y-4">
            @foreach ($widgets as $widget)
                <div wire:key="widget-wrapper-{{ $widget->id }}">
                    <livewire:dynamic-component :component="$widget->widget_class" :key="'widget-' . $widget->id" />
                </div>
            @endforeach
        </div>
        
        <div class="mt-4 flex items-center justify-between text-sm text-gray-500">
             <a href="{{ route('filament.admin.pages.dashboard') }}" class="hover:text-gray-600 transition-colors">Administration</a>
             <span class="text-gray-200">|</span>
             <a href="{{ route('heatmap') }}" class="hover:text-gray-600 transition-colors">View Heatmap</a>
        </div>
    </div>
</div>