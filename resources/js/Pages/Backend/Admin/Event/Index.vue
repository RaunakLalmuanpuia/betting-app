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
                    @click="$inertia.get(route('admin.events.create'))"
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
                                dense
                                flat
                                icon="more_vert"
                                color="primary"
                                round
                            >
                                <q-menu>
                                    <q-list style="min-width: 100px">
                                        <q-item
                                            clickable
                                            @click="$inertia.get(route('admin.events.show', props.row))"
                                        >
                                            <q-item-section>View</q-item-section>
                                        </q-item>
                                        <q-item clickable @click="openWinningOptionDialog(props.row)">
                                            <q-item-section>Set Winning Option</q-item-section>
                                        </q-item>


                                        <q-item
                                            clickable
                                            @click="$inertia.get(route('admin.events.edit', props.row))"
                                        >
                                            <q-item-section>Edit</q-item-section>
                                        </q-item>

                                        <q-item
                                            clickable @click="confirmDeleteEvent(props.row.id)">
                                            <q-item-section>Delete</q-item-section>
                                        </q-item>
                                    </q-list>
                                </q-menu>
                            </q-btn>
                        </q-td>
                    </template>

                </q-table>
            </q-card-section>
        </q-card>


        <q-dialog v-model="dialogVisible" persistent>
            <q-card style="min-width: 300px">
                <q-card-section>
                    <div class="text-h6">Set Winning Option</div>
                </q-card-section>

                <q-separator />

                <q-card-section>
                    <q-select
                        outlined
                        v-model="selectedWinningOptionId"
                        :options="availableOptions"
                        label="Select Option"
                        option-value="id"
                        option-label="label"
                        emit-value
                        map-options
                        dense
                    />
                </q-card-section>

                <q-card-actions align="right">
                    <q-btn flat label="Cancel" color="primary" v-close-popup />
                    <q-btn flat label="Set" color="positive" @click="submitWinningOption" />
                </q-card-actions>
            </q-card>
        </q-dialog>

    </q-page>
</template>

<script setup>
import BackendLayout from "../../../../Layouts/BackendLayout.vue";
import { ref } from "vue";
import { router } from "@inertiajs/vue3";
import {Quasar, useQuasar} from "quasar";

defineOptions({ layout: BackendLayout });

const $q =useQuasar();
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

const dialogVisible = ref(false);
const selectedEventId = ref(null);
const selectedWinningOptionId = ref(null);
const availableOptions = ref([]);

const openWinningOptionDialog = (event) => {
    selectedEventId.value = event.id;
    selectedWinningOptionId.value = event.winning_option_id || null;
    availableOptions.value = event.options;
    dialogVisible.value = true;
};

const submitWinningOption = () => {
    router.post(
        route('admin.events.setWinningOption', selectedEventId.value),
        { winning_option_id: selectedWinningOptionId.value },
        {
            preserveScroll: true,
            onSuccess: () => {
                // Fetch updated row
                axios.get(route('admin.events.json-show', selectedEventId.value)).then(({ data }) => {
                    const index = serverRows.value.findIndex(row => row.id === data.id);
                    if (index !== -1) {
                        serverRows.value[index] = data;
                    }
                });

                dialogVisible.value = false;
            }
        }
    );
};
const confirmDeleteEvent = (id) => {
    $q.dialog({
        title: 'Confirm',
        message: 'Are you sure you want to delete this event?',
        cancel: true,
        persistent: true
    }).onOk(() => {
        router.delete(route('admin.events.destroy', id), {
            preserveScroll: true,
            onSuccess: () => {
                // Remove the row from table
                const index = serverRows.value.findIndex(row => row.id === id);
                if (index !== -1) {
                    serverRows.value.splice(index, 1);
                }
                $q.notify({
                    message: 'Event deleted successfully',
                    color: 'positive',
                    position: 'top-right'
                });
            }
        });
    });
};


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
