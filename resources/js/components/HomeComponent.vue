<script setup>
import {onMounted} from 'vue';
import store from '@store/store.js';
import useAuth from "@composables/auth.js";
const { login, logout, checkAuthStatus } = useAuth();
const user_id = localStorage.getItem('user_id');

onMounted(async () => {
    await checkAuthStatus();
})
</script>

<template>
    <ul class="nav justify-content-end">
        <li class="nav-item">
            <a class="nav-link active" href="/">Home</a>
        </li>
        <li class="nav-item" v-if="!store.state.is_logged_in">
            <a class="nav-link" href="/login">Login</a>
        </li>
        <li class="nav-item" v-if="store.state.is_logged_in">
            <a class="nav-link" href="/dashboard">Dashboard</a>
        </li>
        <li class="nav-item" v-if="store.state.is_logged_in">
            <a class="nav-link" href="/" @click.prevent="logout">Logout</a>
        </li>
    </ul>

</template>

<style scoped>

</style>
