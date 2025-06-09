<template>
    <q-page>
        <q-card class="bg-transparent no-shadow no-border">
            <q-card-section class="row">
                <div class="col-12">
                    <q-space />
                    <q-select
                        dense
                        outlined
                        style="min-width: 200px"
                        v-model="type"
                        :options="['All', 'Open', 'Settled','Closed']"
                        class="float-right"
                        label="Category"
                    />
                </div>
            </q-card-section>

            <q-card-section class="q-mx-sm bg-transparent"  >
                <div class="row q-col-gutter-lg">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12" v-for="data in getData2" :key="data.title">
                        <q-card  class="text-white bg-grey-6">
                            <q-img :src="data.img">
                                <template #loading>
                                    <div class="text-subtitle1 text-white">Loading...</div>
                                </template>
                            </q-img>

                            <q-separator />

                            <q-card-section class="text-h5 text-center">
                                {{ data.title }}
                            </q-card-section>

                            <q-card-section class="text-justify">
                                <div>{{ data.text }}</div>
                            </q-card-section>

                            <q-card-actions>
                                <q-btn
                                    icon="remove_red_eye"
                                    class="bg-transparent text-capitalize"
                                    flat
                                    label="200 Views"
                                />
                                <q-space />
                                <q-btn icon="chat_bubble" class="bg-transparent" flat label="56" />
                            </q-card-actions>
                        </q-card>
                    </div>
                </div>
            </q-card-section>

            <q-card-actions align="center">
                <q-pagination
                    v-model="page"
                    :min="currentPage"
                    :max="Math.ceil(getData().length / totalPages)"
                    :input="true"
                    input-class="text-orange-10"
                />
            </q-card-actions>
        </q-card>
    </q-page>
</template>

<script setup>
import BackendLayout from "../../../../Layouts/BackendLayout.vue";


import { ref, computed } from 'vue'

defineOptions({layout:BackendLayout})

const type = ref('All')
const page = ref(1)
const currentPage = 1
const totalPages = 4

const cards_data = [
    {
        img: 'https://placeimg.com/500/300/nature?t=' + Math.random(),
        type: 'Open',
        text: 'Lorem ipsum dolor sit amet...',
        title: 'Title 1'
    },
    {
        img: 'https://placeimg.com/500/300/nature?t=' + Math.random(),
        type: 'Closed',
        text: 'Lorem ipsum dolor sit amet...',
        title: 'Title 2'
    },
    {
        img: 'https://placeimg.com/500/300/nature?t=' + Math.random(),
        type: 'Settled',
        text: 'Lorem ipsum dolor sit amet...',
        title: 'Title 3'
    },
    {
        img: 'https://placeimg.com/500/300/nature?t=' + Math.random(),
        type: 'Settled',
        text: 'Lorem ipsum dolor sit amet...',
        title: 'Title 4'
    },
    {
        img: 'https://placeimg.com/500/300/nature?t=' + Math.random(),
        type: 'Closed',
        text: 'Lorem ipsum dolor sit amet...',
        title: 'Title 5'
    },
    {
        img: 'https://placeimg.com/500/300/nature?t=' + Math.random(),
        type: 'Open',
        text: 'Lorem ipsum dolor sit amet...',
        title: 'Title 6'
    },
]

function getData() {
    if (type.value === 'All') {
        return cards_data
    }
    return cards_data.filter((item) => item.type.toLowerCase() === type.value.toLowerCase())
}

const getData2 = computed(() => {
    const start = (page.value - 1) * totalPages
    const end = start + totalPages
    return getData().slice(start, end)
})
</script>

