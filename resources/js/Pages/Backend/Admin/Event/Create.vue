<template>
    <q-page padding>
        <q-card class="shadow-2 rounded-borders">
            <!-- Header -->
            <q-card-section class="row items-center q-gutter-sm">
                <q-icon name="event" color="primary" size="md" />
                <div class="text-h6 text-primary text-weight-bold">Create New Event</div>
            </q-card-section>

            <q-separator />

            <!-- Form -->
            <q-card-section>
                <q-form @submit.prevent="submitForm">
                    <div class="q-gutter-md">

                        <!-- Title -->
                        <q-input
                            v-model="form.title"
                            label="Title"
                            outlined
                            dense
                            clearable
                            :error="!!form.errors.title"
                            :error-message="form.errors.title"
                            prefix-icon="title"
                        />

                        <!-- Description -->
                        <q-input
                            v-model="form.description"
                            label="Description"
                            outlined
                            dense
                            type="textarea"
                            clearable
                            autogrow
                            :error="!!form.errors.description"
                            :error-message="form.errors.description"
                        />

                        <!-- Event Image -->
                        <div>
                            <div class="text-subtitle2 text-weight-medium q-mb-xs">Event Image</div>
                            <q-uploader
                                label="Upload Image"
                                accept="image/*"
                                flat
                                bordered
                                auto-expand
                                :max-files="1"
                                @added="files => form.image = files[0]"
                                :factory="() => {}"
                            />
                            <div class="text-negative text-caption q-mt-xs" v-if="form.errors.image">
                                {{ form.errors.image }}
                            </div>
                        </div>

                        <!-- Bet Closes At -->
                        <q-input
                            v-model="form.bet_closes_at"
                            label="Bet Closes At"
                            type="datetime-local"
                            outlined
                            dense
                            :error="!!form.errors.bet_closes_at"
                            :error-message="form.errors.bet_closes_at"
                            prefix-icon="schedule"
                        />

                        <!-- Status -->
                        <q-select
                            v-model="form.status"
                            :options="['open', 'closed', 'settled']"
                            label="Status"
                            outlined
                            dense
                            emit-value
                            map-options
                            :error="!!form.errors.status"
                            :error-message="form.errors.status"
                        />

                        <!-- Options -->
                        <div class="text-subtitle1 text-weight-bold q-mt-md">Event Options</div>

                        <div class="row q-col-gutter-md">
                            <div
                                v-for="(option, index) in form.options"
                                :key="index"
                                class="col-xs-12 col-md-6"
                            >
                                <q-card flat bordered class="q-pa-md">
                                    <div class="text-subtitle2 text-weight-medium q-mb-sm">
                                        Option {{ index + 1 }}
                                    </div>

                                    <q-input
                                        v-model="option.label"
                                        :label="`Label`"
                                        outlined
                                        dense
                                        clearable
                                        :error="!!form.errors[`options.${index}.label`]"
                                        :error-message="form.errors[`options.${index}.label`]"
                                    />

                                    <q-input
                                        v-model="option.description"
                                        :label="`Description`"
                                        outlined
                                        dense
                                        type="textarea"
                                        autogrow
                                        clearable
                                        :error="!!form.errors[`options.${index}.description`]"
                                        :error-message="form.errors[`options.${index}.description`]"
                                    />

                                    <div class="q-mt-sm">
                                        <div class="text-caption text-weight-medium q-mb-xs">Option Image</div>
                                        <q-uploader
                                            accept="image/*"
                                            flat
                                            bordered
                                            auto-expand
                                            :max-files="1"
                                            @added="files => option.image = files[0]"
                                            :factory="() => {}"
                                        />
                                        <div
                                            class="text-negative text-caption q-mt-xs"
                                            v-if="form.errors[`options.${index}.image`]"
                                        >
                                            {{ form.errors[`options.${index}.image`] }}
                                        </div>
                                    </div>
                                </q-card>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="q-mt-lg">
                            <q-btn
                                label="Create Event"
                                type="submit"
                                color="primary"
                                class="full-width"
                                :loading="form.processing"
                                icon="send"
                                unelevated
                                rounded
                            />
                        </div>
                    </div>
                </q-form>
            </q-card-section>
        </q-card>
    </q-page>
</template>


<script setup>
import BackendLayout from "../../../../Layouts/BackendLayout.vue";
defineOptions({ layout: BackendLayout });
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    title: '',
    description: '',
    image: '',
    bet_closes_at: '',
    status: 'open',
    options: [
        { label: '', description: '', image: '' },
        { label: '', description: '', image: '' },
    ]
});

const submitForm = () => {
    form.post(route('admin.events.store'));
};
</script>
