<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b border-gray-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex">
            <div class="flex-shrink-0 flex items-center">
              <h1 class="text-xl font-bold text-indigo-600">{{ organizationName }}</h1>
            </div>
          </div>
          <div class="flex items-center space-x-4">
            <span class="text-sm text-gray-500">{{ auth.user?.name }}</span>
            <button
              @click="handleLogout"
              class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors"
            >
              Logout
            </button>
          </div>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <div class="py-10">
      <header>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex justify-between items-center">
            <div>
              <h1 class="text-3xl font-bold text-gray-900">Events</h1>
              <p class="mt-1 text-sm text-gray-500">Manage your organization's events</p>
            </div>
            <button
              @click="showCreateModal = true"
              class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors"
            >
              <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
              </svg>
              Create Event
            </button>
          </div>
        </div>
      </header>
      <main>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="px-4 py-8 sm:px-0">
            <div v-if="loading" class="text-center py-12">
              <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 mx-auto"></div>
              <p class="mt-4 text-gray-500">Loading events...</p>
            </div>
            <div v-else-if="error" class="text-center py-12">
              <p class="text-red-500">{{ error }}</p>
              <button
                @click="fetchEvents"
                class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700"
              >
                Retry
              </button>
            </div>
            <div v-if="events.length === 0" class="text-center py-12">
              <p class="text-gray-500">No events yet. Create your first event!</p>
            </div>
            <div v-else class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
              <div v-for="event in events" :key="event.id" class="bg-white overflow-hidden shadow-lg rounded-lg transition-all hover:shadow-xl">
                <div class="px-6 py-5">
                  <div class="flex justify-between items-start">
                    <h3 class="text-xl font-semibold text-gray-900">{{ event.title }}</h3>
                    <span class="px-2 py-1 text-xs font-medium rounded-full" 
                      :class="event.max_attendees - (event.attendees?.length || 0) > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
                      {{ event.max_attendees - (event.attendees?.length || 0) }} spots left
                    </span>
                  </div>
                  <p class="mt-2 text-sm text-gray-600 line-clamp-2">{{ event.description }}</p>
                  <div class="mt-4 space-y-2">
                    <div class="flex items-center text-sm text-gray-600">
                      <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                      </svg>
                      {{ event.venue }}
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                      <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                      </svg>
                      {{ formatDate(event.date) }}
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                      <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>
                      ${{ event.price }}
                    </div>
                  </div>
                  <div class="mt-6 flex justify-between items-center">
                    <button
                      @click="showAttendees(event)"
                      class="inline-flex items-center px-4 py-2 border border-indigo-500 text-sm font-medium rounded-md text-indigo-600 bg-white hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors"
                    >
                      <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                      </svg>
                      Attendees
                    </button>
                    <div class="flex space-x-2">
                      <button
                        @click="editEvent(event)"
                        class="p-2 text-gray-600 hover:text-indigo-600 transition-colors"
                        title="Edit Event"
                      >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                      </button>
                      <button
                        @click="deleteEvent(event.id)"
                        class="p-2 text-gray-600 hover:text-red-600 transition-colors"
                        title="Delete Event"
                      >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>

    <!-- Create/Edit Event Modal -->
    <div v-if="showCreateModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
      <div class="bg-white rounded-xl p-8 max-w-md w-full shadow-xl transform transition-all">
        <div class="flex justify-between items-center mb-6">
          <h2 class="text-2xl font-bold text-gray-900">{{ editingEvent ? 'Edit Event' : 'Create Event' }}</h2>
          <button
            @click="showCreateModal = false"
            class="text-gray-400 hover:text-gray-500 transition-colors"
          >
            <span class="sr-only">Close</span>
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
        <form @submit.prevent="handleSubmit" class="space-y-6">
          <div class="space-y-5">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Event Title</label>
              <input
                v-model="eventForm.title"
                type="text"
                required
                placeholder="Enter event title"
                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
              <textarea
                v-model="eventForm.description"
                rows="3"
                placeholder="Enter event description"
                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors"
              ></textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Venue</label>
                <input
                  v-model="eventForm.venue"
                  type="text"
                  required
                  placeholder="Enter venue"
                  class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date & Time</label>
                <input
                  v-model="eventForm.date"
                  type="datetime-local"
                  required
                  class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors"
                />
              </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Price ($)</label>
                <input
                  v-model="eventForm.price"
                  type="number"
                  step="0.01"
                  required
                  placeholder="0.00"
                  class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Maximum Attendees</label>
                <input
                  v-model="eventForm.max_attendees"
                  type="number"
                  required
                  placeholder="Enter max attendees"
                  class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors"
                />
              </div>
            </div>
          </div>
          <div class="flex justify-end space-x-3 pt-2">
            <button
              type="button"
              @click="showCreateModal = false"
              class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors"
            >
              Cancel
            </button>
            <button
              type="submit"
              :disabled="loading"
              class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors disabled:opacity-50"
            >
              <svg v-if="loading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              {{ editingEvent ? 'Update Event' : 'Create Event' }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Attendees Modal -->
    <div v-if="showAttendeesModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
      <div class="bg-white rounded-xl p-8 max-w-4xl w-full shadow-xl transform transition-all">
        <div class="flex justify-between items-center mb-6">
          <div>
            <h2 class="text-2xl font-bold text-gray-900">{{ selectedEvent?.title }}</h2>
            <p class="mt-1 text-sm text-gray-500">Manage event attendees</p>
          </div>
          <button
            @click="showAttendeesModal = false"
            class="text-gray-400 hover:text-gray-500 transition-colors"
          >
            <span class="sr-only">Close</span>
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
        <div class="mt-4">
          <div class="flex justify-between items-center mb-6">
            <div class="flex items-center space-x-2">
              <h3 class="text-lg font-medium text-gray-900">Registered Attendees</h3>
              <span class="px-2.5 py-0.5 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                {{ selectedEvent?.attendees?.length || 0 }} / {{ selectedEvent?.max_attendees }}
              </span>
            </div>
            <button
              @click="showAddAttendeeForm = true"
              class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors"
            >
              <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
              </svg>
              Add Attendee
            </button>
          </div>
          <div class="bg-white shadow rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                  <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="attendee in selectedEvent?.attendees" :key="attendee.id" class="hover:bg-gray-50">
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ attendee.name }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ attendee.email }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ attendee.phone }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <button
                      @click="deleteAttendee(attendee.id)"
                      class="text-red-600 hover:text-red-900 transition-colors focus:outline-none"
                    >
                      Delete
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Add Attendee Form -->
    <div v-if="showAddAttendeeForm" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
      <div class="bg-white rounded-xl p-8 max-w-md w-full shadow-xl transform transition-all">
        <div class="flex justify-between items-center mb-6">
          <h2 class="text-2xl font-bold text-gray-900">Add New Attendee</h2>
          <button
            @click="showAddAttendeeForm = false"
            class="text-gray-400 hover:text-gray-500 transition-colors"
          >
            <span class="sr-only">Close</span>
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
        <form @submit.prevent="handleAddAttendee" class="space-y-6">
          <div class="space-y-5">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
              <input
                v-model="attendeeForm.name"
                type="text"
                required
                placeholder="Enter attendee's name"
                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
              <input
                v-model="attendeeForm.email"
                type="email"
                required
                placeholder="Enter email address"
                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
              <input
                v-model="attendeeForm.phone"
                type="tel"
                required
                placeholder="Enter phone number"
                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors"
              />
            </div>
          </div>
          <div class="flex justify-end space-x-3 pt-2">
            <button
              type="button"
              @click="showAddAttendeeForm = false"
              class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors"
            >
              Cancel
            </button>
            <button
              type="submit"
              :disabled="loading"
              class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors disabled:opacity-50"
            >
              <svg v-if="loading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              Add Attendee
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useAuthStore } from '~/stores/auth'
import { useRouter } from 'vue-router'

