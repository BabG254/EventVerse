<template>
  <div class="space-y-4">
    <div v-for="event in events" :key="event.id" class="bg-white shadow rounded-lg p-6">
      <h3 class="text-lg font-semibold">{{ event.title }}</h3>
      <p class="text-gray-600">{{ event.description }}</p>
      <div class="mt-4 grid grid-cols-2 gap-4">
        <div>
          <p class="text-sm text-gray-500">Venue</p>
          <p>{{ event.venue }}</p>
        </div>
        <div>
          <p class="text-sm text-gray-500">Date</p>
          <p>{{ formatDate(event.date) }}</p>
        </div>
        <div>
          <p class="text-sm text-gray-500">Price</p>
          <p>${{ event.price }}</p>
        </div>
        <div>
          <p class="text-sm text-gray-500">Available Spots</p>
          <p>{{ event.max_attendees - event.attendees.length }}</p>
        </div>
      </div>
      <div class="mt-4">
        <button 
          @click="$emit('register', event)"
          class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
          :disabled="event.max_attendees <= event.attendees.length"
        >
          Register
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
interface Event {
  id: number
  title: string
  description: string
  venue: string
  date: string
  price: number
  max_attendees: number
  attendees: any[]
}

const props = defineProps<{
  events: Event[]
}>()

const formatDate = (date: string) => {
  return new Date(date).toLocaleDateString()
}
</script>