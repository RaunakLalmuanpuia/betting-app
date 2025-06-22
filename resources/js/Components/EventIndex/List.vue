<template>

    <div class="max-w-7xl mx-auto px-4 py-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
            v-for="(event, index) in events"
            :key="index"
            class="flex-1 border-r border-gray-200 pr-6 min-w-[280px]"
        >
            <div
                role="button"
                tabindex="0"
                @click="$inertia.get(route('player.events.show', event))"
                class="flex items-start space-x-3 mb-4 w-full"
            >
                <img
                    class="w-12 h-12 rounded-lg"
                    :src="`/storage/${event.img}`"
                    alt="Event image"
                />
                <div class="flex-1 flex justify-between items-start gap-2">
                    <p class="font-semibold text-base leading-6 break-words">
                        {{ event.event_name }}
                    </p>
                </div>
            </div>


            <div class="space-y-4 mb-6">
                <div
                    v-for="(option, idx) in event.options"
                    :key="idx"
                    class="flex items-center justify-between"
                >
                    <span class="text-base font-normal">{{ option.label }}</span>
                    <span class="text-xl font-bold">{{ option.odds }}x</span>
                    <div class="flex rounded-md overflow-hidden text-sm font-medium">

                        <span class="px-1 py-1 text-gray-400 select-none">₹{{ option.total_amount }}</span>

                    </div>
                </div>
            </div>

            <div class="text-gray-400 text-sm flex items-center justify-between">
                <span>₹{{ getTotalAmount(event.options) }}</span>
                <button aria-label="Expand" class="text-gray-400 hover:text-gray-600 focus:outline-none" type="button">
                    <i class="fas fa-plus-circle fa-lg"></i>
                </button>
            </div>
        </div>
    </div>

</template>

<script setup>

const props = defineProps({
    events: Object,
})

function getTotalAmount(options) {
    return options.reduce((total, opt) => total + (opt.total_amount || 0), 0);
}

</script>
