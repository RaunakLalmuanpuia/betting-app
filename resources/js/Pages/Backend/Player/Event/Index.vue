<template>
    <q-page padding>
        <q-card class="bg-transparent no-shadow no-border">
            <q-card-section class="row">
                <div class="col-12">
                    <q-space />
                    <q-select
                        dense
                        outlined
                        style="min-width: 200px"
                        v-model="type"
                        :options="['Open', 'Settled', 'Closed', 'All']"
                        class="float-right"
                        label="Category"
                        @update:model-value="changeType"
                    />
                </div>
            </q-card-section>

            <q-card-section class="q-mx-sm bg-transparent">
                <div class="row q-col-gutter-lg">
                    <div
                        class="col-12 col-md-4"
                        v-for="event in props.events"
                        :key="event.id"
                    >
                        <div class="column full-height">
                            <q-card class="text-white bg-grey-6 fit column justify-between">
                                <q-img :src="event.img">
                                    <template #loading>
                                        <div class="text-subtitle1 text-white">Loading...</div>
                                    </template>
                                </q-img>

                                <q-separator />

                                <q-card-section class="text-h5 text-center">
                                    {{ event.event_name }}
                                </q-card-section>

                                <q-card-section class="text-justify">
                                    <div>{{ event.text }}</div>
                                    <div class="q-mt-sm text-caption">
                                        <div><strong>Status:</strong> {{ event.type }}</div>
                                        <div><strong>Event Start Time:</strong> {{ event.start_time }}</div>
                                        <div><strong>Betting Closes At:</strong> {{ event.bet_closes_at }}</div>
                                        <div>
                                            <strong>Total Bets:</strong>
                                            {{ event.options.reduce((acc, opt) => acc + opt.total_bets, 0) }}
                                        </div>
                                        <div>
                                            <strong>Total Amount:</strong>
                                            ₹{{ event.options.reduce((acc, opt) => acc + opt.total_amount, 0).toLocaleString() }}
                                        </div>
                                        <div v-if="event.type === 'Settled' && event.result">
                                            <strong>Result:</strong>
                                            {{ event.result.label }} (Payout: {{ event.result.payout }}x)
                                        </div>
                                    </div>
                                </q-card-section>

                                <q-separator color="grey-5" />

                                <q-card-section>
                                    <div class="text-subtitle2 q-mb-sm">Options:</div>
                                    <div v-for="(option, index) in event.options" :key="index" class="q-mb-sm">
                                        <div class="text-bold">{{ option.label }}</div>
                                        <div class="text-caption">
                                            {{ option.total_bets }} Bets • ₹{{ option.total_amount.toLocaleString() }}
                                            <span v-if="['Open', 'Closed'].includes(event.type)">
                                                • Odds: {{ option.odds }}x
                                            </span>
                                        </div>
                                    </div>
                                </q-card-section>

                                <q-separator color="grey-5" />

                                <q-card-actions v-if="event.type === 'Open'">
                                    <q-btn icon="remove_red_eye" flat label="Place Bet" />
                                </q-card-actions>
                            </q-card>
                        </div>
                    </div>
                </div>
            </q-card-section>

            <q-card-actions align="center">
                <q-pagination
                    v-model="page"
                    :min="1"
                    :max="props.pagination.last_page"
                    :input="true"
                    input-class="text-orange-10"
                    @update:model-value="changePage"
                />
            </q-card-actions>
        </q-card>
    </q-page>
</template>


<script setup>
import BackendLayout from "../../../../Layouts/BackendLayout.vue";
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

