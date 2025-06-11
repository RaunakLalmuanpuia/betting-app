<template>
    <q-page padding>
        <q-card class="bg-white text-dark shadow-2">
            <!-- Smaller Event Banner -->
            <q-img :src="event.image" height="200px" contain />

            <q-card-section class="text-h5 text-center">
                {{ event.title }}
            </q-card-section>

            <q-card-section>
                <p class="text-body1">{{ event.description }}</p>
                <p class="text-caption text-grey-7">
                    <strong>Status:</strong> {{ event.status }}<br>
                    <strong>Betting Closes At:</strong> {{ event.bet_closes_at }}
                </p>
            </q-card-section>

            <q-separator />

            <q-card-section>
                <h6 class="text-subtitle1 q-mb-md">Options</h6>

                <!-- Horizontally scrollable options -->
                <div class="scroll-row">
                    <div
                        v-for="opt in event.options"
                        :key="opt.id"
                        class="option-card"
                    >
                        <q-card flat bordered class="q-ma-sm" style="min-width: 250px;">
                            <q-img :src="opt.image" height="120px" contain />

                            <q-card-section>
                                <div class="text-bold">{{ opt.label }}</div>
                                <div class="text-caption text-grey">
                                    {{ opt.description }}
                                </div>

                                <div class="q-mt-sm text-caption">
                                    <strong>Total Bets:</strong> {{ opt.total_bets }}<br>
                                    <strong>Total Amount:</strong> ₹{{ opt.total_amount.toLocaleString() }}<br>
                                    <strong>Odds:</strong> {{ opt.odds }}x
                                </div>
                            </q-card-section>
                        </q-card>
                    </div>
                    <div
                        class="option-card"
                    >
                        <q-card flat bordered class="q-ma-sm" style="min-width: 250px;">
                            <q-card-section>
                                <div class="col-12 column">
                                    <p>1) You must be at least 18 years of age or the legal gambling age in your jurisdiction to participate.</p>
                                    <p>2) By using this platform, you agree to abide by all applicable laws and regulations.</p>
                                    <p>3) All bets placed are final and cannot be canceled or changed once confirmed.</p>
                                    <p>4) The platform reserves the right to suspend or terminate accounts for suspicious activity.</p>
                                    <p>5) Winnings are subject to verification and may be withheld if terms are violated.</p>
                                    <p>6) You are responsible for maintaining the confidentiality of your account credentials.</p>
                                    <p>7) The platform is not responsible for any technical issues that may affect betting outcomes.</p>
                                    <p>8) Promotional offers are subject to specific terms and wagering requirements.</p>
                                    <p>9) Gambling can be addictive - please play responsibly and seek help if needed.</p>
                                    <p>10) The platform may modify these terms at any time, with changes effective immediately upon posting.</p>
                                </div>

                                <div class="col-12">
                                    <q-checkbox class="q-pl-none" v-model="form.agree" label="I Agree to the above terms and conditions and confirm I meet the age requirements." />
                                </div>
                            </q-card-section>
                        </q-card>
                    </div>
                </div>
            </q-card-section>

            <q-separator />


            <div class="col-12 flex justify-center items-center q-gutter-lg p-4">
                <q-btn class="sized-btn" @click="$inertia.get(route('player.events.index'))" rounded outline label="Back" color="accent" no-caps/>
                <q-btn icon="casino" :disable="!!!form.agree" @click="showDialog = true" class="sized-btn" type="submit" rounded  label="Place a Bet" color="primary"  no-caps/>
            </div>
        </q-card>
        <q-dialog v-model="showDialog">
            <q-card class="q-pa-md" style="min-width: 300px; max-width: 500px;">
                <q-card-section>
                    <div class="text-h6">Place Your Bet</div>
                </q-card-section>

                <q-card-section>
                    <q-select
                        v-model="form.event_option_id"
                        :options="props.event.options.map(opt => ({ label: opt.label, value: opt.id }))"
                        label="Select Option"
                        dense outlined emit-value map-options
                        :error="!!form.errors.event_option_id"
                        :error-message="form.errors.event_option_id"
                    />

                    <!-- Show odds immediately -->
                    <div v-if="selectedOption" class="q-mt-sm text-subtitle2">
                        <q-badge color="blue" outline>
                            Odds for "{{ selectedOption.label }}": <strong>{{ selectedOption.odds }}x</strong>
                        </q-badge>
                    </div>


                    <q-input
                        v-model="form.amount"
                        type="number"
                        label="Enter Amount"
                        dense outlined
                        class="q-mt-sm"
                        min="1"
                        :error="!!form.errors.amount"
                        :error-message="form.errors.amount"
                    />

                    <!-- Expected payout -->
                    <div v-if="selectedOption && form.amount" class="q-mt-md text-caption">
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
                        @click="onSubmit"
                        :loading="form.processing"
                    />
                </q-card-actions>
            </q-card>
        </q-dialog>


    </q-page>
</template>
<script setup>
import BackendLayout from "../../../../Layouts/BackendLayout.vue";
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
