<template>
    <div class="app-layout">
        <header class="app-header">
            <div class="logo">
                <Link :href="route('home')">Vote Tracks</Link>
            </div>

            <div class="user-info">
                <span v-if="user" class="welcome-message">Welcome, {{ user.name }}!</span>
                <Link v-if="user" class="logout-button header-button" method="post" :href="route('logout')" as="button">Logout</Link>
                <Link v-if="!user" :href="route('register')" as="button" class="register-button header-button">Register</Link>
                <Link v-if="!user" :href="route('login')" as="button" class="login-button header-button">Login</Link>
            </div>
        </header>

        <main class="main-content">
            <slot></slot>
            <ScrollUp />
        </main>
    </div>
</template>

<script setup lang="ts">
import ScrollUp from '@/components/ScrollUp.vue';
import { Link, usePage } from '@inertiajs/vue3';

const { user } = usePage().props.auth;
</script>

<style scoped>
body,
.app-layout {
    margin: 0;
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
}

.app-header {
    background-color: white;
    padding: 16px 40px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    border-bottom: 1px solid #e0e0e0;
    position: sticky;
    top: 0;
    z-index: 1000;
}

.app-header .logo {
    font-size: 24px;
    font-weight: bold;
    color: #333;
    text-transform: uppercase;
}

.app-header .logo a {
    text-decoration: none;
}

.app-header .user-info {
    display: flex;
    align-items: center;
    gap: 20px;
}

.app-header .user-info .welcome-message {
    font-size: 16px;
    color: #333;
}

.app-header .header-button {
    display: inline-block;
    padding: 10px 20px;
    font-size: 12px;
    font-weight: 600;
    text-decoration: none;
    border-radius: 4px;
    transition:
        background-color 0.3s ease,
        color 0.3s ease;
    text-align: center;
    cursor: pointer;
}

.app-header .logout-button {
    background-color: #f44336;
    border: none;
    color: white;
}

.app-header .logout-button:hover {
    background-color: #d32f2f;
}

.login-button {
    background-color: #007bff;
    color: white;
    border: 1px solid #007bff;
}

.login-button:hover {
    background-color: #0056b3;
}

.register-button {
    background-color: #28a745;
    color: white;
    border: 1px solid #28a745;
}

.register-button:hover {
    background-color: #218838;
}

.main-content {
    padding: 20px;
    background-color: white;
    flex: 1;
    min-height: 100vh;
}

@media (max-width: 639px) {
    .welcome-message {
        display: none !important;
    }
}

</style>
