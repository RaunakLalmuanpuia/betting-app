<template>
    <q-page padding>
        <q-card>
            <q-card-section class="text-h6">Create New Event</q-card-section>
            <q-separator />

            <q-card-section>
                <q-form @submit.prevent="submitForm">
                    <div class="q-gutter-md">

                        <q-input v-model="form.title" label="Title" outlined dense :error="!!form.errors.title" :error-message="form.errors.title" />

                        <q-input v-model="form.description" label="Description" outlined dense type="textarea" :error="!!form.errors.description" :error-message="form.errors.description" />

                        <div>
                            <div class="text-subtitle2">Event Image</div>
                            <q-uploader
                                label="Upload Event Image"
                                accept="image/*"
                                :max-files="1"
                                @added="files => form.image = files[0]"
                                :factory="() => {}"
                            />
                            <div class="text-negative text-caption q-mt-xs" v-if="form.errors.image">{{ form.errors.image }}</div>
                        </div>

                        <q-input v-model="form.bet_closes_at" label="Bet Closes At" outlined dense type="datetime-local" :error="!!form.errors.bet_closes_at" :error-message="form.errors.bet_closes_at" />

                        <q-select
                            v-model="form.status"
                            :options="['open', 'closed', 'settled']"
                            label="Status"
                            outlined
                            dense
                            :error="!!form.errors.status"
                            :error-message="form.errors.status"
                        />

                        <div class="text-subtitle1 q-mt-md">Options</div>

                        <div class="row q-col-gutter-md">
                            <div v-for="(option, index) in form.options" :key="index" class="col-xs-12 col-md-6">
                                <q-input v-model="option.label" :label="`Option ${index + 1} Label`" outlined dense
                                         :error="!!form.errors[`options.${index}.label`]"
                                         :error-message="form.errors[`options.${index}.label`]" />

                                <q-input v-model="option.description" :label="`Option ${index + 1} Description`" outlined dense
                                         :error="!!form.errors[`options.${index}.description`]"
                                         :error-message="form.errors[`options.${index}.description`]"/>

                                <div>
                                    <div class="text-caption">Option {{ index + 1 }} Image</div>
                                    <q-uploader
                                        accept="image/*"
                                        :max-files="1"
                                        @added="files => option.image = files[0]"
                                        :factory="() => {}"
                                    />
                                    <div class="text-negative text-caption q-mt-xs" v-if="form.errors[`options.${index}.image`]">
                                        {{ form.errors[`options.${index}.image`] }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="q-mt-lg">
                            <q-btn label="Create Event" type="submit" color="primary" :loading="form.processing" />
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
