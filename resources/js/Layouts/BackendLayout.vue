<template>
    <q-layout view="lHh LpR lff">

        <Header class="print-hide">
            <template #burger>
                <q-btn v-if="!!$page.props?.auth?.user" dense flat round icon="menu" @click="state.leftDrawerOpen=!state.leftDrawerOpen" />
            </template>
            <template #right-menu>
                <q-list >
                    <q-item  v-close-popup clickable @click="$inertia.get(route('profile.edit'))">
                        <q-item-section>
                            <q-item-label>My Profile</q-item-label>
                        </q-item-section>
                    </q-item>
                    <q-item  v-close-popup clickable @click="$inertia.get(route('profile.edit-password'))">
                        <q-item-section>
                            <q-item-label>Change Password</q-item-label>
                        </q-item-section>
                    </q-item>
                    <q-item  v-close-popup clickable @click="$inertia.delete(route('login.destroy'))">
                        <q-item-section>
                            <q-item-label>Logout</q-item-label>
                        </q-item-section>
                    </q-item>
                </q-list>
            </template>

        </Header>

        <q-drawer width="250"  class="print-hide bg-transparent"  v-model="state.leftDrawerOpen" side="left">
            <q-list class="bg-transparent full-height">
                <!--        <q-img class="q-ma-md" width="250px" height="43px" src="/images/logo.png"/>-->
                <div class="column items-center q-gutter-md q-pa-lg bg-primary text-white">

                    <q-img src="/images/tax-logo.png" width="46px"/>
                    <div style="line-height: 1" class="text-lg text-grey-3 text-weight-medium text-center">
                        Taxation Department
                        <span class="text-sm text-grey-7">(Government of Mizoram)</span>
                    </div>
                </div>
                <q-item :active="route().current()==='dashboard'" active
                        active-class="active-menu text-accent"
                        clickable
                        @click="$inertia.get(route('dashboard'))">
                    <q-item-section avatar>
                        <ZDashboard :active="route().current()==='dashboard'"/>
                    </q-item-section>
                    <q-item-section>
                        <q-item-label>Dashboard</q-item-label>
                    </q-item-section>
                </q-item>

                <q-separator class="q-my-sm"/>

                <q-item :active="route().current()==='events.index'" active
                        active-class="active-menu text-accent"
                        clickable
                        @click="$inertia.get(route('events.index'))">
                    <q-item-section avatar>
                        <ZDashboard :active="route().current()==='events.index'"/>
                    </q-item-section>
                    <q-item-section>
                        <q-item-label>Events</q-item-label>
                    </q-item-section>
                </q-item>

                <q-separator class="q-my-sm"/>

                <q-item :active="route().current()==='bets.index'" active
                        active-class="active-menu text-accent"
                        clickable
                        @click="$inertia.get(route('bets.index'))">
                    <q-item-section avatar>
                        <ZDashboard :active="route().current()==='bets.index'"/>
                    </q-item-section>
                    <q-item-section>
                        <q-item-label>Bets</q-item-label>
                    </q-item-section>
                </q-item>

                <q-separator class="q-my-sm"/>

                <q-item :active="route().current()==='payout.index'" active
                        active-class="active-menu text-accent"
                        clickable
                        @click="$inertia.get(route('payout.index'))">
                    <q-item-section avatar>
                        <ZDashboard :active="route().current()==='payout.index'"/>
                    </q-item-section>
                    <q-item-section>
                        <q-item-label>Payout</q-item-label>
                    </q-item-section>
                </q-item>
            </q-list>
        </q-drawer>


        <q-page-container>
            <slot/>
        </q-page-container>

        <q-footer  class="bg-white  q-pa-md">
<!--            <Footer/>-->
        </q-footer>

    </q-layout>
</template>

<script setup>
import {onMounted, reactive, watch,computed} from 'vue'
import Header from "../Components/Common/Header.vue"
import Footer from "../Components/Common/Footer.vue"
// import SideNav from "@/Components/Backend/SideNav.vue";
import {usePage} from "@inertiajs/vue3";
import {useQuasar} from "quasar";

const state=reactive({
    leftDrawerOpen:true,
    rightDrawerOpen:true,
})
const q = useQuasar();

const notification=computed(()=>usePage().props.flash_notification)



watch(notification,(newVal,oldVal)=>{
    if (newVal) {
        const {type, message} = newVal;
        q.notify({type,message,closeBtn:true,classes:'accent'})
    }
    console.log('test')

},{immediate:true})


</script>
