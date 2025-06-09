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
                        <q-icon>
                            <svg v-if="route().current()==='dashboard'" width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path stroke="#306ADB" stroke-width="1.5" d="M3.75 3.75h8.5v5.5h-8.5zM15.75 3.75H21A3.25 3.25 0 0 1 24.25 7v8.25h-8.5V3.75zM15.75 18.75h8.5V21A3.25 3.25 0 0 1 21 24.25h-5.25v-5.5zM3.75 12.75h8.5v11.5h-8.5z"/>
                            </svg>
                            <svg v-else width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path stroke="#191C51" stroke-width="1.5" d="M3.75 3.75h8.5v5.5h-8.5zM15.75 3.75H21A3.25 3.25 0 0 1 24.25 7v8.25h-8.5V3.75z"/>
                                <path d="M15.75 18.75h8.5V21A3.25 3.25 0 0 1 21 24.25h-5.25v-5.5z" stroke="#D81D1D" stroke-width="1.5"/>
                                <path stroke="#191C51" stroke-width="1.5" d="M3.75 12.75h8.5v11.5h-8.5z"/>
                            </svg>
                        </q-icon>
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
                        <q-icon>

                            <svg v-if="route().current()==='events.index'" width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M17.5 13.6429V24C17.5 24.2761 17.2761 24.5 17 24.5H6C5.72386 24.5 5.5 24.2761 5.5 24V9C5.5 8.72386 5.72386 8.5 6 8.5H13.5L15 7H6C4.89543 7 4 7.89543 4 9V24C4 25.1046 4.89543 26 6 26H17C18.1046 26 19 25.1046 19 24V12.3571L17.5 13.6429Z" fill="#306ADB"/>
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M12.5 10H12C10.8954 10 10 10.8954 10 12V14C10 15.1046 10.8954 16 12 16H14C14.9319 16 15.715 15.3626 15.937 14.5H14H12.5H12C11.7239 14.5 11.5 14.2761 11.5 14V12C11.5 11.7239 11.7239 11.5 12 11.5H12.5V10Z" fill="#306ADB"/>
                                <rect x="8" y="19.5" width="1.5" height="8" transform="rotate(-90 8 19.5)" fill="#306ADB"/>
                                <rect x="11" y="22.5" width="1.5" height="5" transform="rotate(-90 11 22.5)" fill="#306ADB"/>
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M14.9143 11.1281L14.9143 11.8261L15.6305 11.826L22.696 4.90519L21.989 4.19823L14.9143 11.1281ZM22 2.08789L13.4144 10.4977L13.4141 13.3262L16.2427 13.3259L24.8283 4.91616L22 2.08789Z" fill="#306ADB"/>
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M20.5 10.6429V21C20.5 21.2761 20.2761 21.5 20 21.5V22V23C21.1046 23 22 22.1046 22 21V9.35714L20.5 10.6429ZM7 8H8.5V6C8.5 5.72386 8.72386 5.5 9 5.5H16.5L18 4H9C7.89543 4 7 4.89543 7 6V8Z" fill="#306ADB"/>
                            </svg>
                            <svg v-else width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M17.5 13.6429V24C17.5 24.2761 17.2761 24.5 17 24.5H6C5.72386 24.5 5.5 24.2761 5.5 24V9C5.5 8.72386 5.72386 8.5 6 8.5H13.5L15 7H6C4.89543 7 4 7.89543 4 9V24C4 25.1046 4.89543 26 6 26H17C18.1046 26 19 25.1046 19 24V12.3571L17.5 13.6429Z" fill="#191C51"/>
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M12.5 10H12C10.8954 10 10 10.8954 10 12V14C10 15.1046 10.8954 16 12 16H14C14.9319 16 15.715 15.3626 15.937 14.5H14H12.5H12C11.7239 14.5 11.5 14.2761 11.5 14V12C11.5 11.7239 11.7239 11.5 12 11.5H12.5V10Z" fill="#D81D1D"/>
                                <rect x="8" y="19.5" width="1.5" height="8" transform="rotate(-90 8 19.5)" fill="#191C51"/>
                                <rect x="11" y="22.5" width="1.5" height="5" transform="rotate(-90 11 22.5)" fill="#191C51"/>
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M14.9143 11.1281L14.9143 11.8261L15.6305 11.826L22.696 4.90519L21.989 4.19823L14.9143 11.1281ZM22 2.08789L13.4144 10.4977L13.4141 13.3262L16.2427 13.3259L24.8283 4.91616L22 2.08789Z" fill="#D81D1D"/>
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M20.5 10.6429V21C20.5 21.2761 20.2761 21.5 20 21.5V22V23C21.1046 23 22 22.1046 22 21V9.35714L20.5 10.6429ZM7 8H8.5V6C8.5 5.72386 8.72386 5.5 9 5.5H16.5L18 4H9C7.89543 4 7 4.89543 7 6V8Z" fill="#191C51"/>
                            </svg>

                        </q-icon>

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
                        <q-icon>

                            <svg v-if="route().current()==='bets.index'" width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M6 4.5h13.5a.5.5 0 0 1 .5.5v6.938l1.5-1.52V5a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v12a8 8 0 0 0 8 8h7.5a2 2 0 0 0 2-2v-3.038l-1.5 1.52V23a.5.5 0 0 1-.5.5H12A6.5 6.5 0 0 1 5.5 17V5a.5.5 0 0 1 .5-.5z" fill="#306ADB"/>
                                <path fill="#306ADB" d="M5 18h7v1.5H5z"/>
                                <path fill="#306ADB" d="M11 24v-6h1.5v6z"/>
                                <circle cx="12.55" cy="9.25" r="1.65" stroke="#306ADB" stroke-width="1.2"/>
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M15.457 15a3.496 3.496 0 0 0-2.873-1.5c-1.19 0-2.24.593-2.873 1.5H8a5.001 5.001 0 0 1 9.168 0h-1.711zM15.914 20.128v.698h.717l7.065-6.92-.707-.708-7.075 6.93zM23 11.088l-8.586 8.41v2.828h2.829l8.585-8.41L23 11.088z" fill="#306ADB"/>
                            </svg>
                            <svg v-else width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M6 4.5h13.5a.5.5 0 0 1 .5.5v6.938l1.5-1.52V5a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v12a8 8 0 0 0 8 8h7.5a2 2 0 0 0 2-2v-3.038l-1.5 1.52V23a.5.5 0 0 1-.5.5H12A6.5 6.5 0 0 1 5.5 17V5a.5.5 0 0 1 .5-.5z" fill="#191C51"/>
                                <path fill="#191C51" d="M5 18h7v1.5H5z"/>
                                <path fill="#191C51" d="M11 24v-6h1.5v6z"/>
                                <circle cx="12.551" cy="9.25" r="1.65" stroke="#191C51" stroke-width="1.2"/>
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M15.457 15a3.496 3.496 0 0 0-2.873-1.5c-1.19 0-2.24.593-2.873 1.5H8a5.001 5.001 0 0 1 9.168 0h-1.711z" fill="#191C51"/>
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M15.914 20.128v.698h.717l7.065-6.92-.707-.708-7.075 6.93zM23 11.088l-8.586 8.41v2.828h2.829l8.585-8.41L23 11.088z" fill="#D81D1D"/>
                            </svg>

                        </q-icon>
