<template>
    <q-page padding>
        <q-card>
            <q-card-section class="row items-center q-gutter-sm">
                <div class="text-h6">Event List</div>
                <q-space />
                <q-input
                    dense
                    outlined
                    debounce="300"
                    v-model="search"
                    placeholder="Search title, description  or status"
                    @update:model-value="onSearch"
                    style="width: 250px"
                />
                <q-btn
                    label="Add Event"
                    flat
                    color="btn-primary"
                    outline

                />

            </q-card-section>

            <q-separator />

            <q-card-section>
                <q-table
                    :rows="serverRows"
                    :columns="columns"
                    row-key="id"
                    flat
                    bordered
                    wrap-cells
                    v-model:pagination="pagination"
                    :rows-per-page-options="[5, 10, 15, 20]"
                    @request="onRequest"
                >
                    <template #body-cell-actions="props">
                        <q-td align="center">
                            <q-btn
                                color="primary"
                                label="View"
                                size="sm"
                                flat
                                :to="`/admin/events/${props.row.id}`"
                            />
                            <q-btn
                                color="primary"
                                label="Edit"
                                size="sm"
                                flat
                                :to="`/admin/events/${props.row.id}/edit`"
                            />
                        </q-td>
                    </template>
                </q-table>
            </q-card-section>
        </q-card>
    </q-page>
</template>

<script setup>
import BackendLayout from "../../../../Layouts/BackendLayout.vue";
import { ref } from "vue";
import { router } from "@inertiajs/vue3";

defineOptions({ layout: BackendLayout });

const props = defineProps({
    events: Array,
    pagination: Object,
    filters: Object,
});

const search = ref(props.filters.search || '');
const serverRows = ref(props.events);

const pagination = ref({
    page: props.pagination.page,
    rowsPerPage: props.pagination.rowsPerPage,
    rowsNumber: props.pagination.rowsNumber,
});

const onSearch = () => {
    router.get(
        route('admin.events.index'),
        { search: search.value },
        {
            preserveState: true,
            replace: true,
            only: ['events', 'pagination'],
            onSuccess: (page) => {
                serverRows.value = page.props.events;
                pagination.value = {
                    page: page.props.pagination.page,
                    rowsPerPage: page.props.pagination.rowsPerPage,
                    rowsNumber: page.props.pagination.rowsNumber,
                };
            },
        }
    );
};

const onRequest = ({ pagination: pag }) => {
    router.get(
        route("admin.events.index"),
        {
            page: pag.page,
            per_page: pag.rowsPerPage,
            search: search.value,
        },
        {
            preserveState: true,
            replace: true,
            only: ["events", "pagination"],
            onSuccess: (page) => {
                serverRows.value = page.props.events;
                pagination.value = {
                    page: page.props.pagination.page,
                    rowsPerPage: page.props.pagination.rowsPerPage,
                    rowsNumber: page.props.pagination.rowsNumber,
                };
            },
        }
    );
};

const columns = [
    { name: 'title', label: 'Title', field: 'title', align: 'left' },
    { name: 'description', label: 'Description', field: 'description' },
    { name: 'status', label: 'Status', field: 'status' },
    { name: 'bet_closes_at', label: 'Bet Closes At', field: 'bet_closes_at' },
    {
        name: 'winning_option',
        label: 'Winning Option',
        field: row => row.winning_option?.label || '-',
        sortable: false
    },
    {
        name: 'total_bets',
        label: 'Total Bets',
        field: row => row.bets?.length || 0,
        sortable: false
    },
    { name: 'actions', label: 'Actions', field: 'actions', sortable: false },
];
</script>
