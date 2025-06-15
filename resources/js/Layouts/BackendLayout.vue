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
                <div class="column items-center q-gutter-md q-pa-lg bg-gray-600 text-white">

                    <q-img src="/images/logo.svg" width="46px"/>
                    <div style="line-height: 1" class="text-lg text-grey-3 text-weight-medium text-center">
                        WeBet<br>
                        <span class="text-sm text-grey-10">(Bet On Any Events)</span>
                    </div>
                </div>

                <div v-if="player">
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

                    <q-item :active="route().current()==='player.events.index'" active
                            active-class="active-menu text-accent"
                            clickable
                            @click="$inertia.get(route('player.events.index'))">
                        <q-item-section avatar>
                            <q-icon>

                                <svg v-if="route().current()==='player.events.index'" width="800px" height="800px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4 13.9999L5.57465 20.2985C5.61893 20.4756 5.64107 20.5642 5.66727 20.6415C5.92317 21.397 6.60352 21.9282 7.39852 21.9933C7.4799 21.9999 7.5712 21.9999 7.75379 21.9999C7.98244 21.9999 8.09677 21.9999 8.19308 21.9906C9.145 21.8982 9.89834 21.1449 9.99066 20.193C10 20.0967 10 19.9823 10 19.7537V5.49991M18.5 13.4999C20.433 13.4999 22 11.9329 22 9.99991C22 8.06691 20.433 6.49991 18.5 6.49991M10.25 5.49991H6.5C4.01472 5.49991 2 7.51463 2 9.99991C2 12.4852 4.01472 14.4999 6.5 14.4999H10.25C12.0164 14.4999 14.1772 15.4468 15.8443 16.3556C16.8168 16.8857 17.3031 17.1508 17.6216 17.1118C17.9169 17.0756 18.1402 16.943 18.3133 16.701C18.5 16.4401 18.5 15.9179 18.5 14.8736V5.1262C18.5 4.08191 18.5 3.55976 18.3133 3.2988C18.1402 3.05681 17.9169 2.92421 17.6216 2.88804C17.3031 2.84903 16.8168 3.11411 15.8443 3.64427C14.1772 4.55302 12.0164 5.49991 10.25 5.49991Z" stroke="blue" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>


                                <svg v-else width="800px" height="800px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4 13.9999L5.57465 20.2985C5.61893 20.4756 5.64107 20.5642 5.66727 20.6415C5.92317 21.397 6.60352 21.9282 7.39852 21.9933C7.4799 21.9999 7.5712 21.9999 7.75379 21.9999C7.98244 21.9999 8.09677 21.9999 8.19308 21.9906C9.145 21.8982 9.89834 21.1449 9.99066 20.193C10 20.0967 10 19.9823 10 19.7537V5.49991M18.5 13.4999C20.433 13.4999 22 11.9329 22 9.99991C22 8.06691 20.433 6.49991 18.5 6.49991M10.25 5.49991H6.5C4.01472 5.49991 2 7.51463 2 9.99991C2 12.4852 4.01472 14.4999 6.5 14.4999H10.25C12.0164 14.4999 14.1772 15.4468 15.8443 16.3556C16.8168 16.8857 17.3031 17.1508 17.6216 17.1118C17.9169 17.0756 18.1402 16.943 18.3133 16.701C18.5 16.4401 18.5 15.9179 18.5 14.8736V5.1262C18.5 4.08191 18.5 3.55976 18.3133 3.2988C18.1402 3.05681 17.9169 2.92421 17.6216 2.88804C17.3031 2.84903 16.8168 3.11411 15.8443 3.64427C14.1772 4.55302 12.0164 5.49991 10.25 5.49991Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>


                            </q-icon>

                        </q-item-section>
                        <q-item-section>
                            <q-item-label>Events</q-item-label>
                        </q-item-section>
                    </q-item>

                    <q-separator class="q-my-sm"/>

                    <q-item :active="route().current()==='player.bets.index'" active
                            active-class="active-menu text-accent"
                            clickable
                            @click="$inertia.get(route('player.bets.index'))">
                        <q-item-section avatar>
                            <q-icon>

                                <svg v-if="route().current()==='player.bets.index'" width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
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

                </div>

                <div v-if="admin">
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

                    <q-item :active="route().current()==='admin.events.index'" active
                            active-class="active-menu text-accent"
                            clickable
                            @click="$inertia.get(route('admin.events.index'))">
                        <q-item-section avatar>
                            <q-icon>

                                <svg v-if="route().current()==='admin.events.index'" width="800px" height="800px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4 13.9999L5.57465 20.2985C5.61893 20.4756 5.64107 20.5642 5.66727 20.6415C5.92317 21.397 6.60352 21.9282 7.39852 21.9933C7.4799 21.9999 7.5712 21.9999 7.75379 21.9999C7.98244 21.9999 8.09677 21.9999 8.19308 21.9906C9.145 21.8982 9.89834 21.1449 9.99066 20.193C10 20.0967 10 19.9823 10 19.7537V5.49991M18.5 13.4999C20.433 13.4999 22 11.9329 22 9.99991C22 8.06691 20.433 6.49991 18.5 6.49991M10.25 5.49991H6.5C4.01472 5.49991 2 7.51463 2 9.99991C2 12.4852 4.01472 14.4999 6.5 14.4999H10.25C12.0164 14.4999 14.1772 15.4468 15.8443 16.3556C16.8168 16.8857 17.3031 17.1508 17.6216 17.1118C17.9169 17.0756 18.1402 16.943 18.3133 16.701C18.5 16.4401 18.5 15.9179 18.5 14.8736V5.1262C18.5 4.08191 18.5 3.55976 18.3133 3.2988C18.1402 3.05681 17.9169 2.92421 17.6216 2.88804C17.3031 2.84903 16.8168 3.11411 15.8443 3.64427C14.1772 4.55302 12.0164 5.49991 10.25 5.49991Z" stroke="blue" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>


                                <svg v-else width="800px" height="800px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4 13.9999L5.57465 20.2985C5.61893 20.4756 5.64107 20.5642 5.66727 20.6415C5.92317 21.397 6.60352 21.9282 7.39852 21.9933C7.4799 21.9999 7.5712 21.9999 7.75379 21.9999C7.98244 21.9999 8.09677 21.9999 8.19308 21.9906C9.145 21.8982 9.89834 21.1449 9.99066 20.193C10 20.0967 10 19.9823 10 19.7537V5.49991M18.5 13.4999C20.433 13.4999 22 11.9329 22 9.99991C22 8.06691 20.433 6.49991 18.5 6.49991M10.25 5.49991H6.5C4.01472 5.49991 2 7.51463 2 9.99991C2 12.4852 4.01472 14.4999 6.5 14.4999H10.25C12.0164 14.4999 14.1772 15.4468 15.8443 16.3556C16.8168 16.8857 17.3031 17.1508 17.6216 17.1118C17.9169 17.0756 18.1402 16.943 18.3133 16.701C18.5 16.4401 18.5 15.9179 18.5 14.8736V5.1262C18.5 4.08191 18.5 3.55976 18.3133 3.2988C18.1402 3.05681 17.9169 2.92421 17.6216 2.88804C17.3031 2.84903 16.8168 3.11411 15.8443 3.64427C14.1772 4.55302 12.0164 5.49991 10.25 5.49991Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>


                            </q-icon>

                        </q-item-section>
                        <q-item-section>
                            <q-item-label>Events</q-item-label>
                        </q-item-section>
                    </q-item>

                    <q-separator class="q-my-sm"/>

                    <q-item :active="route().current()==='admin.bets.index'" active
                            active-class="active-menu text-accent"
                            clickable
                            @click="$inertia.get(route('admin.bets.index'))">
                        <q-item-section avatar>
                            <q-icon>

                                <svg v-if="route().current()==='admin.bets.index'" width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
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

                    <q-item :active="route().current()==='admin.payout.index'" active
                            active-class="active-menu text-accent"
                            clickable
                            @click="$inertia.get(route('admin.payout.index'))">
                        <q-item-section avatar>

                            <svg v-if="route().current()==='admin.payout.index'" width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
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

                </div>

            </q-list>
        </q-drawer>


        <q-page-container>
            <slot/>
        </q-page-container>

        <q-footer  class="bg-white  q-pa-md">
            <Footer/>
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


const admin=computed(()=>{
    const roles = usePage().props.roles;
    return roles.find(item => item === 'Admin');
})

const player=computed(()=>{
    const roles = usePage().props.roles;
    return roles.find(item => item === 'Player');
})

</script>
