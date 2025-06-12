
<template>
    <div class="q-mb-md">
        <q-banner v-if="bets.length === 0" class="bg-grey-3 text-grey-8 text-center">
            No bets placed yet.
        </q-banner>

        <q-table
            v-else
            flat
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
            <q-card style="width: 100%; max-width: 600px" class="q-pa-md">
                <q-card-section>
                    <div class="text-h6">Transaction History</div>
                </q-card-section>

                <q-separator />

                <q-card-section style="max-height: 60vh; overflow-y: auto;" class="q-gutter-y-md">
                    <q-item>
                        <q-item-section><strong>Order ID:</strong></q-item-section>
                        <q-item-section>{{ selectedBet.transactions?.order_id }}</q-item-section>
                    </q-item>

                    <q-item>
                        <q-item-section><strong>Transaction ID:</strong></q-item-section>
                        <q-item-section>{{ selectedBet.transactions?.transaction_id }}</q-item-section>
                    </q-item>

                    <q-item>
                        <q-item-section><strong>Amount:</strong></q-item-section>
                        <q-item-section>₹{{ selectedBet.transactions?.amount }}</q-item-section>
                    </q-item>

                    <q-item>
                        <q-item-section><strong>Status:</strong></q-item-section>
                        <q-item-section>{{ selectedBet.transactions?.status }}</q-item-section>
                    </q-item>

                    <q-item>
                        <q-item-section><strong>Message:</strong></q-item-section>
                        <q-item-section>{{ selectedBet.transactions?.msg }}</q-item-section>
                    </q-item>

                </q-card-section>

                <q-card-actions align="right">
                    <q-btn
                        v-if="selectedBet.transactions.status !== 'TXN_SUCCESS' && selectedBet.transactions.status !== 'TXN_FAILURE'"
                        class="sized-btn"
                        @click="handleVerify(selectedBet.transactions?.order_id)"
                        color="accent"
                        rounded
                        outline
                        label="Verify Payment"
                        no-caps
                    />
                    <q-btn v-else label="Download PDF" icon="download" color="primary" flat @click="downloadPDF" />
                    <q-btn flat label="Close" color="primary" v-close-popup />
                </q-card-actions>
            </q-card>
        </q-dialog>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { date } from 'quasar';
import {router} from "@inertiajs/vue3";

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



const handleVerify=(orderId)=>{
    router.post(route('player.events.payment.verify',orderId),{},{
        preserveState:false,
        onSuccess:params => q.loading.show({
            boxClass: 'bg-grey-2 text-grey-9',
            spinnerColor: 'primary', message: ' Verifying Payment...'
        }),
        onFinish:params => q.loading.hide()
    })
}
const formatDate = (dateString) => {
    return date.formatDate(dateString, 'YYYY-MM-DD HH:mm');
};
</script>
