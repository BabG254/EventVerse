export default defineNuxtRouteMiddleware((to, from) => {
  const auth = useAuthStore()
  
  if (!auth.token && to.path !== '/') {
    return navigateTo('/')
  }
  
  if (auth.token && to.path === '/') {
    return navigateTo('/dashboard')
  }
})