<template>
    <Head title="Edit Profile" />

    <q-page class="flex flex-center q-pa-md">
        <q-card class="q-pa-md q-ma-md" style="width: 100%; max-width: 600px">
            <q-card-section>
                <div class="text-h5">Edit Profile</div>
                <q-breadcrumbs class="text-caption q-mt-sm text-grey-7">
                    <q-breadcrumbs-el label="Dashboard" @click="$inertia.get(route('dashboard'))" class="cursor-pointer" />
                    <q-breadcrumbs-el label="Profile" @click="$inertia.get(route('profile.edit'))" class="cursor-pointer" />
                </q-breadcrumbs>
            </q-card-section>

            <q-card-section>
                <q-form @submit.prevent="handleSubmit" class="q-gutter-md">
                    <q-input
                        filled
                        v-model="form.name"
                        label="User Name"
                        :error="!!form.errors?.name"
                        :error-message="form.errors?.name"
                    />

                    <q-input
                        filled
                        v-model="form.mobile"
                        label="Mobile"
                        :error="!!form.errors?.mobile"
                        :error-message="form.errors?.mobile"
                    />

                    <q-input
                        filled
                        type="email"
                        v-model="form.email"
                        label="Email"
                        :error="!!form.errors?.email"
                        :error-message="form.errors?.email"
                    />

                    <div class="row q-col-gutter-sm">
                        <div class="col">
                            <q-btn label="Update" type="submit" color="primary" class="full-width" />
                        </div>
                        <div class="col">
                            <q-btn label="Cancel" flat color="negative" @click="handleBack" class="full-width" />
                        </div>
                    </div>
                </q-form>
            </q-card-section>
        </q-card>

        <!-- Success Popup -->

    </q-page>
</template>

<script setup>
import BackendLayout from "../../../Layouts/BackendLayout.vue";
import { Head, useForm, usePage } from "@inertiajs/vue3";

import { useQuasar } from 'quasar'
const $q = useQuasar()
defineOptions({
    layout: BackendLayout,
});


const form = useForm({
    name: usePage().props.auth?.user?.name,
    mobile: usePage().props.auth?.user?.mobile,
    email: usePage().props.auth?.user?.email,
});

const handleSubmit = () => {
    form.put(route("profile.update"), {
        onSuccess: () => {
            $q.dialog({
                title: 'Success',
                message: 'Profile updated successfully!',
                ok: 'OK',
                color: 'green',
                icon: 'check_circle',
            })
        },
    });
};

const handleBack = () => {
    window.history.back();
};
</script>
