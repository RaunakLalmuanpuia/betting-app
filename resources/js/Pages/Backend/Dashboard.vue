<template>
    <q-page padding>
        <q-card class="bg-transparent no-shadow no-border" bordered>
            <q-card-section class="q-pa-none">
                <div class="row q-col-gutter-sm">
                    <div
                        v-for="(item, index) in items"
                        :key="index"
                        class="col-md-3 col-sm-12 col-xs-12"
                    >
                        <q-item :style="`background-color: ${item.color1}`" class="q-pa-none">
                            <q-item-section class="q-pa-md q-ml-none text-white">
                                <q-item-label class="text-white text-h6 text-weight-bolder">
                                    {{ item.value }}
                                </q-item-label>
                                <q-item-label>{{ item.title }}</q-item-label>
                            </q-item-section>

                            <q-item-section side class="q-mr-md text-white">
                                <q-icon :name="item.icon" color="white" size="44px" />
                            </q-item-section>
                        </q-item>
                    </div>
                </div>
            </q-card-section>
        </q-card>
    </q-page>

</template>

<script setup>

import BackendLayout from "../../Layouts/BackendLayout.vue";

defineOptions({layout:BackendLayout})

const items = [
    {
        title: 'Monthly Income',
        icon: 'attach_money', // Material icon equivalent of "fa-dollar-sign"
        value: '$ 20k',
        color1: '#546bfa',
        color2: '#3e51b5',
    },
    {
        title: 'Weekly Sales',
        icon: 'bar_chart', // Material icon equivalent of "fa-chart-bar"
        value: '20',
        color1: '#3a9688',
        color2: '#3e51b5',
    },
    {
        title: 'New Customers',
        icon: 'show_chart', // Material icon equivalent of "chart-line"
        value: '321',
        color1: '#7cb342',
        color2: '#3e51b5',
    },
    {
        title: 'Active Users',
        icon: 'person', // Already a Material icon
        value: '82',
        color1: '#f88c2b',
        color2: '#3e51b5',
    },
]
</script>
<style scoped>

</style>
