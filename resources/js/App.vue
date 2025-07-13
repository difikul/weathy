<template>
  <div class="min-h-screen flex flex-col bg-gray-50 dark:bg-gray-900">
    <MainNavigation />
    <div class="flex-1">
      <router-view />
    </div>
  </div>
</template>

<script setup>
import MainNavigation from './components/MainNavigation.vue';
</script>
