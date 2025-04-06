<template>
    <Head title="Login" />

    <AuthLayout>
        <div class="auth-base">
            <div class="logo">
                <a href="{{ route('home') }}">
                    <img src="/logo/VoteTracks.png" alt="Vote Tracks Logo" class="logo-img" />
                </a>
            </div>

            <h1>Login</h1>
            <p>Enter your details below to log into your account</p>

            <form method="POST" id="login-form" class="form-container" @submit.prevent="login">
                <div class="input-group">
                    <label for="email">Email address</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        v-model="form.email"
                        :class="{ 'input-error': form.errors.email }"
                        placeholder="email@example.com"
                        autofocus
                    />
                    <span v-if="form.errors.email" class="error-message">{{ form.errors.email }}</span>
                </div>

                <div class="input-group">
                    <label for="password">Password</label>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        v-model="form.password"
                        :class="{ 'input-error': form.errors.password }"
                        placeholder="Password"
                    />
                    <span v-if="form.errors.password" class="error-message">{{ form.errors.password }}</span>
                </div>

                <button type="submit" class="submit-btn" :disabled="form.processing">Login</button>

                <div class="text-center">
                    <p>Don't have an account? <Link :href="route('register')" class="link">Create one</Link></p>
                </div>
            </form>
        </div>
    </AuthLayout>
</template>

<script setup lang="ts">
import AuthLayout from '@/layouts/AuthLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    email: '',
    password: '',
});

const login = () => {
    form.post(route('login'), {
        onError: (errors ) => {
            console.log(errors);
        }
    });
};
</script>

<style>
.auth-base .logo {
    text-align: center;
    margin-bottom: 20px;
}

.auth-base .logo-img {
    width: 120px;
    height: auto;
    border-radius: 50%;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.auth-base {
    background-color: #fff;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    width: 100%;
    max-width: 400px;
}

h1 {
    font-size: 24px;
    margin-bottom: 10px;
}

p {
    font-size: 14px;
    color: #666;
    margin-bottom: 20px;
}

/* Form Styling */
.form-container {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.input-group {
    display: flex;
    flex-direction: column;
}

label {
    font-size: 14px;
    margin-bottom: 5px;
    color: #333;
}

input {
    padding: 10px;
    font-size: 14px;
    border: 1px solid #ccc;
    border-radius: 4px;
    margin-bottom: 5px;
}

input:focus {
    outline: none;
    border-color: #007bff;
}

.input-error {
    border-color: red;
}

/* Error message styling */
.error-message {
    font-size: 12px;
    color: red;
}

/* Button Styling */
.submit-btn {
    padding: 12px;
    font-size: 16px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.submit-btn:hover {
    background-color: #0056b3;
}

.text-center {
    text-align: center;
    font-size: 14px;
    color: #555;
}

.link {
    text-decoration: underline;
    color: #007bff;
}

.link:hover {
    color: #0056b3;
}
</style>
