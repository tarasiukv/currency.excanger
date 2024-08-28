<script setup>
import useAuth from '@composables/auth.js';

const { user, register } = useAuth();
</script>

<template>
    <div class="container">
        <h2>Registration</h2>
        <form @submit.prevent="register">
            <div class="form-group">
                <label for="InputName">Name:</label>
                <input
                    type="name"
                    class="form-control"
                    id="InputName"
                    v-model="user.name"
                    required
                >
            </div>
            <div class="form-group">
                <label for="InputEmail">Email address:</label>
                <input
                    type="email"
                    class="form-control"
                    id="InputEmail"
                    aria-describedby="emailHelp"
                    v-model="user.email"
                    required
                >
                <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
            </div>
            <form>
                <div class="form-row">
                    <div class="col">
                        <label for="InputPassword">Password:</label>
                        <input
                            type="password"
                            class="form-control"
                            id="InputPassword"
                            v-model="user.password"
                            aria-describedby="passwordHelpBlock"
                            required
                        >
                    </div>
                    <small id="passwordHelpBlock" class="form-text text-muted">
                        Your password must be 8-20 characters long, contain letters and numbers, and must not contain spaces, special characters, or emoji.
                    </small>
                    <div class="col">
                        <label for="ConfirmPassword">Confirm password:</label>
                        <input
                            type="password"
                            class="form-control"
                            id="ConfirmPassword"
                            v-model="user.password_confirmation"
                            required
                        >
                    </div>
                </div>
            </form>
            <button type="submit" class="btn btn-primary">Register</button>
        </form>
    </div>
</template>

<style scoped>

</style>
