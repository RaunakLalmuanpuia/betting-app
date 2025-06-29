<template>

    <q-page padding>
        <div class="row items-center justify-end">
            <q-select
                dense
                outlined
                v-model="type"
                :options="['Open', 'Settled', 'Closed', 'All']"
                label="Filter Events"
                style="min-width: 200px"
                @update:model-value="changeType"
                class="q-ml-sm"
            />
        </div>

<!--        <Carousel/>-->
        <List :events="events"/>

        <div class="row items-center justify-center">
            <q-pagination

                v-model="page"
                :max="props.pagination.last_page"
                input
                input-class="text-primary"
                color="primary"
                @update:model-value="changePage"
            />
        </div>
    </q-page>
</template>


<script setup>
import BackendLayout from "../../../../Layouts/BackendLayout.vue";
import Carousel from "../../../../Components/EventIndex/Carousel.vue";
import List from "../../../../Components/EventIndex/List.vue";
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';

defineOptions({ layout: BackendLayout });

const props = defineProps({
    events: Array,
    pagination: Object,
    filters: Object,
});

const page = ref(props.pagination.current_page);
const type = ref(props.filters.type || 'Open');

function changeType(newType) {
    router.get(route('player.events.index'), { type: newType }, { preserveState: true });
}

function changePage(p) {
    router.get(route('player.events.index'), { type: type.value, page: p }, { preserveState: true,replace: true });
}
</script>

