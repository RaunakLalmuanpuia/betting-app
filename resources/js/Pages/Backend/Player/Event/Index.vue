<template>

    <q-page padding>
        <Carousel/>
        <List/>
    </q-page>
<!--    <q-page padding>-->
<!--        <q-card class="bg-transparent no-shadow no-border">-->
<!--            &lt;!&ndash; Filter Dropdown &ndash;&gt;-->
<!--            <q-card-section class="q-pa-none q-px-md q-mb-md">-->
<!--                <div class="row items-center justify-end">-->
<!--                    <q-select-->
<!--                        dense-->
<!--                        outlined-->
<!--                        v-model="type"-->
<!--                        :options="['Open', 'Settled', 'Closed', 'All']"-->
<!--                        label="Filter Events"-->
<!--                        style="min-width: 200px"-->
<!--                        @update:model-value="changeType"-->
<!--                        class="q-ml-sm"-->
<!--                    />-->
<!--                </div>-->
<!--            </q-card-section>-->

<!--            &lt;!&ndash; Events Listing &ndash;&gt;-->
<!--            <q-card-section class="q-px-md">-->
<!--                <div class="row q-col-gutter-lg">-->
<!--                    <div-->
<!--                        class="col-12 col-md-6 col-lg-4"-->
<!--                        v-for="event in props.events"-->
<!--                        :key="event.id"-->
<!--                    >-->
<!--                        <q-card class="bg-white shadow-2 rounded-xl column justify-between">-->
<!--                            &lt;!&ndash; Image &ndash;&gt;-->
<!--                            <q-img-->
<!--                                :src="`/storage/${event.img}`"-->
<!--                                height="200px"-->
<!--                                class="rounded-t-xl"-->
<!--                                spinner-color="primary"-->
<!--                                :ratio="16/9"-->
<!--                            >-->
<!--                                <template #loading>-->
<!--                                    <div class="absolute-full flex flex-center text-white text-subtitle2">-->
<!--                                        Loading...-->
<!--                                    </div>-->
<!--                                </template>-->
<!--                            </q-img>-->

<!--                            &lt;!&ndash; Event Title &ndash;&gt;-->
<!--                            <q-card-section class="text-h6 text-center text-primary q-pt-md">-->
<!--                                {{ event.event_name }}-->
<!--                            </q-card-section>-->

<!--                            &lt;!&ndash; Event Metadata &ndash;&gt;-->
<!--                            <q-card-section class="q-px-md q-pt-none">-->
<!--                                <q-list bordered separator>-->
<!--                                    <q-item dense>-->
<!--                                        <q-item-section avatar><q-icon name="info" /></q-item-section>-->
<!--                                        <q-item-section><strong>Status:</strong> {{ event.type }}</q-item-section>-->
<!--                                    </q-item>-->
<!--                                    <q-item dense>-->
<!--                                        <q-item-section avatar><q-icon name="event" /></q-item-section>-->
<!--                                        <q-item-section><strong>Starts:</strong> {{ event.start_time }}</q-item-section>-->
<!--                                    </q-item>-->
<!--                                    <q-item dense>-->
<!--                                        <q-item-section avatar><q-icon name="schedule" /></q-item-section>-->
<!--                                        <q-item-section><strong>Bet Closes:</strong> {{ event.bet_closes_at }}</q-item-section>-->
<!--                                    </q-item>-->
<!--                                    <q-item dense>-->
<!--                                        <q-item-section avatar><q-icon name="groups" /></q-item-section>-->
<!--                                        <q-item-section><strong>Total Bets:</strong> {{ event.options.reduce((acc, opt) => acc + opt.total_bets, 0) }}</q-item-section>-->
<!--                                    </q-item>-->
<!--                                    <q-item dense>-->
<!--                                        <q-item-section avatar><q-icon name="currency_rupee" /></q-item-section>-->
<!--                                        <q-item-section><strong>Total Amount:</strong> ₹{{ event.options.reduce((acc, opt) => acc + opt.total_amount, 0).toLocaleString() }}</q-item-section>-->
<!--                                    </q-item>-->

<!--                                    <q-item v-if="event.type === 'Settled' && event.result" dense>-->
<!--                                        <q-item-section avatar><q-icon name="emoji_events" /></q-item-section>-->
<!--                                        <q-item-section><strong>Result:</strong> {{ event.result.label }} ({{ event.result.payout }}x)</q-item-section>-->
<!--                                    </q-item>-->
<!--                                </q-list>-->
<!--                            </q-card-section>-->

<!--                            &lt;!&ndash; Options &ndash;&gt;-->
<!--                            &lt;!&ndash; Options &ndash;&gt;-->
<!--                            <q-card-section>-->
<!--                                <div class="text-subtitle2 text-primary q-mb-sm">Options</div>-->
<!--                                <q-list dense separator>-->
<!--                                    <q-item-->
<!--                                        v-for="(option, index) in event.options"-->
<!--                                        :key="index"-->
<!--                                        class="q-pa-sm"-->
<!--                                    >-->
<!--                                        <q-item-section>-->
<!--                                            <div class="text-bold">{{ option.label }}</div>-->
<!--                                            <div class="text-caption text-grey">-->
<!--                                                {{ option.total_bets }} Bets • ₹{{ option.total_amount.toLocaleString() }}-->
<!--                                                <span v-if="option.odds !== null">-->
<!--                                                    • <span class="text-positive">Odds: {{ option.odds }}x</span>-->
<!--                                                </span>-->
<!--                                            </div>-->
<!--                                        </q-item-section>-->
<!--                                    </q-item>-->
<!--                                </q-list>-->
<!--                            </q-card-section>-->

<!--                            &lt;!&ndash; Bet Button &ndash;&gt;-->
<!--                            <q-card-actions align="center" v-if="event.type === 'Open'">-->
<!--                                <q-btn-->
<!--                                    @click="$inertia.get(route('player.events.show', event))"-->
<!--                                    icon="sports_mma"-->
<!--                                    color="primary"-->
<!--                                    label="Place Bet"-->
<!--                                    class="full-width"-->
<!--                                    unelevated-->
<!--                                />-->
<!--                            </q-card-actions>-->
<!--                        </q-card>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </q-card-section>-->

<!--            &lt;!&ndash; Pagination &ndash;&gt;-->
<!--            <q-card-actions align="center" class="q-py-md">-->
<!--                <q-pagination-->
<!--                    v-model="page"-->
<!--                    :max="props.pagination.last_page"-->
<!--                    input-->
<!--                    input-class="text-primary"-->
<!--                    color="primary"-->
<!--                    @update:model-value="changePage"-->
<!--                />-->
<!--            </q-card-actions>-->
<!--        </q-card>-->

<!--    </q-page>-->
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

