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
                        :options="[ 'Open', 'Settled', 'Closed','All']"
                        class="float-right"
                        label="Category"
                    />
                </div>
            </q-card-section>

            <q-card-section class="q-mx-sm bg-transparent">
                <div class="row q-col-gutter-lg">
                    <div
                        class="col-12 col-md-4"
                        v-for="event in getData2"
                        :key="event.event_name"
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
import { ref, computed } from 'vue';

defineOptions({ layout: BackendLayout });

const type = ref('Open');
const page = ref(1);
const currentPage = 1;
const totalPages = 3;

const cards_data = [
    {
        img: 'https://placeimg.com/500/300/nature?t=' + Math.random(),
        type: 'Open',
        event_name: 'Who will win the match?',
        text: 'IPL Final between Team A and Team B.',
        start_time: '2025-06-12 18:00',
        bet_closes_at: '2025-06-12 17:50',
        result: null,
        options: [
            { label: 'Team A', total_bets: 120, total_amount: 34000, odds: 1.8 },
            { label: 'Team B', total_bets: 95, total_amount: 27500, odds: 2.2 }
        ]
    },
    {
        img: 'https://placeimg.com/500/300/nature?t=' + Math.random(),
        type: 'Settled',
        event_name: 'First Goal Scorer',
        text: 'Who will score the first goal in the match?',
        start_time: '2025-06-09 20:00',
        bet_closes_at: '2025-06-09 19:45',
        result: { label: 'Player Y', payout: 2.5 },
        options: [
            { label: 'Player X', total_bets: 60, total_amount: 12000 },
            { label: 'Player Y', total_bets: 85, total_amount: 17000 }
        ]
    },
    {
        img: 'https://placeimg.com/500/300/nature?t=' + Math.random(),
        type: 'Closed',
        event_name: 'Toss Winner',
        text: 'Which team will win the toss?',
        start_time: '2025-06-10 17:30',
        bet_closes_at: '2025-06-10 17:15',
        result: null,
        options: [
            { label: 'Team A', total_bets: 110, total_amount: 25000, odds: 1.9 },
            { label: 'Team B', total_bets: 130, total_amount: 31000, odds: 1.7 }
        ]
    },
    {
        img: 'https://placeimg.com/500/300/nature?t=' + Math.random(),
        type: 'Open',
        event_name: 'Next Prime Minister',
        text: 'Who will be the next PM?',
        start_time: '2025-06-20 09:00',
        bet_closes_at: '2025-06-20 08:00',
        result: null,
        options: [
            { label: 'Candidate A', total_bets: 200, total_amount: 48000, odds: 1.6 },
            { label: 'Candidate B', total_bets: 180, total_amount: 46000, odds: 1.8 }
        ]
    }
];


function getData() {
    if (type.value === 'All') return cards_data;
    return cards_data.filter(
        item => item.type.toLowerCase() === type.value.toLowerCase()
    );
}

const getData2 = computed(() => {
    const start = (page.value - 1) * totalPages;
    const end = start + totalPages;
    return getData().slice(start, end);
});
</script>
