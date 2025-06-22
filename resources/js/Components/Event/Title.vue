<template>
    <div class="bg-white font-sans text-black">
        <div class="max-w-4xl mx-auto p-6">
            <!-- Header -->
            <header class="flex items-center space-x-4 mb-6">
                <img
                    :src="`/storage/${event.image}`"
                    class="w-20 h-20 rounded-md object-cover flex-shrink-0"
                    width="80"
                    height="80"
                />
                <p class="text-2xl font-extrabold flex-1 leading-tight">
                    {{ event.title }}
                </p>
                <button
                    aria-label="Share"
                    class="text-black text-xl hover:text-gray-700 focus:outline-none"
                    @click="share"
                >
                    <i class="fas fa-share-alt"></i>
                </button>
            </header>

            <!-- Chance Section -->
            <section class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-1">
                    <span class="text-4xl font-extrabold leading-none">{{ chance }}</span>
                    <span class="text-lg font-semibold">chance</span>
                    <div class="flex items-center ml-2 space-x-1 text-green-600 font-semibold text-sm">
                        <i class="fas fa-caret-up"></i>
                        <span>{{ change }}</span>
                    </div>
                    <button
                        aria-label="Info"
                        title="Change in chance"
                        class="text-gray-400 hover:text-gray-600 text-xs ml-1 focus:outline-none"
                    >
                        <i class="fas fa-info-circle"></i>
                    </button>
                </div>
                <div>
                    <span class="text-green-600 font-extrabold text-xl">{{ source }}</span>
                </div>
            </section>

            <!-- Chart -->
            <section class="relative mb-6">
                <img
                    :src="chartImage"
                    alt="Line chart showing chance over time"
                    class="w-full h-auto"
                    width="900"
                    height="300"
                />
            </section>

            <!-- Time Range Buttons -->
            <section class="flex justify-center space-x-6 text-green-600 font-semibold text-sm mb-6 select-none">
                <button v-for="range in timeRanges" :key="range" @click="setRange(range)" :class="{
                      'bg-green-600 text-white rounded-md px-3 py-1': selectedRange === range,
                      'hover:underline': selectedRange !== range
                    }">
                    {{ range }}
                </button>
            </section>




            <!-- Year and Volume -->
            <section class="flex items-center justify-between border-t border-b border-gray-200 py-3 text-sm">
                <div class="flex items-center space-x-1 cursor-pointer select-none" @click="toggleYearDropdown">
                    <span>{{ selectedYear }}</span>
                    <i class="fas fa-chevron-down text-xs"></i>
                </div>
                <div class="flex items-center space-x-2 text-gray-600">
                    <span>${{ volume.toLocaleString() }} vol</span>
                    <button aria-label="Filter" class="text-black hover:text-gray-700 focus:outline-none">
                        <i class="fas fa-sliders-h"></i>
                    </button>
                </div>
            </section>

            <!-- Order Book -->
            <section class="border-b border-gray-200 py-4 flex items-center justify-between cursor-pointer select-none">
                <div class="flex items-center space-x-2">
                    <span class="font-semibold text-base">Order book</span>
                    <button aria-label="Order book info" class="text-gray-400 hover:text-gray-600 text-xs focus:outline-none">
                        <i class="fas fa-info-circle"></i>
                    </button>
                </div>
                <button aria-label="Expand order book" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                    <i class="fas fa-chevron-down"></i>
                </button>
            </section>
        </div>
    </div>
    <div class="bg-white text-gray-900 min-h-screen">
        <main class="max-w-4xl mx-auto px-6 py-10">
            <!-- Header -->
            <header class="flex justify-between items-center mb-6">
                <p class="text-3xl font-semibold leading-tight">Rules summary</p>
                <a href="#" class="text-base font-normal hover:underline">Learn more</a>
            </header>

            <!-- Description -->
            <section class="text-lg font-normal leading-relaxed mb-6">
                <p class="mb-4">
                    {{event.description}}
                </p>
            </section>

            <!-- Buttons -->
            <section class="mb-8">
                <button
                    type="button"
                    class="inline-block bg-gray-200 text-gray-900 font-semibold text-base rounded-lg px-5 py-2.5 mr-4 hover:bg-gray-300 transition"
                >
                    View full rules
                </button>
                <button
                    type="button"
                    class="inline-block bg-gray-200 text-gray-900 font-semibold text-base rounded-lg px-5 py-2.5 hover:bg-gray-300 transition"
                >
                    Market Rules
                </button>
            </section>

            <hr class="border-t border-gray-200 mb-4" />

            <!-- Expandable Section -->
            <section>
                <button
                    type="button"
                    class="w-full flex items-center justify-between text-gray-900 text-lg font-normal py-3"
                    aria-expanded="false"
                >
                    <div class="flex items-center space-x-3">
                        <i class="far fa-calendar-alt text-2xl"></i>
                        <span>Timeline and payout</span>
                    </div>
                    <i class="fas fa-chevron-down text-gray-400"></i>
                </button>
                <hr class="border-t border-gray-200" />
            </section>
        </main>
    </div>
</template>

<script setup>
import { ref } from 'vue'

const props = defineProps({
    event: Object,
})


const title = ref('New US-Iran nuclear deal this year?')
const headerImage = ref('https://storage.googleapis.com/a1aa/image/f2d2e0c8-ec6e-4fba-5690-1eaa1b39a20b.jpg')
const headerAlt = ref('US-Iran nuclear deal image')
const chartImage = ref('https://storage.googleapis.com/a1aa/image/68f2dc21-da37-4b4f-c64a-5ca2103e24a1.jpg')

const chance = ref('44%')
const change = ref('0.3')
const source = ref('WeBet')
const volume = ref(143352)
const selectedYear = ref(2026)

const timeRanges = ['6H', '1D', '1W', '1M', 'ALL']
const selectedRange = ref('ALL')

const share = () => {
    alert('Share functionality coming soon!')
}

const setRange = (range) => {
    selectedRange.value = range
    console.log('Selected range:', range)
}

const toggleYearDropdown = () => {
    console.log('Toggle year dropdown')
}
</script>

