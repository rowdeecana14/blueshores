<template>
    <div class="app-layout">
        <header class="app-header">
            <div class="logo">
                <Link :href="route('home')">Vote Tracks</Link>
            </div>

            <div class="user-info">
                <span class="welcome-message">Welcome, Guest!</span>
            </div>
        </header>

        <main class="main-content">
            <slot></slot>
        </main>
    </div>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
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
.main-content {
    padding: 20px;
    background-color: white;
    display: flex !important;
    justify-content: center !important;
    align-items: center !important;
}
</style>
