<template>
    <q-page padding>
        <q-card class="q-pa-md shadow-2 rounded-borders max-w-md mx-auto">
            <q-card-section>
                <div class="text-h6 text-primary">Transaction Receipt</div>
            </q-card-section>

            <q-separator />

            <q-card-section class="q-gutter-y-md">


                <q-item>
                    <q-item-section><strong>Order ID:</strong></q-item-section>
                    <q-item-section>{{ transaction.order_id }}</q-item-section>
                </q-item>

                <q-item>
                    <q-item-section><strong>Transaction ID:</strong></q-item-section>
                    <q-item-section>{{ transaction.transaction_id }}</q-item-section>
                </q-item>

                <q-item>
                    <q-item-section><strong>Status:</strong></q-item-section>
                    <q-item-section>
                        <q-badge>
                            {{ transaction.status }}
                        </q-badge>
                    </q-item-section>
                </q-item>

                <q-item>
                    <q-item-section><strong>Name:</strong></q-item-section>
                    <q-item-section>{{ transaction.user?.name || 'N/A' }}</q-item-section>
                </q-item>

                <q-item>
                    <q-item-section><strong>Contact:</strong></q-item-section>
                    <q-item-section>{{ transaction.user?.mobile || 'N/A' }}</q-item-section>
                </q-item>

                <q-item>
                    <q-item-section><strong>Event:</strong></q-item-section>
                    <q-item-section>{{ transaction.bet?.event?.title || 'N/A' }}</q-item-section>
                </q-item>

                <q-item>
                    <q-item-section><strong>Selected Option:</strong></q-item-section>
                    <q-item-section>{{ transaction.bet?.option?.label || 'N/A' }}</q-item-section>
                </q-item>

                <q-item>
                    <q-item-section><strong>Amount:</strong></q-item-section>
                    <q-item-section>₹{{ transaction.amount }}</q-item-section>
                </q-item>

                <q-item>
                    <q-item-section><strong>Transaction Date:</strong></q-item-section>
                    <q-item-section>{{ new Date(transaction.transaction_date).toLocaleString() }}</q-item-section>
                </q-item>
            </q-card-section>

            <q-separator />

            <q-card-actions align="right">
                <q-btn v-if="data.status==='TXN_SUCCESS'" class="sized-btn" @click="$inertia.get(route('player.bets.index'))" color="accent" rounded outline label="View Bets" no-caps/>
                <q-btn v-else-if="data.status==='TXN_FAILURE'" class="sized-btn" @click="$inertia.get(route('player.events.show', data.bet?.event))" color="accent" rounded outline label="Try Again" no-caps/>
                <q-btn v-else-if="data.status==='Attempted'" class="sized-btn" @click="handleVerify" color="accent" rounded outline label="Verify Payment" no-caps/>
                <q-btn v-else-if="data.status==='PENDING'" class="sized-btn" @click="handleVerify" color="accent" rounded outline label="Verify Payment" no-caps/>
                <q-btn label="Download PDF" icon="download" color="primary" flat @click="downloadPDF" />
            </q-card-actions>
        </q-card>
    </q-page>
</template>

<script setup>
import BackendLayout from "../../../../Layouts/BackendLayout.vue";
import {router} from "@inertiajs/vue3";
import {useQuasar} from "quasar";
defineOptions({ layout: BackendLayout });

const props = defineProps({
    data: Object,
});
const q = useQuasar();
const transaction = props.data


const handleVerify=e=>{
    router.post(route('player.events.payment.verify',props.data?.order_id),{},{
        preserveState:false,
        onSuccess:params => q.loading.show({
            boxClass: 'bg-grey-2 text-grey-9',
            spinnerColor: 'primary', message: ' Verifying Payment...'
        }),
        onFinish:params => q.loading.hide()
    })
}
</script>

<style scoped>

</style>
