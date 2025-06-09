<template>
    <q-header style="height: 70px;"  class="bg-white text-dark flex items-center">
        <q-toolbar style="padding-left: 16px;padding-right: 16px" class="container q-px-md">
            <slot name="burger">

            </slot>
            <q-toolbar-title>
                <div class="flex items-center gap-x-3 cursor-pointer" @click="$inertia.get('/')">
                    <q-img width="40px" height="40px" alt="logo" src="/images/logo.svg" />
                    <div style="line-height: 1" class="text-lg text-grey-10 text-weight-medium">
                        We Bet<br>
                        <span class="text-sm text-grey-5">(Bet On Any Events)</span>
                    </div>
                </div>
            </q-toolbar-title>

            <q-item flat v-if="!!$page.props.auth?.user">
                <q-item-section>
                    <div class="column">
                        <div class="text-dark q-pa-none">{{$page.props.auth?.user?.name}}</div>
                        <div style="font-size: 12px;color: #9b9b9b;margin-top: -6px"
                             class="text-dark text-caption q-pa-none">{{$page.props.auth?.user?.name}}</div>
                    </div>
                </q-item-section>
                <q-item-section side>
                    <q-icon size="32px" name="account_circle"/>
                </q-item-section>
                <q-menu>
                    <slot name="right-menu"/>
                </q-menu>
            </q-item>

            <div v-else class="flex q-gutter-sm">
                <q-btn @click="$inertia.get(route('login'))" class="text-dark sized-btn" color="btn-primary" outline label="Login" no-caps/>
                <q-btn v-if="$q.screen.gt.sm" @click="$inertia.get(route('register.create'))" color="btn-primary sized-btn"  label="Register" no-caps/>
            </div>


        </q-toolbar>
    </q-header>
</template>
<script setup>
import {reactive} from "vue";

const props = defineProps(['hideToggleBtn']);
const emit=defineEmits(['onLeftDrawerToggle'])


const toggleLeftDrawer=()=> emit('onLeftDrawerToggle');


</script>
