
<template>
    <div class="bg-white p-6 min-h-screen">
        <div class="max-w-sm mx-auto bg-white rounded-2xl p-6 space-y-6 shadow-sm">
            <!-- Header -->
            <div class="flex items-center space-x-4 w-full">
                <img
                    alt="Image showing US and Iran flags with a nuclear symbol in the center on a wooden background"
                    class="w-16 h-16 rounded-xl object-cover flex-shrink-0"
                    src="https://storage.googleapis.com/a1aa/image/c0916ef1-0174-4a1b-98e9-1cbf056b4fd5.jpg"
                />
                <div class="flex-1 min-w-0">
                    <p class="text-base text-black leading-tight">
                        New US-Iran nuclear deal this year?
                    </p>
                    <a class="text-blue-600 text-lg font-normal leading-tight" href="#">
                        Buy Yes
                    </a>
                </div>
            </div>



            <!-- Buy/Sell and Currency Selector -->
            <div class="flex items-center justify-between">
                <div class="flex space-x-3">
                    <button
                        class="px-4 py-2 rounded-full border border-green-300 text-green-600 font-medium text-base leading-tight"
                    >
                        Buy
                    </button>
                    <button
                        class="px-4 py-2 rounded-full border border-gray-300 text-black font-normal text-base leading-tight"
                    >
                        Sell
                    </button>
                </div>
                <div
                    class="flex items-center space-x-1 text-black font-normal text-base leading-tight cursor-pointer select-none"
                >
                    <span>Dollars</span>
                    <i class="fas fa-chevron-down text-base"></i>
                </div>
            </div>

            <!-- Price Buttons -->
            <div class="flex space-x-4">
                <button
                    class="flex-1 bg-blue-600 rounded-xl py-4 text-white text-lg font-normal leading-tight flex justify-center items-center"
                >
                    <span>Yes</span>
                    <span class="ml-1 text-xl font-semibold">45</span>
                    <span class="ml-0.5 text-lg font-semibold">¢</span>
                </button>
                <button
                    class="flex-1 bg-purple-100 rounded-xl py-4 text-purple-500 text-lg font-normal leading-tight flex justify-center items-center"
                >
                    <span>No</span>
                    <span class="ml-1 text-xl font-semibold">56</span>
                    <span class="ml-0.5 text-lg font-semibold">¢</span>
                </button>
            </div>

            <!-- Amount Display -->
            <div class="border border-gray-300 rounded-xl p-4 flex justify-between items-center">
                <span class="text-base font-medium text-black">Amount</span>
                <span class="text-3xl font-extrabold text-black">$200.00</span>
            </div>

            <!-- Odds and Payout -->
            <div class="flex justify-between items-center">
                <div class="flex flex-col space-y-2">
                    <span class="text-gray-500 text-base font-normal">Odds</span>
                    <span
                        class="text-gray-500 text-base font-normal flex items-center cursor-default select-none"
                    >
            Payout if Yes
            <i class="fas fa-chevron-down ml-1 text-sm"></i>
          </span>
                </div>
                <div class="flex flex-col items-end space-y-2">
                    <span class="text-black text-lg font-semibold">45% chance</span>
                    <span class="text-green-500 text-4xl font-extrabold">$445</span>
                </div>
            </div>

            <!-- Signup Button -->
            <button class="w-full bg-green-500 text-white text-lg font-normal rounded-xl py-4">
                Sign up to trade
            </button>
        </div>
    </div>


<!--    <div class="bg-white p-6">-->
<!--        <div class="max-w-sm mx-auto bg-white rounded-2xl p-5 space-y-5 shadow-sm">-->
<!--            <div class="flex items-center space-x-4 w-full">-->
<!--                <img-->
<!--                    alt="Image showing US and Iran flags with a nuclear symbol in the center on a wooden background"-->
<!--                    class="w-16 h-16 rounded-xl object-cover flex-shrink-0"-->
<!--                    src="https://storage.googleapis.com/a1aa/image/c0916ef1-0174-4a1b-98e9-1cbf056b4fd5.jpg"-->
<!--                />-->
<!--                <div class="flex-1 min-w-0">-->
<!--                    <p class="text-base text-black leading-tight">-->
<!--                        New US-Iran nuclear deal this year?-->
<!--                    </p>-->
<!--                    <a class="text-blue-600 text-lg font-normal leading-tight" href="#">-->
<!--                        Buy Yes-->
<!--                    </a>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="flex items-center justify-between">-->
<!--                <div class="flex space-x-3">-->
<!--                    <button class="px-5 py-2 rounded-full border border-transparent bg-green-100 text-green-600 font-medium">-->
<!--                        Buy-->
<!--                    </button>-->
<!--                    <button class="px-5 py-2 rounded-full border border-gray-300 text-black font-normal">-->
<!--                        Sell-->
<!--                    </button>-->
<!--                </div>-->
<!--                <button aria-label="Currency dropdown" class="flex items-center space-x-1 text-black font-normal text-base">-->
<!--                 <span>-->
<!--                  Dollars-->
<!--                 </span>-->
<!--                    <i class="fas fa-chevron-down text-black">-->
<!--                    </i>-->
<!--                </button>-->
<!--            </div>-->
<!--            <div class="flex space-x-4">-->
<!--                <button class="flex-1 bg-blue-600 rounded-xl py-4 text-white text-xl font-normal flex justify-center items-center space-x-2">-->
<!--     <span>-->
<!--      Yes-->
<!--     </span>-->
<!--                    <span class="text-2xl font-semibold">-->
<!--      45¢-->
<!--     </span>-->
<!--                </button>-->
<!--                <button class="flex-1 bg-purple-100 rounded-xl py-4 text-purple-500 text-xl font-normal flex justify-center items-center space-x-2">-->
<!--     <span>-->
<!--      No-->
<!--     </span>-->
<!--                    <span class="text-2xl font-semibold">-->
<!--      56¢-->
<!--     </span>-->
<!--                </button>-->
<!--            </div>-->
<!--            <div class="border border-gray-300 rounded-xl p-4 flex justify-between items-center">-->
<!--    <span class="text-gray-900 font-semibold text-base">-->
<!--     Amount-->
<!--    </span>-->
<!--                <span class="text-gray-400 font-extrabold text-3xl">-->
<!--     $0-->
<!--    </span>-->
<!--            </div>-->
<!--            <button class="w-full bg-green-600 text-white text-lg font-normal py-4 rounded-xl">-->
<!--                Sign up to trade-->
<!--            </button>-->
<!--        </div>-->
<!--    </div>-->
</template>

<script setup>
// Add interactivity here if needed, e.g., for Buy/Sell toggle, selected currency, etc.
</script>


<style scoped>

</style>



