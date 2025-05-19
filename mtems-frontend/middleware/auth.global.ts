export default defineNuxtRouteMiddleware((to) => {
  const auth = useAuthStore()
  
  // Allow access to login page
  if (to.path === '/') {
    return
  }
  
  // Redirect to login if not authenticated
  if (!auth.token) {
    return navigateTo('/')
  }
})