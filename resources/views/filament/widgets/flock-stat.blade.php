<div
    x-data="{
        stats: @js($stats),
        currentIndex: 0,
        nextIndex() {
            this.currentIndex = (this.currentIndex + 1) % this.stats.length;
        },
        init() {
            if (this.stats.length > 1) {
                setInterval(() => this.nextIndex(), 4000);
            }
        }
    }"
    class="fi-wi-stats-overview-stat relative rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10"
>
    <div class="grid gap-y-2">
        <!-- Headline -->
        <div class="flex items-center gap-x-2">
            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                {{ __('Flock Stats') }}
            </span>
        </div>

        <!-- Value Container -->
        <div class="relative h-9 flex items-center">
            <template x-for="(stat, index) in stats" :key="index">
                <div
                    x-show="currentIndex === index"
                    x-transition:enter="transition ease-out duration-500"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-300 absolute"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-2"
                    class="text-3xl font-semibold tracking-tight leading-9"
                    :class="stat.color"
                    x-text="stat.value"
                ></div>
            </template>
        </div>

        <!-- Subline Container -->
        <div class="relative h-5 flex items-center">
            <template x-for="(stat, index) in stats" :key="index">
                <div
                    x-show="currentIndex === index"
                    x-transition:enter="transition ease-out duration-500 delay-100"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-300 absolute"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="text-sm text-gray-500 dark:text-gray-400 leading-5"
                    x-text="stat.label"
                ></div>
            </template>
        </div>
    </div>
</div>