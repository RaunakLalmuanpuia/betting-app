<template>
    <q-card class="q-pa-md q-mx-auto" style="max-width: 400px">
        <!-- Header -->
        <div class="q-gutter-sm flex items-center">
            <q-img
                :src="`/storage/${selectedOption.image}`"
                class="rounded-borders"
                style="width: 64px; height: 64px"
                spinner-color="primary"
                :ratio="1"
            />
            <div class="q-ml-sm">
                <div class="text-subtitle1">{{ event.title }}</div>
            </div>
        </div>

        <!-- Options Buttons -->
        <div class="q-my-md row q-col-gutter-sm">
            <q-btn
                v-for="option in event.options"
                :key="option.id"
                @click="() => {
          selectedOption = option;
          form.event_option_id = option.id;
        }"
                :label="option.label"
                :color="selectedOption?.id === option.id ? 'primary' : 'blue-2'"
                :text-color="selectedOption?.id === option.id ? 'white' : 'primary'"
                class="col"
                flat
                rounded
                size="lg"
            />
        </div>

        <!-- Amount Display -->
        <q-input
            v-model.number="form.amount"
            label="Amount"
            outlined
            input-class="text-h5 text-right text-weight-bold"
            class="q-my-md"
            placeholder="₹0"
            type="number"
        />

        <!-- Odds and Payout -->
        <div class="row justify-between items-center q-my-md">
            <div>
                <div class="text-caption text-grey">Odds</div>
                <div class="text-body1 text-weight-medium">
                    Payout if {{ selectedOption?.label || '---' }}
                </div>
            </div>
            <div class="text-right">
                <div class="text-h6 text-weight-medium">
                    {{ selectedOption ? selectedOption.odds : '--' }}
                </div>
                <div class="text-h4 text-positive text-weight-bold">
                    ₹{{ selectedOption ? expectedPayout : '0' }}
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <q-btn
            label="Make Bet"
            color="green"
            class="full-width q-mt-md"
            unelevated
            rounded
            size="lg"
            @click="onSubmit"
        />
    </q-card>
</template>


<script setup>
import {ref, computed, onMounted} from 'vue'
import {useForm} from "@inertiajs/vue3";
import {useQuasar} from "quasar";
const q = useQuasar();

const props = defineProps({
    event: Object,
})
const form=useForm({
    event_option_id: null,
    amount: null,
})

// Default to "Yes" option if available
const selectedOption = ref(
    props.event.options.find(opt => opt.label.toLowerCase() === 'yes') || props.event.options[0]
)

// Compute expected payout
const expectedPayout = computed(() => {
    if (!selectedOption.value || !form.amount) return 0
    return (parseFloat(form.amount) * parseFloat(selectedOption.value.odds))
})

const onSubmit=e=>{
    q.loading.show({
        boxClass: 'bg-grey-2 text-grey-9',
        spinnerColor: 'primary', message: ' Submitting...'
    });
    axios.post(route('player.events.place-bet',props.event),{
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
