<template>
    <q-layout view="lHh Lpr lFf">
        <Head title="Register" />

        <q-page-container>
            <q-page class="flex flex-center q-pa-md">
                <q-card class="q-pa-lg" style="width: 100%; max-width: 500px;">
                    <q-card-section>
                        <div v-if="$page.props.auth?.user" class="q-gutter-md">
                            <p class="text-body1">You are logged in</p>
                            <q-btn
                                flat
                                label="Go to dashboard"
                                color="primary"
                                @click="$inertia.get(route('dashboard'))"
                            />
                        </div>

                        <div v-else>
                            <!-- OTP Form -->
                            <div v-if="state.sentOtp">
                                <q-form @submit.prevent="handleOtp" class="q-gutter-md">
                                    <div class="text-h6">OTP</div>

                                    <q-input
                                        v-model="otpForm.mobile_otp"
                                        label="Mobile OTP"
                                        maxlength="4"
                                        outlined
                                        dense
                                        :error="!!otpForm.errors?.mobile_otp"
                                        :error-message="otpForm.errors?.mobile_otp"
                                    />

                                    <div class="text-primary cursor-pointer">Resend OTP</div>

                                    <div class="flex justify-center">
                                        <q-btn label="Confirm" type="submit" color="primary" class="full-width" />
                                    </div>
                                </q-form>
                            </div>

                            <!-- Sign Up Form -->
                            <q-form
                                v-else
                                @submit.prevent="handleSubmit"
                                class="q-gutter-md"
                            >
                                <div class="text-h6">Sign up</div>

                                <!-- Name -->
                                <q-input
                                    v-model="form.name"
                                    label="Name"
                                    outlined
                                    dense
                                    :error="!!form.errors?.name"
                                    :error-message="form.errors?.name"
                                />

                                <!-- Phone Number -->
                                <q-input
                                    v-model="form.mobile"
                                    label="Phone Number"
                                    outlined
                                    dense
                                    maxlength="10"
                                    :error="!!form.errors?.mobile"
                                    :error-message="form.errors?.mobile"
                                />

                                <!-- Email -->
                                <q-input
                                    v-model="form.email"
                                    label="Email"
                                    type="email"
                                    outlined
                                    dense
                                    :error="!!form.errors?.email"
                                    :error-message="form.errors?.email"
                                />

                                <!-- Password -->
                                <q-input
                                    v-model="form.password"
                                    label="Password"
                                    :type="state.visiblePassword ? 'text' : 'password'"
                                    outlined
                                    dense
                                    :error="!!form.errors?.password"
                                    :error-message="form.errors?.password"
                                >
                                    <template #append>
                                        <q-icon
                                            :name="state.visiblePassword ? 'visibility_off' : 'visibility'"
                                            class="cursor-pointer"
                                            @click="state.visiblePassword = !state.visiblePassword"
                                        />
                                    </template>
                                </q-input>

                                <!-- Confirm Password -->
                                <q-input
                                    v-model="form.password_confirmation"
                                    label="Confirm Password"
                                    :type="state.visiblePassword ? 'text' : 'password'"
                                    outlined
                                    dense
                                    :error="!!form.errors?.password_confirmation"
                                    :error-message="form.errors?.password_confirmation"
                                />

                                <div class="flex justify-center">
                                    <q-btn label="Next" type="submit" color="primary" class="full-width" />
                                </div>
                            </q-form>
                        </div>
                    </q-card-section>
                </q-card>
            </q-page>
        </q-page-container>
    </q-layout>
</template>

<script setup>
import { Head, useForm } from "@inertiajs/vue3";
import { reactive } from "vue";

const state = reactive({
    sentOtp: false,
    visiblePassword: false,
});

const form = useForm({
    name: "",
    mobile: "",
    email: "",
    password: "",
    password_confirmation: "",
});

const otpForm = useForm({
    mobile_otp: "",
    email_otp: "",
});

const handleOtp = () => {
    otpForm
        .transform((data) => ({ ...data, ...form.data() }))
        .post(route("register.confirm-otp"), {
            onSuccess: () => {
                state.sentOtp = false;
            },
        });
};

const handleSubmit = () => {
    axios
        .post(route("register.send-otp"), form.data())
        .then((res) => {
            if (res.data.status) {
                state.sentOtp = true;
            }
            form.setError({});
        })
        .catch((err) => {
            state.sentOtp = false;
            if (err?.response?.data?.errors) {
                form.setError(err.response.data.errors);
            }
        });
};
</script>
