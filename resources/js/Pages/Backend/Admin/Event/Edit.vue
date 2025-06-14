<template>
    <q-page padding>
        <q-card class="shadow-2 rounded-borders">
            <!-- Header -->
            <q-card-section class="row items-center q-gutter-sm">
                <q-icon name="edit_calendar" color="primary" size="md" />
                <div class="text-h6 text-primary text-weight-bold">Edit Event</div>
            </q-card-section>

            <q-separator />

            <!-- Form Section -->
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
                            autogrow
                            clearable
                            :error="!!form.errors.description"
                            :error-message="form.errors.description"
                        />

                        <!-- Current Image Preview -->
                        <div>
                            <div class="text-subtitle2 text-weight-medium">Current Event Image</div>
                            <q-img
                                v-if="event.image"
                                :src="`/storage/${event.image}`"
                                style="max-width: 200px; border-radius: 8px"
                                class="q-mb-sm"
                            />
                            <q-uploader
                                label="Replace Event Image"
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
                            prefix-icon="event"
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
                        <div class="text-subtitle1 text-weight-bold q-mt-md">Edit Options</div>

                        <div class="row q-col-gutter-md">
                            <div
                                v-for="(option, index) in form.options"
                                :key="option.id"
                                class="col-xs-12 col-md-6"
                            >
                                <q-card flat bordered class="q-pa-md">
                                    <div class="text-subtitle2 text-weight-medium q-mb-sm">
                                        Option {{ index + 1 }}
                                    </div>

                                    <q-input
                                        v-model="option.label"
                                        label="Label"
                                        outlined
                                        dense
                                        clearable
                                        :error="!!form.errors[`options.${index}.label`]"
                                        :error-message="form.errors[`options.${index}.label`]"
                                    />

                                    <q-input
                                        v-model="option.description"
                                        label="Description"
                                        type="textarea"
                                        outlined
                                        dense
                                        autogrow
                                        clearable
                                        :error="!!form.errors[`options.${index}.description`]"
                                        :error-message="form.errors[`options.${index}.description`]"
                                    />

                                    <!-- Current Option Image -->
                                    <div class="q-mt-sm">
                                        <div class="text-caption text-weight-medium q-mb-xs">Current Image</div>
                                        <q-img
                                            v-if="option.oldImage"
                                            :src="`/storage/${option.oldImage}`"
                                            style="max-width: 200px; border-radius: 8px"
                                            class="q-mb-sm"
                                        />
                                        <q-uploader
                                            label="Replace Image"
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
                                label="Update Event"
                                type="submit"
                                color="primary"
                                icon="check"
                                :loading="form.processing"
                                class="full-width"
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
import { computed } from 'vue';

const props = defineProps({
    event: Object
});

const form = useForm({
    title: props.event.title,
    description: props.event.description,
    image: null,
    bet_closes_at: props.event.bet_closes_at,
    status: props.event.status,
    options: props.event.options.map(opt => ({
        id: opt.id,
        label: opt.label,
        description: opt.description,
        image: null, // new image to upload
        oldImage: opt.image, // for preview
    }))
});

const submitForm = () => {
    form.post(route('admin.events.update', props.event.id), {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => {
            form.reset('image');
            form.options.forEach(o => (o.image = null));
        }
    });
};
</script>
