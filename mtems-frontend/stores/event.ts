import { defineStore } from 'pinia'

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

export const useEventStore = defineStore('event', {
  state: () => ({
    events: [] as Event[],
    loading: false,
    error: null as string | null
  }),

  actions: {
    async fetchEvents(organizationSlug: string) {
      this.loading = true
      try {
        const { data } = await useFetch(`/api/${organizationSlug}/events`)
        this.events = data.value as Event[]
      } catch (error) {
        this.error = 'Failed to fetch events'
        console.error(error)
      } finally {
        this.loading = false
      }
    },

    async registerAttendee(organizationSlug: string, eventId: number, attendee: any) {
      try {
        const { data } = await useFetch(`/api/${organizationSlug}/events/${eventId}/attendees`, {
          method: 'POST',
          body: attendee
        })
        return data.value
      } catch (error) {
        this.error = 'Failed to register attendee'
        console.error(error)
        return null
      }
    }
  }
})