<!--                        <ZDashboard :active="route().current()==='bets.index'"/>-->
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
                        <svg v-if="route().current()==='payout.index'" width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M6 4.5h13.5a.5.5 0 0 1 .5.5v6.938l1.5-1.52V5a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v12a8 8 0 0 0 8 8h7.5a2 2 0 0 0 2-2v-3.038l-1.5 1.52V23a.5.5 0 0 1-.5.5H12A6.5 6.5 0 0 1 5.5 17V5a.5.5 0 0 1 .5-.5z" fill="#306ADB"/>
                            <path fill="#306ADB" d="M5 18h7v1.5H5z"/>
                            <path fill="#306ADB" d="M11 24v-6h1.5v6z"/>
                            <circle cx="12.55" cy="9.25" r="1.65" stroke="#306ADB" stroke-width="1.2"/>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M15.457 15a3.496 3.496 0 0 0-2.873-1.5c-1.19 0-2.24.593-2.873 1.5H8a5.001 5.001 0 0 1 9.168 0h-1.711zM15.914 20.128v.698h.717l7.065-6.92-.707-.708-7.075 6.93zM23 11.088l-8.586 8.41v2.828h2.829l8.585-8.41L23 11.088z" fill="#306ADB"/>
                        </svg>
                        <svg v-else width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M6 4.5h13.5a.5.5 0 0 1 .5.5v6.938l1.5-1.52V5a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v12a8 8 0 0 0 8 8h7.5a2 2 0 0 0 2-2v-3.038l-1.5 1.52V23a.5.5 0 0 1-.5.5H12A6.5 6.5 0 0 1 5.5 17V5a.5.5 0 0 1 .5-.5z" fill="#191C51"/>
                            <path fill="#191C51" d="M5 18h7v1.5H5z"/>
                            <path fill="#191C51" d="M11 24v-6h1.5v6z"/>
                            <circle cx="12.551" cy="9.25" r="1.65" stroke="#191C51" stroke-width="1.2"/>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M15.457 15a3.496 3.496 0 0 0-2.873-1.5c-1.19 0-2.24.593-2.873 1.5H8a5.001 5.001 0 0 1 9.168 0h-1.711z" fill="#191C51"/>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M15.914 20.128v.698h.717l7.065-6.92-.707-.708-7.075 6.93zM23 11.088l-8.586 8.41v2.828h2.829l8.585-8.41L23 11.088z" fill="#D81D1D"/>
                        </svg>
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
