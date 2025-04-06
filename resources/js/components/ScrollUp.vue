<template>
    <!-- Scroll Up Button with Arrow Icon -->
    <button v-show="showScrollUpButton" @click="scrollUp" class="scroll-up-button">
        <i class="fas fa-arrow-up"></i>
    </button>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue';

const showScrollUpButton = ref(false);

const scrollUp = () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
};

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
});

const handleScroll = () => {
    if (window.scrollY > 300) {
        showScrollUpButton.value = true;
    } else {
        showScrollUpButton.value = false;
    }
};
</script>

<style scoped>
.scroll-up-button {
    position: fixed;
    bottom: 20px;
    right: 20px;
    padding: 10px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 20%;
    font-size: 18px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    opacity: 0.7;
    transition: opacity 0.3s;
}

.scroll-up-button:hover {
    background-color: #0056b3;
    opacity: 1;
}

.scroll-up-button:focus {
    outline: none;
}
</style>
