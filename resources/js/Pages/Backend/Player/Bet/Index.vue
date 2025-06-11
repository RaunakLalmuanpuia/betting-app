<template>
    <q-page class="q-pa-md">
        <div class="text-h4 text-weight-bold">My Bets</div>

        <q-tabs
            v-model="activeTab"
            dense
            class="text-grey"
            active-color="primary"
            indicator-color="primary"
            align="justify"
            narrow-indicator
        >
            <q-tab name="open" label="Open Bets" />
            <q-tab name="settled" label="Settled Bets" />
            <q-tab name="closed" label="Closed Bets" />

        </q-tabs>

        <q-separator />

        <q-tab-panels v-model="activeTab" animated>
            <q-tab-panel name="open">
                <OpenBets
                    :bets="openBets"

                />
            </q-tab-panel>

            <q-tab-panel name="settled">
                <SettledBets
                    :bets="settledBets"
                />
            </q-tab-panel>


            <q-tab-panel name="closed">
                <ClosedBets
                    :bets="closedBets"
                />
            </q-tab-panel>


        </q-tab-panels>


    </q-page>
</template>

<script setup>
import BackendLayout from "../../../../Layouts/BackendLayout.vue";
import ClosedBets from '../../../../Components/Bets/ClosedBets.vue'
import OpenBets from '../../../../Components/Bets/OpenBets.vue'
import SettledBets from '../../../../Components/Bets/SettledBets.vue'
import { ref,computed } from 'vue';

defineOptions({ layout: BackendLayout });

const props=defineProps(['bets']);

const activeTab = ref('open');

// Categorize bets based on event status
const openBets = computed(() => {
    return props.bets.filter(bet => bet.event.status === 'open');
});

const closedBets = computed(() => {
    return props.bets.filter(bet => bet.event.status === 'closed');
});

const settledBets = computed(() => {
    return props.bets.filter(bet => bet.event.status === 'settled');
});

</script>
<style scoped>
.my-card {
    border-radius: 12px;
}



</style>
