<template>
    <q-page padding>
        <q-card>
            <q-card-section>
                <div class="text-h6">Event List</div>
            </q-card-section>
            <q-separator />
            <q-card-section>
                <q-table
                    :rows="props.events"
                    :columns="columns"
                    row-key="id"
                    flat
                    bordered
                    wrap-cells
                >
                    <template #body-cell-actions="props">
                        <q-td align="center">
                            <q-btn color="primary" label="View" size="sm" flat :to="`/admin/events/${props.row.id}`" />
                            <q-btn color="primary" label="Edit" size="sm" flat :to="`/admin/events/${props.row.id}/edit`" />
                        </q-td>
                    </template>
                </q-table>
            </q-card-section>
        </q-card>
    </q-page>
</template>


<script setup>
import BackendLayout from "../../../../Layouts/BackendLayout.vue";


defineOptions({ layout: BackendLayout });

const props = defineProps({
    events: Array,
});

const columns = [
    { name: 'title', label: 'Title', field: 'title', align: 'left' },
    { name: 'description', label: 'Description', field: 'description' },
    { name: 'status', label: 'Status', field: 'status' },
    { name: 'bet_closes_at', label: 'Bet Closes At', field: 'bet_closes_at' },
    { name: 'winning_option', label: 'Winning Option', field: row => row.winning_option?.label || '-', sortable: false },
    { name: 'total_bets', label: 'Total Bets', field: row => row.bets?.length || 0, sortable: false },
    { name: 'actions', label: 'Actions', field: 'actions', sortable: false },
];

</script>



<style scoped>

</style>
