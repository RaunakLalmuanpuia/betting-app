<template>
    <q-page class="flex flex-center q-pa-md">
        <q-card class="q-pa-md q-ma-md" style="width: 100%; max-width: 600px">
            <q-card-section>
                <div class="text-h6">Change Password</div>
                <q-breadcrumbs class="text-grey-7 text-caption q-mt-sm">
                    <q-breadcrumbs-el label="Dashboard" @click="$inertia.get(route('dashboard'))" class="cursor-pointer" />
                    <q-breadcrumbs-el label="Profile" @click="$inertia.get(route('profile.edit'))" class="cursor-pointer" />
                </q-breadcrumbs>
            </q-card-section>

            <q-form @submit.prevent="handleSubmit" class="q-gutter-md q-pa-sm">
                <q-input
                    v-model="form.old_password"
                    type="password"
                    label="Old Password"
                    outlined
                    :error="!!form.errors.old_password"
                    :error-message="form.errors.old_password"
                />

                <q-input
                    v-model="form.password"
                    type="password"
                    label="New Password"
                    outlined
                    :error="!!form.errors.password"
                    :error-message="form.errors.password"
                />

                <q-input
                    v-model="form.password_confirmation"
                    type="password"
                    label="Confirm New Password"
                    outlined
                    :error="!!form.errors.password_confirmation"
                    :error-message="form.errors.password_confirmation"
                />

                <div class="row q-col-gutter-sm q-pt-md">
                    <div class="col">
                        <q-btn label="Update" type="submit" color="primary" class="full-width" />
                    </div>
                    <div class="col">
                        <q-btn label="Cancel" color="red" outline class="full-width" @click="handleBack" />
                    </div>
                </div>
            </q-form>
        </q-card>
    </q-page>
</template>
<script setup>
import BackendLayout from "../../../Layouts/BackendLayout.vue";
import { useForm } from "@inertiajs/vue3";
import { useQuasar } from 'quasar';

const $q = useQuasar();

defineOptions({
    layout: BackendLayout,
});

const form = useForm({
    old_password: '',
    password: '',
    password_confirmation: '',
});

const handleSubmit = () => {
    form.put(route('profile.update-password'), {
        onSuccess: () => {
            $q.dialog({
                title: 'Success',
                message: 'Password updated successfully!',
                ok: true,
                color: 'green-6',
                icon: 'check_circle',
            });
        },
        onError: () => {
            $q.dialog({
                title: 'Error',
                message: 'Please correct the form errors.',
                ok: true,
                color: 'red-6',
                icon: 'error',
            });
        },
    });
};

const handleBack = () => {
    window.history.back();
};
</script>
