<script setup>
import { ref } from 'vue'



const menuVisible = ref(false)
</script>

<template>
        <q-toolbar class="container q-px-md" style="padding-left: 16px; padding-right: 16px;">
            <!-- Logo -->
            <q-toolbar-title>
                <q-img
                    class="cursor-pointer"
                    @click="$inertia.get('/')"
                    width="240px"
                    height="43px"
                    alt="logo"
                    src="/images/logo.png"
                />
            </q-toolbar-title>

            <!-- Authenticated User Info -->
            <div v-if="$page.props.auth?.user" class="flex items-center q-gutter-sm">
                <div class="column items-end q-mr-sm">
                    <div class="text-dark">{{ $page.props.auth.user.name }}</div>
                    <div style="font-size: 12px; color: #9b9b9b; margin-top: -6px;" class="text-caption">
                        {{ $page.props.auth.user.name }}
                    </div>
                </div>

                <!-- Menu Trigger -->
                <q-btn flat round dense @click="menuVisible = !menuVisible">
                    <q-icon size="32px" name="account_circle" />
                </q-btn>

                <!-- Dropdown Menu -->
                <q-menu v-model="menuVisible" anchor="bottom right" self="top right">
                    <q-list>
                        <q-item clickable v-close-popup @click="$inertia.get(route('profile.edit'))">
                            <q-item-section>
                                <q-item-label>My Profile</q-item-label>
                            </q-item-section>
                        </q-item>
                        <q-item clickable v-close-popup @click="$inertia.get(route('profile.edit-password'))">
                            <q-item-section>
                                <q-item-label>Change Password</q-item-label>
                            </q-item-section>
                        </q-item>
                        <q-item clickable v-close-popup @click="$inertia.delete(route('login.destroy'))">
                            <q-item-section>
                                <q-item-label>Logout</q-item-label>
                            </q-item-section>
                        </q-item>
                    </q-list>
                </q-menu>
            </div>

            <!-- Guest Buttons -->
            <div v-else class="flex q-gutter-sm">
                <q-btn
                    @click="$inertia.get(route('login'))"
                    class="text-dark sized-btn"
                    color="btn-primary"
                    outline
                    label="Login"
                    no-caps
                />
                <q-btn
                    v-if="$q.screen.gt.sm"
                    @click="$inertia.get(route('register.create'))"
                    class="text-dark sized-btn"
                    color="btn-primary"
                    outline
                    label="Register"
                    no-caps
                />
            </div>
        </q-toolbar>
</template>

<style scoped>
.print-hide {
    display: block;
}
@media print {
    .print-hide {
        display: none !important;
    }
}
</style>
