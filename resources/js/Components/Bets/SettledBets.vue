
<template>
    <div class="q-mb-md">
        <q-banner v-if="bets.length === 0" class="bg-grey-3 text-grey-8">
            No bets placed yet.
        </q-banner>

        <q-table
            v-else
            flat
            bordered
            :rows="bets"
            :columns="columns"
            row-key="id"
            :pagination="{ rowsPerPage: 10 }"
        >
            <!-- Status -->
            <template v-slot:body-cell-status="props">
                <q-td :props="props">
                    <q-badge :color="getStatusColor(props.row.event.status)">
                        {{ props.row.event.status }}
                    </q-badge>
                </q-td>
            </template>

            <!-- Closes At -->
            <template v-slot:body-cell-bet_closes_at="props">
                <q-td :props="props">
                    {{ formatDate(props.row.event.bet_closes_at) }}
                </q-td>
            </template>

            <!-- Option Chosen -->
            <template v-slot:body-cell-option="props">
                <q-td :props="props">
                    {{ props.row.option.label }}
                </q-td>
            </template>

            <!-- All Options -->
            <template v-slot:body-cell-options="props">
                <q-td :props="props">
                    <q-badge
                        v-for="opt in props.row.event.options"
                        :key="opt.id"
                        color="primary"
                        outline
                        class="q-mr-xs"
                    >
                        {{ opt.label }}
                    </q-badge>
                </q-td>
            </template>

            <!-- Amount -->
            <template v-slot:body-cell-amount="props">
                <q-td :props="props">
                    ₹{{ props.row.amount }}
                </q-td>
            </template>

            <!-- Payout -->
            <template v-slot:body-cell-payout="props">
                <q-td :props="props">
          <span :class="props.row.is_winner ? 'text-positive' : 'text-grey'">
            ₹{{ props.row.is_winner ? props.row.payout_amount : '0.00' }}
          </span>
                </q-td>
            </template>

            <!-- Result -->
            <template v-slot:body-cell-result="props">
                <q-td :props="props">
                    <q-badge
                        v-if="props.row.event.winning_option_id"
                        :color="props.row.is_winner ? 'positive' : 'negative'"
                    >
                        {{ props.row.is_winner ? 'Won' : 'Lost' }}
                    </q-badge>
                </q-td>
            </template>

            <!-- Transactions Action -->
            <template v-slot:body-cell-transactions="props">
                <q-td :props="props">
                    <q-btn dense flat icon="eye" color="primary" label="View" @click="openTransactionDialog(props.row)" />
                </q-td>

            </template>
        </q-table>

        <!-- Transaction Dialog -->
        <q-dialog v-model="transactionDialog">
            <q-card style="min-width: 400px">
                <q-card-section>
                    <div class="text-h6">Transaction History</div>
                </q-card-section>
                <q-separator />
                <q-card-section>
                    <q-timeline color="secondary">
                        <q-timeline-entry
                            v-for="txn in selectedBet?.transactions || []"
                            :key="txn.id"
                            :icon="getTransactionIcon(txn.type)"
                            :color="getTransactionColor(txn.type)"
                            :subtitle="formatDate(txn.created_at)"
                        >
                            <template v-slot:title>
                                {{ formatTransactionType(txn.type) }} - ₹{{ txn.amount }}
                            </template>
                            <div>{{ txn.remarks }}</div>
                            <div v-if="txn.reference" class="text-caption text-grey-7">
                                Ref: {{ txn.reference }}
                            </div>
                        </q-timeline-entry>
                    </q-timeline>
                </q-card-section>
                <q-separator />
                <q-card-actions align="right">
                    <q-btn flat label="Close" color="primary" v-close-popup />
                </q-card-actions>
            </q-card>
        </q-dialog>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { date } from 'quasar';

defineProps({
    bets: Array
});

const transactionDialog = ref(false);
const selectedBet = ref(null);

const openTransactionDialog = (bet) => {
    selectedBet.value = bet;
    transactionDialog.value = true;
};

const columns = [
    { name: 'event', label: 'Event Title', field: row => row.event.title, sortable: true },
    { name: 'status', label: 'Status', field: row => row.event.status, sortable: true },
    { name: 'bet_closes_at', label: 'Closes At', field: row => row.event.bet_closes_at, sortable: true },
    { name: 'option', label: 'Option Chosen', field: row => row.option.label },
    { name: 'options', label: 'All Options', field: row => row.event.options },
    { name: 'amount', label: 'Amount', field: 'amount', align: 'right' },
    { name: 'payout', label: 'Payout', field: 'payout_amount', align: 'right' },
    { name: 'result', label: 'Result', field: 'is_winner' },
    { name: 'transactions', label: 'Transactions', field: 'transactions', sortable: false },
];

const getStatusColor = (status) => {
    switch (status?.toLowerCase()) {
        case 'settled': return 'positive';
        case 'closed': return 'warning';
        case 'open': return 'primary';
        default: return 'grey';
    }
};

const getTransactionIcon = (type) => {
    switch (type?.toLowerCase()) {
        case 'payment_received': return 'mdi-arrow-down-circle';
        case 'payout_sent': return 'mdi-arrow-up-circle';
        case 'bet_placed': return 'mdi-dice-multiple';
        default: return 'mdi-swap-horizontal';
    }
};

const getTransactionColor = (type) => {
    switch (type?.toLowerCase()) {
        case 'payment_received':
        case 'payout_sent': return 'positive';
        case 'bet_placed': return 'negative';
        default: return 'info';
    }
};

const formatTransactionType = (type) => {
    return type
        .split('_')
        .map(word => word.charAt(0).toUpperCase() + word.slice(1))
        .join(' ');
};

const formatDate = (dateString) => {
    return date.formatDate(dateString, 'YYYY-MM-DD HH:mm');
};
</script>