const auth = useAuthStore()
const router = useRouter()

onMounted(() => {
  // Redirect to login if not authenticated
  if (!auth.isAuthenticated) {
    router.push('/login')
  }
})

const events = ref([])
const showCreateModal = ref(false)
const showAttendeesModal = ref(false)
const showAddAttendeeForm = ref(false)
const editingEvent = ref(null)
const selectedEvent = ref(null)

const eventForm = ref({
  title: '',
  description: '',
  venue: '',
  date: '',
  price: '',
  max_attendees: ''
})

const attendeeForm = ref({
  name: '',
  email: '',
  phone: ''
})

const organizationName = computed(() => auth.user?.organization?.name || 'Organization')

onMounted(async () => {
  await fetchEvents()
})

const loading = ref(false)
const error = ref(null)

const fetchEvents = async () => {
  loading.value = true
  error.value = null
  
  try {
    const config = useRuntimeConfig()
    const { data, error: fetchError } = await useFetch(`${config.public.apiBase}/api/${auth.user?.organization?.slug}/events`, {
      headers: {
        'Authorization': `Bearer ${auth.token}`
      }
    })
    
    if (fetchError.value) {
      error.value = 'Failed to load events. Please try again.'
      console.error('Error fetching events:', fetchError.value)
      return
    }
    
    events.value = data.value
  } catch (err) {
    error.value = 'An unexpected error occurred'
    console.error('Error:', err)
  } finally {
    loading.value = false
  }
}

