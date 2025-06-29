<template>
    <q-page padding>
        <div class="flex">
            <!-- Left Section (60%) -->
            <div class="w-3/5 pr-4">
                <Title :event="event"/>
                <Comment />
            </div>

            <!-- Right Section (40%) -->
            <div class="w-2/5">
                <PlaceBet :event="event"/>
            </div>
        </div>
    </q-page>

    <q-page padding>
        <q-card class="bg-white text-dark shadow-2 rounded-borders q-mx-auto" style="max-width: 1000px;">
            <!-- Event Banner -->
            <q-img :src="`/storage/${event.image}`" height="220px" contain class="rounded-borders-top" />

            <!-- Event Title & Info -->
            <q-card-section class="text-center">
                <div class="text-h5 q-mb-xs">{{ event.title }}</div>
                <div class="text-body2 text-grey-7">
                    <q-icon name="info" class="q-mr-xs" /> {{ event.status }} |
                    <q-icon name="schedule" class="q-ml-sm q-mr-xs" /> {{ event.bet_closes_at }}
                </div>
            </q-card-section>

            <q-separator />

            <!-- Description -->
            <q-card-section>
                <p class="text-body1 q-mb-md">{{ event.description }}</p>
            </q-card-section>

            <q-separator />

            <!-- Options -->
            <q-card-section>
                <div class="text-subtitle1 q-mb-md">Options</div>

                <!-- Responsive and centered options -->
                <div class="row q-col-gutter-lg justify-center">
                    <div
                        v-for="opt in event.options"
                        :key="opt.id"
                        class="col-xs-12 col-sm-6 col-md-6 col-lg-4"
                    >
                        <q-card bordered class="q-hoverable transition-all">
                            <q-img :src="`/storage/${opt.image}`" height="120px" contain class="rounded-borders-top" />
                            <q-card-section>
                                <div class="text-subtitle2 text-bold">{{ opt.label }}</div>
                                <div class="text-caption text-grey-7 q-mb-sm">{{ opt.description }}</div>
                                <div class="text-caption">
                                    <q-badge color="primary" class="q-mr-sm">Bets: {{ opt.total_bets }}</q-badge>
                                    <q-badge color="green" class="q-mr-sm">₹{{ opt.total_amount.toLocaleString() }}</q-badge>
                                    <q-badge color="amber" outline>Odds: {{ opt.odds }}x</q-badge>
                                </div>
                            </q-card-section>
                        </q-card>
                    </div>
                </div>
            </q-card-section>

            <!-- Terms -->
            <q-card-section>
                <q-card bordered class="bg-grey-1">
                    <q-card-section class="scroll" style="max-height: 260px;">
                        <div class="text-caption q-mb-sm" v-for="(line, i) in terms" :key="i">
                            {{ i + 1 }}) {{ line }}
                        </div>
                        <q-checkbox
                            class="q-mt-sm"
                            v-model="form.agree"
                            label="I Agree to the terms and confirm I meet the age requirements."
                        />
                    </q-card-section>
                </q-card>
            </q-card-section>

            <!-- Action Buttons -->
            <q-separator />
            <q-card-section class="row justify-center q-gutter-md">
                <q-btn label="Back" @click="$inertia.get(route('player.events.index'))" outline rounded color="accent" no-caps />
                <q-btn
                    label="Place a Bet"
                    rounded
                    color="primary"
                    no-caps
                    :disable="!form.agree"
                    @click="showDialog = true"
                />
            </q-card-section>
        </q-card>

        <!-- Dialog -->
        <q-dialog v-model="showDialog" persistent>
            <q-card class="q-pa-md" style="width: 100%; max-width: 450px;">
                <q-card-section>
                    <div class="text-h6">Place Your Bet</div>
                </q-card-section>

                <q-card-section>
                    <q-select
                        v-model="form.event_option_id"
                        :options="event.options.map(opt => ({ label: opt.label, value: opt.id }))"
                        label="Select Option"
                        dense
                        outlined
                        emit-value
                        map-options
                        :error="!!form.errors.event_option_id"
                        :error-message="form.errors.event_option_id"
                    />

                    <div v-if="selectedOption" class="q-mt-sm text-caption text-blue">
                        <q-icon name="trending_up" /> Odds for <strong>{{ selectedOption.label }}</strong>: {{ selectedOption.odds }}x
                    </div>

                    <q-input
                        v-model="form.amount"
                        type="number"
                        label="Enter Amount"
                        dense
                        outlined
                        class="q-mt-sm"
                        min="1"
                        :error="!!form.errors.amount"
                        :error-message="form.errors.amount"
                    />

                    <div v-if="selectedOption && form.amount" class="q-mt-md">
                        <q-badge color="green" outline>
                            Expected Payout: ₹{{ expectedPayout }}
                        </q-badge>
                    </div>
                </q-card-section>

                <q-card-actions align="right">
                    <q-btn flat label="Cancel" color="primary" v-close-popup />
                    <q-btn
                        label="Submit Bet"
                        color="primary"
                        :disable="!form.event_option_id || form.amount <= 0"
                        :loading="form.processing"
                        @click="onSubmit"
                    />
                </q-card-actions>
            </q-card>
        </q-dialog>
    </q-page>
