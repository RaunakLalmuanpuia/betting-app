<template>
    <q-page padding>
        <q-card class="q-pa-md shadow-2 rounded-borders">
            <!-- Loader -->
            <q-inner-loading :showing="loading">
                <q-spinner size="50px" color="primary" />
            </q-inner-loading>

            <!-- Header Section -->
            <q-card-section class="row items-center justify-between q-gutter-md">
                <div class="text-h6 text-primary">Bet Payouts</div>
                <div class="row q-gutter-sm items-center">
                    <!-- Paid Status Filter -->
                    <q-select
                        v-model="filter"
                        :options="[
        { label: 'All', value: '' },
        { label: 'Paid', value: '1' },
        { label: 'Unpaid', value: '0' }
      ]"
                        dense
                        outlined
                        rounded
                        color="primary"
                        emit-value
                        map-options
                        style="width: 150px"
                        @update:model-value="handleSearch"
                    />

                    <!-- Search Input -->
                    <q-input
                        v-model="search"
                        debounce="500"
                        outlined
                        dense
                        rounded
                        color="primary"
                        placeholder="Search by UUID, user, event"
                        style="min-width: 280px"
                        @keyup.enter="handleSearch"
                    >
                        <template #append>
                            <q-btn icon="search" flat dense @click="handleSearch" />
                        </template>
                    </q-input>
                </div>
            </q-card-section>

            <!-- Table -->
            <q-table
                :rows="bets.data"
                :columns="columns"
                row-key="id"
                dense
                flat
                bordered
                wrap-cells
                class="q-mt-sm"
                separator="horizontal"
                no-data-label="No bets found"
                v-model:pagination="pagination"
                :rows-per-page-options="[5, 10, 15, 25, 50, 100]"
                :loading="loading"
                @request="onRequest"
            >

            <!-- Action Column Slot -->
                <template #body-cell-action="props">
                    <q-td :props="props" class="text-center">
                        <q-btn-dropdown
                            dense
                            flat
                            color="primary"
                            size="sm"
                        >
                            <q-list bordered separator>
                                <q-item clickable v-close-popup @click="() => viewBet(props.row)">
                                    <q-item-section>View</q-item-section>
                                </q-item>

                                <q-item clickable v-close-popup @click="() => markAsPaid(props.row)">
                                    <q-item-section>Make Payout</q-item-section>
                                </q-item>

                                <q-item clickable v-close-popup @click="() => markAsPaid(props.row)">
                                    <q-item-section>Mark as Paid</q-item-section>
                                </q-item>
                            </q-list>
                        </q-btn-dropdown>
                    </q-td>
                </template>
            </q-table>
            <!-- Stats Section -->
            <div class="q-mt-xl q-gutter-md row  justify-center">
                <q-card
                    v-for="(value, key) in statCards"
                    :key="key"
                    class="col-xs-12 col-sm-6 col-md-4 shadow-2 rounded-borders"
                    :class="value.bg"
                >
                    <q-card-section class="q-pa-md row items-center">
                        <q-icon :name="value.icon" size="md" class="q-mr-sm" :color="value.color" />
                        <div>
                            <div class="text-subtitle2 text-weight-medium">{{ value.label }}</div>
                            <div class="text-h6 text-bold">{{ value.prefix }}{{ value.value }}</div>
                        </div>
                    </q-card-section>
                </q-card>
            </div>


        </q-card>
        <!-- Stats Section -->



    </q-page>
</template>

<script setup>
import BackendLayout from "../../../../Layouts/BackendLayout.vue";
defineOptions({ layout: BackendLayout });

import { ref,computed } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
    bets: Object,
    filters: Object,
    stats: Object
})


const statCards = computed(() => ({
    total_winning_bets: {
        label: 'Total Winning Bets',
        value: props.stats.total_winning_bets,
        icon: 'emoji_events',
        color: 'primary',
        bg: 'bg-blue-1',
        prefix: ''
    },
    total_payout_amount: {
        label: 'Total Payout Amount',
        value: props.stats.total_payout_amount,
        icon: 'account_balance_wallet',
        color: 'deep-purple',
        bg: 'bg-deep-purple-1',
        prefix: '₹'
    },
    total_paid_bets: {
        label: 'Total Paid Bets',
        value: props.stats.total_paid_bets,
        icon: 'check_circle',
        color: 'positive',
        bg: 'bg-green-1',
        prefix: ''
    },
    total_unpaid_bets: {
        label: 'Total Unpaid Bets',
        value: props.stats.total_unpaid_bets,
        icon: 'hourglass_empty',
        color: 'negative',
        bg: 'bg-red-1',
        prefix: ''
    },
    total_paid_amount: {
        label: 'Total Paid Amount',
        value: props.stats.total_paid_amount,
        icon: 'payments',
        color: 'positive',
        bg: 'bg-green-2',
        prefix: '₹'
    },
    total_unpaid_amount: {
        label: 'Total Unpaid Amount',
        value: props.stats.total_unpaid_amount,
        icon: 'pending_actions',
        color: 'negative',
        bg: 'bg-red-2',
        prefix: '₹'
    }
}))


const search = ref(props.filters.search || '')
const filter = ref(props.filters.filter ?? '')

const loading = ref(false)

const pagination = computed(() => ({
    page: props.bets.current_page,
    rowsPerPage: props.bets.per_page,
    rowsNumber: props.bets.total
}))

const onRequest = (params) => {
    loading.value = true
    router.get(route('admin.payout.index'), {
        search: search.value,
        filter: filter.value,
        page: params.pagination.page,
        per_page: params.pagination.rowsPerPage
    }, {
        preserveState: true,
        replace: true,
        onFinish: () => {
            loading.value = false
        }
    })
}
const handleSearch = () => {
    loading.value = true
    router.get(route('admin.payout.index'), {
        search: search.value,
        filter: filter.value,
        page: 1,
        per_page: pagination.value.rowsPerPage
    }, {
        preserveState: true,
        replace: true,
        onFinish: () => {
            loading.value = false
        }
    })
}

const viewBet = (row) => {
    console.log('Viewing bet:', row)
    // Example: router.visit(route('admin.payout.show', row.id))
}

const markAsPaid = (row) => {
    console.log('Marking as paid:', row)
    // Example: Call API or emit event to update
}

const columns = [
    { name: 'uuid', label: 'UUID', field: 'bet_uuid' },
    { name: 'user', label: 'User', field: row => row.user?.name },
    { name: 'event', label: 'Event', field: row => row.event?.title },
    { name: 'option', label: 'Option', field: row => row.option?.label },
    { name: 'amount', label: 'Amount', field: 'amount', align: 'right' },
    { name: 'payout', label: 'Payout', field: 'payout_amount', align: 'right' },
    { name: 'status', label: 'Status', field: row => row.is_paid ? 'Paid' : 'Unpaid' },
    { name: 'action', label: 'Action', field: 'action', align: 'center' }
]
</script>
