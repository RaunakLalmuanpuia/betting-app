<template>
    <q-layout view="lHh Lpr lFf">
        <Head title="Login" />

        <q-page-container>
            <q-page class="flex flex-center q-pa-md">
                <q-card class="q-pa-lg shadow-2" style="min-width: 360px; max-width: 420px;">
                    <q-card-section>
                        <div class="text-h5 text-primary">Login</div>
                        <div class="text-subtitle2 text-grey-7">Enter your credentials to login</div>
                    </q-card-section>

                    <q-card-section>
                        <div v-if="error" class="text-negative q-mb-md">
                            {{ error }}
                        </div>

                        <q-form @submit.prevent="handleSubmit">
                            <q-input
                                v-model="form.login"
                                label="Email ID / Phone No."
                                filled
                                dense
                                required
                            />
                            <div v-if="showError" class="text-caption text-negative q-mb-sm">
                                {{ page.props.errors.login }}
                            </div>

                            <q-input
                                v-model="form.password"
                                label="Password"
                                :type="state.visiblePassword ? 'text' : 'password'"
                                filled
                                dense
                                required
                                class="q-mt-md"
                            >
                                <template #append>
                                    <q-icon
                                        :name="state.visiblePassword ? 'visibility_off' : 'visibility'"
                                        class="cursor-pointer"
                                        @click="state.visiblePassword = !state.visiblePassword"
                                    />
                                </template>
                            </q-input>

                            <div class="q-mt-lg flex justify-center">
                                <q-btn label="Login" type="submit" color="primary" class="full-width" />
                            </div>
                        </q-form>
                    </q-card-section>

                    <q-card-actions align="between" class="q-mt-sm">
                        <q-btn
                            flat
                            label="Register New Account!"
                            color="secondary"
                            :to="route('register.create')"
                        />
                        <q-btn
                            flat
                            label="Forgot Password?"
                            color="secondary"
                            :to="route('login.forgot')"
                        />
                    </q-card-actions>
                </q-card>
            </q-page>
        </q-page-container>
    </q-layout>
</template>

<script setup>
import { Head, useForm, usePage } from "@inertiajs/vue3";
import { reactive, ref, watch } from "vue";

const form = useForm({
    login: "",
    password: "",
});

const page = usePage();
const state = reactive({
    visiblePassword: false,
});

defineProps({
    error: String,
});

const showError = ref(false);

const handleSubmit = () => {
    form.post(route("login.store"));
};

watch(
    () => page.props.errors,
    (errors) => {
        showError.value = Object.keys(errors).length > 0;
    },
    { immediate: true }
);
</script>
