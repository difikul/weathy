<template>
  <nav class="bg-white/80 backdrop-blur shadow-md border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <div class="flex">
          <div class="-ml-2 mr-2 flex items-center md:hidden">
            <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-700">
              <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                <path v-if="!open" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                <path v-else class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
          <div class="flex-shrink-0 flex items-center">
            <span class="font-bold text-lg">Weathy</span>
          </div>
          <div class="hidden md:ml-6 md:flex md:space-x-8">
            <RouterLink to="/" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium" :class="linkClass('/')">Počasí</RouterLink>
            <RouterLink to="/dashboard" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium" :class="linkClass('/dashboard')">Nástěnka</RouterLink>
          </div>
        </div>
      </div>
    </div>
    <div v-if="open" class="md:hidden">
      <div class="pt-2 pb-3 space-y-1">
        <RouterLink to="/" class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium" :class="mobileLinkClass('/')">Počasí</RouterLink>
        <RouterLink to="/dashboard" class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium" :class="mobileLinkClass('/dashboard')">Nástěnka</RouterLink>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { ref } from 'vue';
import { useRoute, RouterLink } from 'vue-router';

const open = ref(false);
const route = useRoute();

function linkClass(path) {
  return route.path === path
    ? 'border-indigo-500 text-gray-900'
    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300';
}
function mobileLinkClass(path) {
  return route.path === path
    ? 'bg-indigo-50 border-indigo-500 text-indigo-700'
    : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800';
}
</script>