// Add loading states to other operations
const handleSubmit = async () => {
  try {
    loading.value = true
    error.value = null
    const config = useRuntimeConfig()
    const url = editingEvent.value
      ? `${config.public.apiBase}/api/${auth.user?.organization?.slug}/events/${editingEvent.value.id}`
      : `${config.public.apiBase}/api/${auth.user?.organization?.slug}/events`

    const { error } = await useFetch(url, {
      method: editingEvent.value ? 'PUT' : 'POST',
      body: eventForm.value,
      headers: {
        'Authorization': `Bearer ${auth.token}`
      }
    })

    if (error.value) {
      console.error('Error submitting event:', error.value)
      return
    }

    await fetchEvents()
    showCreateModal.value = false
    resetForm()
  } catch (err) {
    console.error('Error:', err)
  }
}

const handleAddAttendee = async () => {
  try {
    loading.value = true
    error.value = null
    const config = useRuntimeConfig()
    const { error } = await useFetch(
      `${config.public.apiBase}/api/${auth.user?.organization?.slug}/events/${selectedEvent.value.id}/attendees`, 
      {
        method: 'POST',
        body: attendeeForm.value,
        headers: {
          'Authorization': `Bearer ${auth.token}`
        }
      }
    )

    if (error.value) {
      console.error('Error adding attendee:', error.value)
      return
    }

    await showAttendees(selectedEvent.value)
    showAddAttendeeForm.value = false
    attendeeForm.value = { name: '', email: '', phone: '' }
  } catch (err) {
    console.error('Error:', err)
  }
}

const deleteAttendee = async (attendeeId) => {
  if (!confirm('Are you sure you want to remove this attendee?')) return

  try {
    const config = useRuntimeConfig()
    const { error } = await useFetch(
      `${config.public.apiBase}/api/${auth.user?.organization?.slug}/events/${selectedEvent.value.id}/attendees/${attendeeId}`,
      {
        method: 'DELETE',
        headers: {
          'Authorization': `Bearer ${auth.token}`
        }
      }
    )

    if (error.value) {
      console.error('Error deleting attendee:', error.value)
      return
    }

    await showAttendees(selectedEvent.value)
  } catch (err) {
    console.error('Error:', err)
  }
}

const editEvent = (event) => {
  editingEvent.value = event
  eventForm.value = { ...event }
  showCreateModal.value = true
}

const deleteEvent = async (id) => {
  if (!confirm('Are you sure you want to delete this event?')) return

  const config = useRuntimeConfig()
  await useFetch(`${config.public.apiBase}/api/${auth.user?.organization?.slug}/events/${id}`, {
    method: 'DELETE',
    headers: {
      'Authorization': `Bearer ${auth.token}`
    }
  })

  await fetchEvents()
}

const resetForm = () => {
  editingEvent.value = null
  eventForm.value = {
    title: '',
    description: '',
    venue: '',
    date: '',
    price: '',
    max_attendees: ''
  }
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

const handleLogout = async () => {
  const success = await auth.logout()
  if (success) {
    router.push('/')
  }
}

const showAttendees = async (event) => {
  try {
    const config = useRuntimeConfig()
    const { data, error } = await useFetch(
      `${config.public.apiBase}/api/${auth.user?.organization?.slug}/events/${event.id}`,
      {
        headers: {
          'Authorization': `Bearer ${auth.token}`
        }
      }
    )

    if (error.value) {
      console.error('Error fetching attendees:', error.value)
      return
    }

    selectedEvent.value = data.value
    showAttendeesModal.value = true
  } catch (err) {
    console.error('Error:', err)
  }
}
</script>