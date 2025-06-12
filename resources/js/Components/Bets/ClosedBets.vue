<template>
    <div class="q-mb-md">

        <q-banner v-if="bets.length === 0" class="bg-grey-3 text-grey-8 text-center">
            No bets placed yet.
        </q-banner>

        <q-table
            v-else
            flat
            title="Closed Bets"
            :rows="bets"
            :columns="columns"
            row-key="id"
            :pagination="{ rowsPerPage: 10 }"
        >
            <template v-slot:body-cell-options="props">
                <q-td :props="props">
                    <div class="q-gutter-xs">
                        <q-badge
                            v-for="opt in props.row.event.options"
                            :key="opt.id"
                            color="primary"
                            outline
                            class="q-mr-xs"
                        >
                            {{ opt.label }}
                        </q-badge>
                    </div>
                </q-td>
            </template>

            <template v-slot:body-cell-status="props">
                <q-td :props="props">
                    <q-badge :color="getStatusColor(props.row.event.status)">
                        {{ props.row.event.status }}
                    </q-badge>
                </q-td>
            </template>

            <template v-slot:body-cell-event="props">
                <q-td :props="props">
                    {{ props.row.event.title }}
                </q-td>
            </template>

            <template v-slot:body-cell-bet_closes_at="props">
                <q-td :props="props">
                    {{ formatDate(props.row.event.bet_closes_at) }}
                </q-td>
            </template>

            <template v-slot:body-cell-option="props">
                <q-td :props="props">
                    {{ props.row.option.label }}
                </q-td>
            </template>

            <template v-slot:body-cell-amount="props">
                <q-td :props="props">
                    ₹{{ props.row.amount }}
                </q-td>
            </template>



            <template v-slot:body-cell-transactions="props">
                <q-td :props="props">
                    <q-btn
                        dense
                        flat
                        icon="mdi-eye"
                        color="primary"
                        label="View"
                        @click="openDialog(props.row)"
                    />
                </q-td>
            </template>
        </q-table>

        <!-- Dialog for transaction history -->
        <q-dialog v-model="dialog" persistent>
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

const props = defineProps(['bets']);

const dialog = ref(false);
const selectedBet = ref(null);

const openDialog = (bet) => {
    selectedBet.value = bet;
    dialog.value = true;
};

const columns = [
    { name: 'event', label: 'Event Title', field: 'event.title', sortable: true },
    { name: 'options', label: 'Options', field: 'event.options', sortable: false },
    { name: 'status', label: 'Status', field: row => row.event.status, sortable: true },
    { name: 'bet_closes_at', label: 'Closes At', field: row => row.event.bet_closes_at, sortable: true },
    { name: 'option', label: 'Option Chosen', field: row => row.option.label, sortable: true },
    { name: 'amount', label: 'Amount', field: 'amount', align: 'right', sortable: true },
    { name: 'transactions', label: 'Transactions', field: 'transactions', sortable: false },
];

const formatDate = (dateString) => {
    return date.formatDate(dateString, 'YYYY-MM-DD HH:mm');
};

const getStatusColor = (status) => {
    switch (status?.toLowerCase()) {
        case 'settled': return 'positive';
        case 'closed': return 'positive';
        case 'open': return 'positive';
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

</script>
