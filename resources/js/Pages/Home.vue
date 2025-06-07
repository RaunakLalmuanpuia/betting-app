<script setup>

</script>

<template>
<p class="text-3xl font-bold text-green-500">Betting App</p>
    <q-btn
        label="Login"
        color="primary"
        class="q-mr-md"
        @click="$inertia.get(route('login'))"
    />
    <q-btn
        label="Register"
        class="q-mr-md"
        color="primary"
        @click="$inertia.get(route('register.create'))"
    />
</template>

<style scoped>

</style>
