<script setup>
import useAuth from '@composables/auth.js';
import HeaderComponent from "@components/features/HeaderComponent.vue";

const { email, password, login } = useAuth();
</script>

<template>
    <HeaderComponent />
    <div class="container">
        <h2>Login</h2>
        <form @submit.prevent="login">
            <div class="form-group">
                <label for="InputEmail">Email address</label>
                <input
                    type="email"
                    class="form-control"
                    id="InputEmail"
                    aria-describedby="emailHelp"
                    v-model="email"
                    required
                >
                <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
            </div>
            <div class="form-group">
                <label for="InputPassword">Password</label>
                <input
                    type="password"
                    class="form-control"
                    id="InputPassword"
                    v-model="password"
                    required
                >
            </div>
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="exampleCheck1">
                <label class="form-check-label" for="exampleCheck1">Check me out</label>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>
</template>

<style scoped>
</style>
