<template>
  <NuxtLayout>
    <NuxtLoadingIndicator />
    <NuxtPage />
  </NuxtLayout>
</template>

<script setup>
const route = useRoute()
</script>

<style>
/* Global styles */
body {
  margin: 0;
  font-family: 'Inter', sans-serif;
  background-color: #f9fafb;
}

/* Remove Nuxt splash screen */
#__nuxt {
  display: none;
}

/* Show content once loaded */
#__nuxt:has(> div) {
  display: block;
}

/* Loading indicator customization */
.nuxt-loading-indicator {
  background: repeating-linear-gradient(
    to right,
    #4f46e5 0%,
    #818cf8 50%,
    #4f46e5 100%
  );
}
</style>