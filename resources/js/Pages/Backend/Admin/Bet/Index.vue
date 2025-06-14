<template>
    <q-page padding>
        <q-card>
            <q-card-section class="row items-center q-gutter-sm">
                <div class="text-h6">Bets List</div>
                <q-space />
                <q-select
                    dense
                    outlined
                    v-model="filters.event_id"
                    :options="props.events"
                    option-label="label"
                    option-value="value"
                    emit-value
                    map-options
                    clearable
                    label="Filter by Event"
                    style="width: 200px"
                    @update:model-value="updateSearch"
                />
                <q-input
                    outlined
                    dense
                    debounce="300"
                    v-model="filters.search"
                    placeholder="Search user, phone, uuid"
                    @update:model-value="updateSearch"
                />

            </q-card-section>

            <q-markup-table flat bordered>
                <thead>
                <tr>
                    <th>#</th>
                    <th>Bet UUID</th>
                    <th>User</th>
                    <th>Mobile</th>
                    <th>Event</th>
                    <th>Option</th>
                    <th>Amount</th>
                    <th>Is Winner</th>
                    <th>Payout</th>
                    <th>Is Paid</th>
                    <th>Txn Status</th>
                    <th>Order ID</th>
                    <th>Txn ID</th>

                    <th>Date</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(bet, i) in bets.data" :key="bet.id">
                    <td>{{ (page - 1) * filters.per_page + i + 1 }}</td>
                    <td>{{ bet.bet_uuid }}</td>
                    <td>{{ bet.user?.name }}</td>
                    <td>{{ bet.user?.mobile }}</td>
                    <td>{{ bet.event?.title }}</td>
                    <td>{{ bet.option?.label }}</td>
                    <td>&#8377; {{ bet.amount }}</td>
                    <td>
                        <q-badge :color="bet.is_winner ? 'green' : 'grey'" :label="bet.is_winner ? 'Yes' : 'No'" />
                    </td>
                    <td>{{ bet.payout_amount ?? '-' }}</td>
                    <td>
                        <q-badge :color="bet.is_paid ? 'positive' : 'negative'" :label="bet.is_paid ? 'Yes' : 'No'" />
                    </td>
                    <td>
                        <q-badge :color="getTxnColor(bet.transactions.status)" :label="bet.transactions.status" />
                    </td>
                    <td>{{ bet.transactions?.order_id }}</td>
                    <td>{{ bet.transactions?.transaction_id }}</td>
                    <td>{{ formatDate(bet.created_at) }}</td>
                </tr>
                </tbody>
            </q-markup-table>

            <q-card-section class="q-pa-md">
                <div class="row items-center justify-between q-gutter-md q-col-gutter-md q-mx-xs q-mb-sm wrap">
                    <!-- Pagination Controls -->
                    <div class="row items-center q-gutter-sm">
                        <q-pagination
                            v-model="page"
                            :max="bets.last_page"
                            :max-pages="6"
                            boundary-links
                            direction-links
                            boundary-numbers
                            @update:model-value="changePage"
                            color="primary"
                        />
                        <div class="text-caption">Page {{ page }} of {{ bets.last_page }}</div>
                    </div>

                    <!-- Per Page Select -->
                    <div style="min-width: 140px">
                        <q-select
                            dense
                            outlined
                            v-model="filters.per_page"
                            :options="[10, 15, 25, 50, 100]"
                            label="Rows per page"
                            @update:model-value="updateSearch"
                        />
                    </div>
                </div>
            </q-card-section>


        </q-card>
    </q-page>
</template>

<script setup>
import BackendLayout from "../../../../Layouts/BackendLayout.vue";
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';

defineOptions({ layout: BackendLayout });

const props = defineProps({
    bets: Object,
    filters: Object,
    events: Array
});

const page = ref(props.bets.current_page);
const filters = ref({
    search: props.filters.search || '',
    event_id: props.filters.event_id || '',
    per_page: parseInt(props.filters.per_page) || 15,
});

watch(page, (val) => {
    router.get(route('admin.bets.index'), { ...filters.value, page: val }, { preserveScroll: true });
});

function updateSearch() {
    page.value = 1;
    router.get(route('admin.bets.index'), { ...filters.value, page: page.value }, { preserveScroll: true });
}

function changePage(val) {
    page.value = val;
}

function getTxnColor(status) {
    return status === 'TXN_SUCCESS'
        ? 'positive'
        : status === 'TXN_FAILURE'
            ? 'negative'
            : 'grey';
}

function formatDate(dateStr) {
    return new Date(dateStr).toLocaleString();
}
</script>
