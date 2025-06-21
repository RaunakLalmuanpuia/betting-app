<template>
    <div class="max-w-7xl mx-auto px-4 py-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
            v-for="(card, index) in cards"
            :key="index"
            class="flex-1 border-r border-gray-200 pr-6 min-w-[280px]"
        >
            <div class="flex items-start space-x-3 mb-4 w-full">
                <img
                    :alt="card.title"
                    class="w-12 h-12 rounded-lg"
                    :src="card.image"
                />
                <div class="flex-1 flex justify-between items-start gap-2">
                    <p class="font-semibold text-base leading-6 break-words">
                        {{ card.title }}
                    </p>
                </div>
            </div>

            <div class="space-y-4 mb-6">
                <div
                    v-for="(option, idx) in card.options"
                    :key="idx"
                    class="flex items-center justify-between"
                >
                    <span class="text-base font-normal">{{ option.name }}</span>
                    <span class="text-xl font-bold">{{ option.percent }}%</span>
                    <div class="flex rounded-md overflow-hidden text-sm font-medium">
                        <button class="px-3 py-1 text-blue-600 bg-blue-100 hover:bg-blue-200" type="button">Yes</button>
                        <span class="px-1 py-1 text-gray-400 select-none">/</span>
                        <button class="px-3 py-1 text-purple-600 bg-purple-100 hover:bg-purple-200" type="button">No</button>
                    </div>
                </div>
            </div>

            <div class="text-gray-400 text-sm flex items-center justify-between">
                <span>${{ card.amount.toLocaleString() }}</span>
                <button aria-label="Expand" class="text-gray-400 hover:text-gray-600 focus:outline-none" type="button">
                    <i class="fas fa-plus-circle fa-lg"></i>
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
const cards = [
    {
        title: "Club World Cup Group B winner?",
        image: "https://storage.googleapis.com/a1aa/image/6869d582-0c8f-4337-ca8c-a6949c9ba983.jpg",
        amount: 4126,
        options: [
            { name: "Botafogo FR", percent: 49 },
            { name: "PSG", percent: 48 }
        ]
    },
    {
        title: "Club World Cup Group H winner ?",
        image: "https://storage.googleapis.com/a1aa/image/4e41fe39-202e-48f8-6edd-a6a1200dd456.jpg",
        amount: 4401,
        options: [
            { name: "Real Madrid", percent: 67 },
            { name: "Al Hilal", percent: 16 }
        ]
    },
    {
        title: "Club World Cup Group A winner?",
        image: "https://storage.googleapis.com/a1aa/image/4e41fe39-202e-48f8-6edd-a6a1200dd456.jpg",
        amount: 3950,
        options: [
            { name: "Bayern Munich", percent: 70 },
            { name: "Seattle Sounders", percent: 20 }
        ]
    },
    {
        title: "Club World Cup Group H winner ?",
        image: "https://storage.googleapis.com/a1aa/image/4e41fe39-202e-48f8-6edd-a6a1200dd456.jpg",
        amount: 4401,
        options: [
            { name: "Real Madrid", percent: 67 },
            { name: "Al Hilal", percent: 16 }
        ]
    },
    {
        title: "Club World Cup Group H winner ?",
        image: "https://storage.googleapis.com/a1aa/image/4e41fe39-202e-48f8-6edd-a6a1200dd456.jpg",
        amount: 4401,
        options: [
            { name: "Real Madrid", percent: 67 },
            { name: "Al Hilal", percent: 16 }
        ]
    },
    {
        title: "Club World Cup Group H winner ?",
        image: "https://storage.googleapis.com/a1aa/image/4e41fe39-202e-48f8-6edd-a6a1200dd456.jpg",
        amount: 4401,
        options: [
            { name: "Real Madrid", percent: 67 },
            { name: "Al Hilal", percent: 16 }
        ]
    },
];
</script>
