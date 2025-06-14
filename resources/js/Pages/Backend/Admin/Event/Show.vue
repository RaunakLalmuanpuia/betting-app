<template>
    <q-page padding>
        <q-card bordered class="shadow-2 rounded-borders">
            <!-- Header Section -->
            <q-card-section class="row items-center q-gutter-md">
                <div class="text-h5 text-primary text-weight-bold">Event Details</div>
                <q-space />
                <q-chip :label="event.status" color="blue-10" text-color="white" icon="info" />
            </q-card-section>

            <q-separator />

            <!-- Event Info -->
            <q-card-section>
                <div class="text-h6 text-weight-medium q-mb-xs">{{ event.title }}</div>
                <div class="text-body2 q-mb-sm">{{ event.description }}</div>
                <div class="text-caption text-grey-7">
                    <q-icon name="schedule" class="q-mr-xs" />
                    Bet Closes At: {{ formatDate(event.bet_closes_at) }}
                </div>
            </q-card-section>

            <!-- Betting Options Table -->
            <q-card-section>
                <q-markup-table flat bordered dense wrap-cells>
                    <thead class="bg-grey-2 text-grey-9">
                    <tr>
                        <th class="text-left">Option</th>
                        <th class="text-center">Total Bets</th>
                        <th class="text-center">Total Amount</th>
                        <th class="text-center">Odds</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="option in options" :key="option.label">
                        <td class="text-left">
                            {{ option.label }}
                            <q-icon
                                v-if="option.is_winner"
                                name="emoji_events"
                                color="amber"
                                class="q-ml-sm"
                            />
                        </td>
                        <td class="text-center">{{ option.total_bets }}</td>
                        <td class="text-center">&#8377; {{ option.total_amount }}</td>
                        <td class="text-center">{{ option.odds }}</td>
                    </tr>
                    </tbody>
                </q-markup-table>
            </q-card-section>

            <q-separator />

            <!-- Stats Cards -->
            <q-card-section class="row q-col-gutter-md">
                <q-card class="col-xs-12 col-sm-6 col-md-3" flat bordered hoverable>
                    <q-card-section>
                        <div class="text-subtitle2 text-grey-8">Total Bets</div>
                        <div class="text-h6 text-primary text-weight-bold">
                            {{ stats.total_bets }}
                        </div>
                    </q-card-section>
                </q-card>

                <q-card class="col-xs-12 col-sm-6 col-md-3" flat bordered hoverable>
                    <q-card-section>
                        <div class="text-subtitle2 text-grey-8">Total Amount</div>
                        <div class="text-h6 text-primary text-weight-bold">
                            &#8377; {{ stats.total_amount }}
                        </div>
                    </q-card-section>
                </q-card>

                <q-card class="col-xs-12 col-sm-6 col-md-3" flat bordered hoverable>
                    <q-card-section>
                        <div class="text-subtitle2 text-grey-8">Unique Bettors</div>
                        <div class="text-h6 text-deep-orange text-weight-bold">
                            {{ stats.unique_users }}
                        </div>
                    </q-card-section>
                </q-card>

                <q-card class="col-xs-12 col-sm-6 col-md-3" flat bordered hoverable>
                    <q-card-section>
                        <div class="text-subtitle2 text-grey-8">Winners</div>
                        <div class="text-h6 text-positive text-weight-bold">
                            {{ stats.winners }}
                        </div>
                    </q-card-section>
                </q-card>
            </q-card-section>

            <!-- Winner Banner -->
            <q-card-section v-if="winningSummary">
                <q-banner class="bg-light-green-1 text-black rounded-borders" dense>
                    <template #avatar>
                        <q-icon name="emoji_events" color="amber-8" size="md" />
                    </template>
                    <div class="text-body1">
                        <div><strong>Winning Option:</strong> {{ winningSummary.label }}</div>
                        <div><strong>Number of Winners:</strong> {{ winningSummary.winners }}</div>
                        <div><strong>Total Payout:</strong> ₹ {{ winningSummary.total_payout }}</div>
                        <div><strong>Unpaid Winners:</strong> {{ winningSummary.unpaid_winners }}</div>
                    </div>
                </q-banner>
            </q-card-section>
        </q-card>
    </q-page>
</template>



<script setup>
import { ref } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import BackendLayout from "../../../../Layouts/BackendLayout.vue";

defineOptions({ layout: BackendLayout })

const page = usePage()

const event = ref(page.props.event)
const options = ref(page.props.options)
const stats = ref(page.props.stats)
const winningSummary = ref(page.props.winning_summary)

function formatDate(dateStr) {
    return new Date(dateStr).toLocaleString()
}




</script>