</template>

<script setup>
import BackendLayout from "../../../../Layouts/BackendLayout.vue";
import Title from "../../../../Components/Event/Title.vue";
import Comment from "../../../../Components/Event/Comments.vue";
import PlaceBet from "../../../../Components/Event/PlaceBet.vue";

import { usePage, useForm } from '@inertiajs/vue3';
import {useQuasar} from "quasar";
import {ref,computed, onMounted} from 'vue'

defineOptions({ layout: BackendLayout });

const props = defineProps({
    event: Object,
});
const q = useQuasar();

const form=useForm({
    agree:false,
    event_option_id: null,
    amount: null,
})

const showDialog = ref(false)
// Get selected option from ID
const selectedOption = computed(() =>
    props.event.options.find(opt => opt.id === form.event_option_id)
)

// Compute expected payout
const expectedPayout = computed(() => {
    if (!selectedOption.value || !form.amount) return 0
    return (parseFloat(form.amount) * parseFloat(selectedOption.value.odds)).toFixed(2)
})

const terms = [
    'You must be at least 18 years of age or the legal gambling age in your jurisdiction to participate.',
    'By using this platform, you agree to abide by all applicable laws and regulations.',
    'All bets placed are final and cannot be canceled or changed once confirmed.',
    'The platform is not responsible for any technical issues that may affect betting outcomes.',
    'Gambling can be addictive - please play responsibly and seek help if needed.',
]

const onSubmit=e=>{
    q.dialog({
        title:'Confirmation',
        message:'Do you want to resubmit your application',
        ok:'Yes',
        cancel:'No'
    })
        .onOk(()=>{
            q.loading.show({
                boxClass: 'bg-grey-2 text-grey-9',
                spinnerColor: 'primary', message: ' Submitting...'
            });
            axios.post(route('player.events.place-bet',props.event),{
                agree:form.agree,
                event_option_id:form.event_option_id,
                amount:form.amount,
            }).then(res=>{
                const {token,amount,order_id} = res.data;
                initPaytm({token,order_id,amount})
            }).catch(err=>{
                console.log(err.response.data)
                q.notify({type:'negative',message:err?.response?.data?.message || err.toString()});
                if (!!err?.response?.data?.errors) {
                    form.errors = err.response.data.errors;
                }
            }).finally(()=>q.loading.hide());
        })
}
const initPaytm = data => {
    var config = {
        "root": "",
        "flow": "DEFAULT",
        "data": {
            "orderId": data.order_id, /* update order id */
            "token": data.token, /* update token value */
            "tokenType": 'TXN_TOKEN',
            "amount": data.amount /* update amount */
        },
        "handler": {
            "notifyMerchant": function (eventName, data) {
                console.log("notifyMerchant handler function called");
                console.log("eventName => ", eventName);
                console.log("data => ", data);
            }
        }
    };
    if (window.Paytm && window.Paytm.CheckoutJS) {
        window.Paytm.CheckoutJS.init(config)
            .then(function onSuccess() {
                window.Paytm.CheckoutJS.invoke();
            }).catch(function onError(error) {
            console.log("error => ", error);
            router.replace(route('player.events.show', props.event))
        });
    }
}

onMounted(() => {
    let scriptElement = document.createElement('script')
    scriptElement.setAttribute('src', 'https://securegw-stage.paytm.in/merchantpgpui/checkoutjs/merchants/Resell00448805757124.js')
    // scriptElement.setAttribute('src', 'https://securestage.paytmpayments.com/merchantpgpui/checkoutjs/merchants/Resell00448805757124.js')
    document.head.appendChild(scriptElement)
})

</script>
<style scoped>
.text-bold {
    font-weight: 600;
}

.scroll-row {
    display: flex;
    overflow-x: auto;
    padding-bottom: 8px;
    -webkit-overflow-scrolling: touch;
}

.option-card {
    flex: 0 0 auto;
}


</style>
