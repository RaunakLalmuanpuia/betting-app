<template>
    <q-page padding>
        <q-card>
            <q-card-section class="text-h6">Edit Event</q-card-section>
            <q-separator />

            <q-card-section>
                <q-form @submit.prevent="submitForm">
                    <div class="q-gutter-md">

                        <q-input v-model="form.title" label="Title" outlined dense :error="!!form.errors.title" :error-message="form.errors.title" />

                        <q-input v-model="form.description" label="Description" outlined dense type="textarea" :error="!!form.errors.description" :error-message="form.errors.description" />

                        <div>
                            <div class="text-subtitle2">Current Event Image</div>
                            <q-img :src="`/storage/${event.image}`" v-if="event.image" class="q-mb-sm" style="max-width: 200px" />

                            <q-uploader
                                label="Replace Event Image"
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

                                <div class="q-mt-sm">
                                    <div class="text-caption">Current Image</div>
                                    <q-img :src="`/storage/${option.oldImage}`" v-if="option.oldImage" style="max-width: 200px" />

                                    <q-uploader
                                        accept="image/*"
                                        :max-files="1"
                                        @added="files => option.image = files[0]"
                                        :factory="() => {}"
                                        label="Replace Image"
                                    />
                                    <div class="text-negative text-caption q-mt-xs" v-if="form.errors[`options.${index}.image`]">
                                        {{ form.errors[`options.${index}.image`] }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="q-mt-lg">
                            <q-btn label="Update Event" type="submit" color="primary" :loading="form.processing" />
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
