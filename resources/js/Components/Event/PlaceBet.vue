
<template>
    <div class="bg-white min-h-screen">
        <div class="max-w-sm mx-auto bg-white rounded-2xl p-6 space-y-6 shadow-sm">
            <!-- Header -->
            <div class="flex items-center space-x-4 w-full">
                <img
                    alt="Image showing US and Iran flags with a nuclear symbol in the center on a wooden background"
                    class="w-16 h-16 rounded-xl object-cover flex-shrink-0"
                    :src="`/storage/${event.image}`"
                />
                <div class="flex-1 min-w-0">
                    <p class="text-base text-black leading-tight">
                        {{ event.title }}
                    </p>
                </div>
            </div>

            <!-- Options Buttons -->
            <div class="flex space-x-4">
                <button
                    v-for="option in event.options"
                    :key="option.id"
                    @click="() => {
                      selectedOption = option;
                      form.event_option_id = option.id;
                    }"
                    :class="[
                          'flex-1 rounded-xl py-4 text-lg font-normal leading-tight flex justify-center items-center transition',
                          selectedOption?.id === option.id
                            ? 'bg-blue-600 text-white'
                            : idx === 0
                            ? 'bg-blue-100 text-blue-600'
                            : 'bg-purple-100 text-purple-600'
                        ]"
                    >
                    <span>{{ option.label }}</span>
                </button>
            </div>

            <!-- Amount Display -->
            <div class="border border-gray-300 rounded-xl p-4 flex justify-between items-center">
                <span class="text-base font-medium text-black">Amount</span>
                <input
                    v-model.number="form.amount"
                    class="text-3xl font-extrabold text-black text-right bg-transparent border-none focus:outline-none w-32"
                    placeholder="$0"
                />
            </div>


            <!-- Odds and Payout -->
            <div class="flex justify-between items-center">
                <div class="flex flex-col space-y-2">
                    <span class="text-gray-500 text-base font-normal">Odds</span>
                    <span class="text-gray-500 text-base font-normal flex items-center cursor-default select-none">
                        Payout if {{ selectedOption?.label || '---' }}
                        <i class="fas fa-chevron-down ml-1 text-sm"></i>
                    </span>
                </div>
                <div class="flex flex-col items-end space-y-2">
                  <span class="text-black text-lg font-semibold">
                    {{ selectedOption ? selectedOption.odds  : '--' }}
                  </span>
                    <span class="text-green-500 text-4xl font-extrabold">
                    ${{ selectedOption ? expectedPayout : '0' }}
                  </span>
                </div>
            </div>

            <!-- Signup Button -->
            <button @click="onSubmit"
                class="w-full bg-green-500 text-white text-lg font-normal rounded-xl py-4">
                Make Bet
            </button>
        </div>
    </div>



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
    q.dialog({
        title:'Confirmation',
        message:'Do you want to make the bet',
        ok:'Yes',
        cancel:'No'
    })
        .onOk(()=>{
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

</style>



